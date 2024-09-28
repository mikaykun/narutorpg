<?php

namespace NarutoRPG\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use NarutoRPG\Entity\SpecialFeatures;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use tageZuWerte;

#[AsCommand(name: 'narutorpg:training:update', description: 'Update the training data')]
final class TrainingUpdateCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly Connection $connection,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return Command::SUCCESS;
        }

        $output->writeln('Start update training data');

        $result = $this->connection->fetchAllAssociative(
            "SELECT * FROM user WHERE last_update < NOW()"
        );

        foreach ($result as $user) {
            $output->writeln('Update user ' . $user['id']);

            $this->connection->beginTransaction();
            try {
                $this->executeTrainingForUser($user);
                $this->connection->commit();
            } catch (\Throwable $e) {
                $this->connection->rollBack();
                throw $e;
            }
        }

        $output->writeln('Update finished');

        return Command::SUCCESS;
    }

    private function updateUser(array $spalten, $id): void
    {
        $spalten[] = 'lastup = :date';
        $spalten[] = 'last_update = NOW()';
        $spalten = implode(',', $spalten);

        $this->connection->executeStatement(
            'UPDATE user SET ' . $spalten . ' WHERE id = :id',
            [
                'date' => date("d.m.Y"),
                'id' => $id,
            ],
            [
                'date' => \PDO::PARAM_STR,
                'id' => \PDO::PARAM_INT,
            ]
        );
    }

    private function executeTrainingForUser(array $user): void
    {
        // Schreibe Log Eintrag
        $logMessage = sprintf(
            '%d %s trainiert %s für %s Tage am %s',
            $user['id'],
            $user['name'],
            $user['Training'],
            $user['Dauer'],
            date('d.m.Y')
        );
        $this->connection->executeStatement(
            'INSERT INTO Updatelog (Text) VALUES (:logMessage)',
            ['logMessage' => $logMessage]
        );

        // Käfer
        if ($user['Clan'] === 'Aburame Familie') {
            $Kaf = max($user['Kaefer'], 1);
            $Kaf = ceil($Kaf * 1.03);

            if ($user['Niveau'] == 2) {
                $Kaf = min($Kaf, 3_000);
            } elseif ($user['Niveau'] == 3) {
                $Kaf = min($Kaf, 6_000);
            } elseif ($user['Niveau'] == 4) {
                $Kaf = min($Kaf, 10_000);
            }

            $this->connection->executeStatement("UPDATE user SET Kaefer = :kaefer WHERE id = :id", [
                'kaefer' => $Kaf,
                'id' => $user['id'],
            ]);
        }

        // Bonus Berechnung
        $bonus = 1;
        if ($user['Bonustage'] > 0) {
            $bonus += 1.5;
        }
        if ($user['doubleup'] > 0) {
            $bonus += 1.5;
        }

        ### Next?
        $trainingwegmachen = 0; // 0 = nicht weg machen, 1 = weg machen
        $needsBonustage = false;
        $tage = new tageZuWerte();
        $u_besos = $this->entityManager->find(SpecialFeatures::class, $user['id']);

        switch ($user['Training']) {
            case 'Gentle Fist Style Training':
                if ($u_besos->isGentle() < 1 && $user['Clan'] == 'Hyuuga Clan') {
                    $u_besos->setGentle(true);
                    $this->entityManager->flush();
                }
                break;

            case "Jobben":
            case "D-Rang Mission":
            case "C-Rang Mission":
            case "B-Rang Mission":
                // TODO: Logic needs to be fixed!
                $Job = ['D' => 0, 'C' => 1, 'B' => 2];
                $Geld = $tage->geldplus(1, $Job[$user['Training'][0]]);
                $up = [
                    'Geld = Geld+' . $Geld,
                    $user['Training'][0] . ' = ' . $user['Training'][0] . '+1',
                ];
                $this->updateUser($up, $user['id']);
                break;

            case "St&auml;rketraining":
            case "Verteidigungstraining":
            case "Geschwindigkeitstraining":
                $needsBonustage = true;
                $user['Training'] = str_replace('&auml;', 'ae', (string)$user['Training']);
                $user['Training'] = str_replace('straining', '', $user['Training']);
                $user['Training'] = str_replace('training', '', $user['Training']);
                $wertName = str_replace('ae', 'ä', $user['Training']);
                $training = $tage->grundwerte($u_besos, $user['Niveau'], 1, $user['Training']) * $bonus;
                $this->updateUser([$wertName . ' = ROUND(' . $wertName . '+' . $training . ',3)'], $user['id']);
                $trainingwegmachen = ((($user[$wertName] + $training) >= $user['Biswert']) && $user['Biswert'] > 0) ? 1 : 0;
                break;

            case "Ausdauer Training":
                $needsBonustage = true;
                $training = $tage->ausdauer($u_besos, $user['Niveau'], 1) * $bonus;
                $this->updateUser(['Ausdauer = ROUND(Ausdauer+\'' . $training . '\',3)'], $user['id']);
                $trainingwegmachen = ((($user['Ausdauer'] + $training) >= $user['Biswert']) && $user['Biswert'] > 0) ? 1 : 0;
                break;

            case "Chakra Training":
                $needsBonustage = true;
                $training = $tage->chakra($u_besos, $user['Niveau'], 1) * $bonus;
                $this->updateUser(['Chakra = ROUND(Chakra+\'' . $training . '\',4)'], $user['id']);
                $trainingwegmachen = ((($user['Chakra'] + $training) >= $user['Biswert']) && $user['Biswert'] > 0) ? 1 : 0;
                break;
        }

        $Dauer = (int)$user['Dauer'];
        $Dauer -= 1;

        $btZusatz = [];
        if ($needsBonustage && $user['Bonustage'] > 0) {
            $btZusatz[] = '`Bonustage` = `Bonustage`-1';
        }
        if ($needsBonustage && $user['doubleup'] > 0) {
            $btZusatz[] = '`doubleup` = `doubleup`-1';
        }

        if ($trainingwegmachen == 1 || ($Dauer <= 0 && $user['Biswert'] <= 0)) {
            $btZusatz[] = "Training = ''";
            $btZusatz[] = "Dauer = 0";
            $this->updateUser($btZusatz, $user['id']);
        } elseif ($user['Biswert'] > 0) {
            $this->updateUser($btZusatz, $user['id']);
        } else {
            $btZusatz[] = "Dauer = '{$Dauer}'";
            $this->updateUser($btZusatz, $user['id']);
        }
    }
}
