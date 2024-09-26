<?php

declare(strict_types=1);

namespace NarutoRPG\EventSubscriber;

use NarutoRPG\Service\LegacyDatabaseConnection;
use NarutoRPG\SessionHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class MultiCharacterSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LegacyDatabaseConnection $connection) {}

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        // TODO: Fix multi character detection!
        if (is_user_logged_in() && $this->insertNewIP()) {
            $c_loged = SessionHelper::getUserId();
            $stmt = $this->connection->prepare("SELECT uId FROM IPs WHERE ip = :ip AND date = DATE(NOW()) AND uId != :uId");
            $stmt->execute([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uId' => SessionHelper::getUserId(),
            ]);

            while ($multi = $stmt->fetchObject()) {
                $multId = $multi->uId;
                $forumUser = $this->connection->prepare("SELECT Forumge FROM userdaten WHERE id = :id AND Forumge = 1");
                $forumUser->execute(['id' => $multi->uId]);

                if ($forumUser->rowCount() > 0) {
                    $this->connection->exec("UPDATE userdaten SET Forumge = '1' WHERE id = '$c_loged'");

                    $chatUser = $this->connection->prepare("SELECT uid, uname FROM ajax_chat_user WHERE aid = :aid");
                    $chatUser->execute(['aid' => $c_loged]);
                    while ($usr2 = $chatUser->fetchObject()) {
                        $insertStatement = $this->connection->prepare("INSERT INTO `ajax_chat_bans` (`userID`, `userName`, `dateTime`) VALUES (:userID, :userName, NOW() + INTERVAL 1 YEAR)");
                        $insertStatement->execute([
                            'userID' => $usr2->uid,
                            'userName' => $usr2->uname,
                        ]);
                    }
                }

                $stmt = $this->connection->prepare("INSERT INTO multi (`uId1`,`uId2`,multOk) VALUES (:uId1,:uId2,0)");
                if (!$stmt->execute(['uId1' => $c_loged, 'uId2' => $multi->uId])) {
                    $stmt = $this->connection->prepare('UPDATE `multi` SET `Counter` = `Counter`+1 WHERE (`uId1` = :uId1 && `uId2` = :uId2) || (`uId2` = :uId1 && `uId1` = :uId2)');
                    $stmt->execute([
                        'uId1' => $c_loged,
                        'uId2' => $multi->uId,
                    ]);
                } else {
                    $stmt = $this->connection->prepare("SELECT `name` FROM user WHERE id = :id");
                    $stmt->execute(['id' => $multi->uId]);
                    $multiDude = $stmt->fetchObject();

                    (new \PrivateMessage())
                        ->subject('Multiusing')
                        ->from('System', 0)
                        ->to($c_loged)
                        ->body("Hallo, es wurde festgestellt, dass du dich soeben erstmalig mit " . $multiDude->name . " über dieselbe IP-Adresse eingeloggt hast. Bitte teile der Administration mit, ob es sich tatsächlich um einen Multi von dir handelt. Dies kannst du zum Beispiel <a href=/Adminbriefkasten.php>im Adminbriefkasten</a> tun. Sollte eine andere Person sich über deine Leitung einloggen, so denkt daran, dass ihr, falls dies wiederholt geschieht, zudem das <a href=https://wiki.narutorpg.de/index.php?title=Multiusing#Beantragtes_Multiusing>Antragsverfahren für genehmigtes Multiusing</a> beachten müsst, um weiterhin uneingeschränkt am Spiel teilnehmen zu können.")
                        ->send();
                }

                $up = "INSERT INTO multi (`uId1`,`uId2`) VALUES ('$multId','$c_loged');";
                if ($this->connection->exec($up)) {
                    $sql4 = "SELECT `name` FROM user WHERE id = '$c_loged'";
                    $mainDude = $this->connection->query($sql4, \PDO::FETCH_OBJ);

                    (new \PrivateMessage())
                        ->subject('Multiusing')
                        ->from('System', 0)
                        ->to($multId)
                        ->body("Hallo, es wurde festgestellt, dass du dich soeben erstmalig mit " . $mainDude->name . " über dieselbe IP-Adresse eingeloggt hast. Bitte teile der Administration mit, ob es sich tatsächlich um einen Multi von dir handelt. Dies kannst du zum Beispiel <a href=/Adminbriefkasten.php>im Adminbriefkasten</a> tun. Sollte eine andere Person sich über deine Leitung einloggen, so denkt daran, dass ihr, falls dies wiederholt geschieht, zudem das <a href=https://wiki.narutorpg.de/index.php?title=Multiusing#Beantragtes_Multiusing>Antragsverfahren für genehmigtes Multiusing</a> beachten müsst, um weiterhin uneingeschränkt am Spiel teilnehmen zu können.")
                        ->send();
                }
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    private function insertNewIP(): bool
    {
        $this->connection->exec("DELETE FROM IPs WHERE DATEDIFF(DATE(NOW()), date) > 7");

        $statement = $this->connection->prepare("INSERT INTO IPs (`system`,ip,uId,date) VALUES (:system,:ip,:uId,DATE(NOW()))");
        $statement->bindValue(':system', gethostbyaddr($_SERVER['REMOTE_ADDR']) . " " . $_SERVER["HTTP_USER_AGENT"]);
        $statement->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
        $statement->bindValue(':uId', SessionHelper::getUserId(), \PDO::PARAM_INT);

        try {
            $statement->execute();
            return true;
        } catch (\PDOException) {
            return false;
        }
    }
}
