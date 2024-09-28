<?php

namespace NarutoRPG\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use NarutoRPG\Service\LegacyDatabaseConnection;
use NarutoRPG\Types\Villages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CharacterCreationController extends AbstractController
{
    public function __construct(
        private readonly Connection $connection
    ) {}

    #[Route('/Ninja.php', name: 'app.legacy.character_creation')]
    public function index(Request $request, LegacyDatabaseConnection $connection): Response
    {
        if (!is_user_logged_in()) {
            return $this->redirect('/');
        }

        $u_dat2 = nrpg_get_current_character();
        if ($u_dat2->feddig) {
            return $this->redirect('/Center.php');
        }

        $niveauUser = $u_dat2->Niveau;

        if ($niveauUser == 1) {
            $niveauMin = 1;
        } else {
            $niveauMin = ($niveauUser - 1);
        }

        $doerfer = Villages::all();

        if ($niveauUser == 1 || $niveauUser == 2 || $niveauUser == 3 || $niveauUser == 4) {
            $doerfer = array_slice(
                $doerfer,
                1
            );
        }

        $userDorf = [];
        foreach ($doerfer as $dorf) {
            $anzahl = 0;
            $sql = "SELECT id FROM user WHERE `Heimatdorf` = '$dorf' AND (`Niveau` = '" . $niveauUser . "' OR `Niveau` = '" . $niveauMin . "') AND `Inaktivitaet` = '0' AND `Rang` != 'Missing-Nin'";
            //$sql = "SELECT id FROM user WHERE `Heimatdorf` = '$dorf' AND `Inaktivitaet` = '0' AND `Rang` != 'Akademist'";
            $query = $connection->query($sql);
            while ($row = $query->fetchObject()) {
                $multiQ = "SELECT `uId2` FROM `multi` WHERE `multOk` = '2' AND `uId1` = '$row->id' AND `uId2` < `uId1`";
                $multiQ = $connection->query($multiQ);
                if (!$multii = $multiQ->fetchObject()) {
                    $multiQ = "SELECT `uId1` FROM `multi` WHERE `multOk` = '2' AND `uId2` = '$row->id' AND `uId1` < `uId2`";
                    $multiQ = $connection->query($multiQ);
                    if (!$multii = $multiQ->fetchObject()) {
                        $multiQ = "SELECT `mailBest`,`laston` FROM `userdaten` WHERE `id` = '$row->id'";
                        $multiQ = $connection->query($multiQ);
                        $multii = $multiQ->fetchObject();
                        if ($multii->mailBest == 1 && $multii->laston > time() - 60 * 60 * 24 * 60) {
                            $anzahl++;
                        }
                    }
                }
            }
            $userDorf[$dorf] = $anzahl;
        }

        asort($userDorf);

        $maxCurr = end($userDorf);
        $minCurr = reset($userDorf);
        $rangeCurr = $maxCurr - $minCurr;
        $borderSteps = floor($rangeCurr / 3);
        $border1 = $minCurr + $borderSteps;
        $border2 = $minCurr + $borderSteps * 2;
        $tp1 = 20;
        $tp2 = 10;
        $max = $minCurr * 4;
        $max = ($max < 8) ? 8 : $max;

        $villages = [];
        foreach ($userDorf as $dorf => $user) {
            if ($user > $max) {
                unset($userDorf[$dorf]);
            } else {
                $tp = ($user < $border1) ? $tp1 : (($user < $border2) ? $tp2 : 0);
                $villages[] = ['label' => "$dorf ($user Ninja, $tp TP)", 'value' => $dorf];
            }
        }

        return $this->render('character_creation/index.html.twig', [
            'villages' => $villages,
            'error' => $request->query->getString('error'),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/charamach.php', name: 'app.legacy.charamach', methods: ['POST'])]
    public function charamach(Request $request): Response
    {
        $current_character = nrpg_get_current_character();

        if (!is_user_logged_in() || $current_character->feddig) {
            return $this->redirect('/Center.php');
        }

        $postParams = $request->request;
        $fullName = $postParams->getString('Vorname') . ' ' . $postParams->getString('Nachname');
        $fullName = preg_replace('/ {2,}/', ' ', $fullName);
        $fullName = preg_replace('/[^a-zA-Z \-\_]/', '', $fullName);
        $fullName = strip_tags($fullName);

        if ($this->isInvalidName($fullName)) {
            return $this->redirectToRoute('app.legacy.character_creation', ['error' => 'invalid_name']);
        }

        // Now we start shuffling around the user's data
        $this->connection->beginTransaction();

        try {
            // Bluterbe
            $Bluterbe = htmlentities($postParams->getString('Bluterbe'));
            $Punkte = 30;
            switch ($Bluterbe) {
                case "Souma no Kou":
                case "Aburame Familie":
                case "Ningyosenshu Clan":
                    $Punkte -= 25;
                    break;
                case "Mokuton Bluterbe":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Wasser', Element2 = 'Erde', Element6 = 'Holz' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Elementmanipulation = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 25;
                    break;
                case "Kaguya Clan":
                case "Uchiha Clan":
                case "Jinchuriki Clan":
                case "Uzumaki Clan":
                case "Spinnenbluterbe":
                    $Punkte -= 30;
                    break;
                case "Inuzuka Familie":
                case "Akimichi Clan":
                    $Punkte -= 20;
                    break;
                case "Hyuuga Clan":
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Byakugan = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 30;
                    break;
                case "Fischmenschen":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Wasser' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 10;
                    break;
                case "Nara Familie":
                    $Punkte -= 15;
                    break;
                case "Deidara Clan":
                case "Yamanaka Familie":
                    $Punkte -= 10;
                    break;
                case "Hyouton Bluterbe":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Wasser', Element2 = 'Wind', Element6 = 'Eis' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Elementmanipulation = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 20;
                    break;
                case "Ranton Bluterbe":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Wasser', Element2 = 'Donner', Element6 = 'Ranton' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Elementmanipulation = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 20;
                    break;
                case "Youton Bluterbe":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Feuer', Element2 = 'Erde', Element6 = 'Youton' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Elementmanipulation = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 20;
                    break;
                case "Futton Bluterbe":
                    $this->connection->executeStatement(
                        "UPDATE user SET Element1 = 'Wasser', Element2 = 'Feuer', Element6 = 'Futton' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE Fähigkeiten SET Elementmanipulation = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte -= 20;
                    break;
            }

            // Keine Ahnung was das ist.
            $this->connection->executeStatement(
                "UPDATE user SET NinGenTaiAutoset = 1 WHERE id = :id",
                ['id' => $current_character->id]
            );

            // Nachteile
            $Nachteil = htmlentities($request->request->getString('Nachteil'));
            switch ($Nachteil) {
                case "sChakra":
                case "sAusdauer":
                    $Punkte += 15;
                    break;
                case "nTaijutsu":
                    $this->connection->executeStatement(
                        "UPDATE user SET Taijutsu = 10, allUp = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte += 0;
                    break;
                case "sGenjutsu":
                case "sNinjutsu":
                case "sTaijutsu":
                    $Punkte += 10;
                    break;
                case "sChakrakontrolle":
                    $this->connection->executeStatement(
                        "UPDATE user SET Ninjutsu = 6, Genjutsu = 6, Taijutsu = 10, allUp = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $Punkte += 20;
                    break;
                case "sKorper":
                    $Punkte += 20;
                    break;
            }

            // Vorteile
            $Vorteile = htmlentities($request->request->getString('Vorteile'));
            switch ($Vorteile) {
                case "Genie":
                    $Punkte -= 30;
                    break;
                case "Chakra":
                case "Ausdauernd":
                    $Punkte -= 20;
                    break;
                case "Verteidigung":
                case "Geschwindigkeit":
                case "Chakrakontrolle":
                case "Staerke":
                    $Punkte -= 10;
                    break;
            }

            // IQ
            $IQ = htmlentities($request->request->getString('IQ'));
            $Punkte -= match ($IQ) {
                "75" => -10,
                "100" => 0,
                "125" => 10,
                "150" => 15,
                default => 1000000,
            };

            if ($IQ != "100" && $Vorteile == "Genie") {
                $Punkte -= 999;
            }
            if ($Bluterbe != "" && $Vorteile == "Sandkontrolle") {
                $Punkte -= 999;
            }

            if ($Nachteil == "sKorper") {
                if ($Vorteile == "Staerke") {
                    $Punkte -= 999;
                }
                if ($Vorteile == "Verteidigung") {
                    $Punkte -= 999;
                }
                if ($Vorteile == "Geschwindigkeit") {
                    $Punkte -= 999;
                }
            } elseif ($Nachteil == "sChakrakontrolle" || $Nachteil == "nTaijutsu") {
                if ($Vorteile == "Chakrakontrolle") {
                    $Punkte -= 999;
                }
            } elseif ($Nachteil == "sAusdauer") {
                if ($Vorteile == "Ausdauernd") {
                    $Punkte -= 999;
                }
            } elseif ($Nachteil == "sChakra") {
                if ($Vorteile == "Chakra") {
                    $Punkte -= 999;
                }
            }

            if ($Punkte >= 0) {
                if ($Punkte > 0) {
                    $PunkteTPMax = $Punkte * 3;
                    $this->connection->executeStatement(
                        "UPDATE user SET PunkteTPMax = :points WHERE id = :id",
                        ['points' => $PunkteTPMax, 'id' => $current_character->id]
                    );
                }
                if ($Vorteile == "Sandkontrolle") {
                    $this->connection->executeStatement(
                        "UPDATE Besonderheiten SET Sand = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                    $this->connection->executeStatement(
                        "UPDATE user SET Element6 = 'Sand' WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                } elseif ($Vorteile != "Keiner") {
                    $this->connection->executeStatement(
                        "UPDATE Besonderheiten SET $Vorteile = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                }

                if ($Nachteil != "Keiner") {
                    $this->connection->executeStatement(
                        "UPDATE Besonderheiten SET $Nachteil = 1 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                }
                if ($Bluterbe == "Aburame Familie") {
                    $this->connection->executeStatement(
                        "UPDATE user SET Kaefer = 200 WHERE id = :id",
                        ['id' => $current_character->id]
                    );
                } elseif ($Bluterbe == "Hyuuga Clan") {
                    if (rand(1, 5) == 1) {
                        $this->connection->executeStatement(
                            "UPDATE Besonderheiten SET Main = 1 WHERE id = :id",
                            ['id' => $current_character->id]
                        );
                    }
                } elseif ($Bluterbe == "Uchiha Clan") {
                    $this->connection->executeStatement(
                        "UPDATE user SET startsha = :rand WHERE id = :id",
                        ['rand' => rand(1, 3), 'id' => $current_character->id]
                    );
                }

                $IQTP = [75 => 0, 100 => 0, 125 => 20, 150 => 30, 200 => 60];
                if ($Vorteile == "Genie") {
                    $IQ = 200;
                }

                $this->connection->executeStatement(
                    "UPDATE user SET Lern = :lern, tplf = :tplf, Stärke = 10, Verteidigung = 10, Geschwindigkeit = 10, Ausdauer = 50, Chakra = 50, name = :fullname WHERE id = :id",
                    [
                        'lern' => $IQ,
                        'tplf' => $IQTP[$IQ],
                        'fullname' => $fullName,
                        'id' => $current_character->id,
                    ]
                );

                $caViewModel = new \ChataccViewModel();
                $caViewModel->SetAcc($current_character->id, $fullName);
                $caViewModel->CreateOrDeleteChatacc($fullName);

                if ($current_character->Niveau > 1 && ($Vorteile == "Sandkontrolle" || $Bluterbe != '')) {
                    $current_character->Clan = $Bluterbe;
                    $current_character->Sand = ($Vorteile == "Sandkontrolle") ? 1 : 0;
                    $tps = new \tpKosten();
                    $clanTP = $tps->ClanTPGesamt($current_character, $current_character);
                    $PunkteTP = ($PunkteTPMax / 3) * ($current_character->maxNiveau - 1);
                    $this->connection->executeStatement(
                        "UPDATE user SET ClanTP = :clan, PunkteTP = :tp WHERE id = :id",
                        ['clan' => $clanTP, 'tp' => $PunkteTP, 'id' => $current_character->id]
                    );
                }
                $rHeimatdorf = htmlentities($request->request->getString('Heimatdorf'));

                $niveauUser = $current_character->Niveau;
                $niveauMin = ($niveauUser == 1) ? 1 : ($niveauUser - 1);
                $doerfer = ['Ame', 'Iwa', 'Konoha', 'Kumo', 'Kusa', 'Suna', 'Taki'];
                $doerfer = ($niveauUser == 1 || $niveauUser == 2 || $niveauUser == 3 || $niveauUser == 4) ? array_slice(
                    $doerfer,
                    1
                ) : $doerfer;
                $userDorf = [];
                foreach ($doerfer as $dorf) {
                    $anzahl = 0;
                    $sql = "SELECT id FROM user WHERE `Heimatdorf` = :dorf AND (`Niveau` = :niveauUser OR `Niveau` = :niveauMin) AND `Inaktivitaet` = '0'";
                    $query = $this->connection->executeQuery($sql, ['dorf' => $dorf, 'niveauUser' => $niveauUser, 'niveauMin' => $niveauMin]);
                    while ($row = $query->fetchAssociative()) {
                        $multiQ = "SELECT `uId2` FROM `multi` WHERE `multOk` = '2' AND `uId1` = :id AND `uId2` < `uId1`";
                        $multiQ = $this->connection->executeQuery($multiQ, ['id' => $row['id']]);
                        if (!$multii = $multiQ->fetchAssociative()) {
                            $multiQ = "SELECT `uId1` FROM `multi` WHERE `multOk` = '2' AND `uId2` = :id AND `uId1` < `uId2`";
                            $multiQ = $this->connection->executeQuery($multiQ, ['id' => $row['id']]);
                            if (!$multii = $multiQ->fetchAssociative()) {
                                $multiQ = "SELECT `mailBest`,`laston` FROM `userdaten` WHERE `id` = :id";
                                $multiQ = $this->connection->executeQuery($multiQ, ['id' => $row['id']]);
                                $multii = $multiQ->fetchAssociative();
                                if ($multii['mailBest'] == 1 && $multii['laston'] > time() - 60 * 60 * 24 * 60) {
                                    $anzahl++;
                                }
                            }
                        }
                    }
                    $userDorf[$dorf] = $anzahl;
                }

                // Ab hier alles eher unbearbeitet und muss noch clean gemacht werden!
                $c_loged = $current_character->id;

                asort($userDorf);
                $maxCurr = end($userDorf);
                $minCurr = reset($userDorf);
                $rangeCurr = $maxCurr - $minCurr;
                $borderSteps = floor($rangeCurr / 3);
                $border1 = $minCurr + $borderSteps;
                $border2 = $minCurr + $borderSteps * 2;
                $tp1 = 20;
                $tp2 = 10;
                $max = $minCurr * 4;
                $max = ($max < 8) ? 8 : $max;
                foreach ($userDorf as $dorf => $user) {
                    if ($user > $max) {
                        unset($userDorf[$dorf]);
                    } else {
                        $userDorf[$dorf] = ($user < $border1) ? $tp1 : (($user < $border2) ? $tp2 : 0);
                    }
                }
                if (array_key_exists($rHeimatdorf, $userDorf)) {
                    $dorfTP = $userDorf[$rHeimatdorf];
                    $aendern = "UPDATE user SET Heimatdorf = :dorf, `DorfTP` = :tp, `rangTp` = `rangTp` + :tp WHERE id = :id";
                    $this->connection->executeStatement($aendern, ['dorf' => $rHeimatdorf, 'tp' => $dorfTP, 'id' => $c_loged]);
                } else {
                    die("Fehler beim Erstellen des Ninjas! Heimatdorf nicht möglich");
                }
                $Lands = $rHeimatdorf;
                if ($Lands == "Konoha" or $Lands == "Suna" or $Lands == "Kusa" or $Lands == "Iwa") {
                    $Bundnishier = "|B1|";
                } elseif ($Lands == "Kumo" or $Lands == "Taki") {
                    $Bundnishier = "|B2|";
                } elseif ($Lands == "Ame") {
                    $Bundnishier = "|B3|";
                }
                $aendern = "UPDATE user SET Bundniszugang = '$Bundnishier', Angehoer = '$_POST[Heimatdorf]' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $aendern = "UPDATE `Item` SET `Item` = CONCAT('{$rHeimatdorf}','gakure-Ninjaweste') WHERE Von = '$c_loged' AND `Item` = '%gakure-Ninjaweste'";
                $this->connection->executeStatement($aendern);

                $aendern = "UPDATE user set Standort = '$_POST[Heimatdorf]" . "gakure' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $Alterkramninja = $request->request->getInt('Alterkramninja');
                if ($Alterkramninja < 6) {
                    $Alterkramninja = 6;
                }
                if ($Alterkramninja > 20) {
                    $Alterkramninja = 20;
                }
                $aendern = "UPDATE user set Alt = '$Alterkramninja' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);


                $Datees = time();
                $aendern = "UPDATE userdaten set Charerstellt = '$Datees' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                if ($Bluterbe == "") {
                    $Bluterbe = "Keiner";
                }
                $aendern = "UPDATE user set Clan = '$Bluterbe' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $Chakrafarbe = $request->request->getString('Chakrafarbe');
                if ($Chakrafarbe != "Blau" and $Chakrafarbe != "Schwarz" and $Chakrafarbe != "Weiß" and $Chakrafarbe != "Gelb"
                    and $Chakrafarbe != "Rot" and $Chakrafarbe != "Grün" and $Chakrafarbe != "Violett" and $Chakrafarbe != "Orange") {
                    $Chakrafarbe = "Blau";
                }
                $Chakrafarbe = htmlentities($Chakrafarbe);
                $aendern = "UPDATE user SET Farbe = '$Chakrafarbe' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                if ($current_character->Rang == '') {
                    $aendern = "UPDATE user set Rang = 'Akademist' WHERE id = '$c_loged'";
                    $this->connection->executeStatement($aendern);
                }
                $Geld = 20000;
                if ($current_character->Geld > 0) {
                    $Geld += $current_character->Geld;
                }
                $aendern = "UPDATE user set Geld = '$Geld' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $datums = date("d.m.Y");
                $aendern = "UPDATE user set lastup = '$datums' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $Geschlecht = $request->request->getString('Geschlecht');
                $Geschlecht = str_replace("&auml;", "ä", $Geschlecht);
                if ($Geschlecht != "Männlich" and $Geschlecht != "Weiblich") {
                    $Geschlecht = "Männlich";
                }
                $Geschlecht = str_replace("'", "\"", $Geschlecht);
                $aendern = "UPDATE user set Geschlecht = '$Geschlecht' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $aendern = "UPDATE user set feddig = '1' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);
                $rand = rand(1, 365);
                $aendern = "UPDATE user set Geburtstag = '$rand' WHERE id = '$c_loged'";
                $this->connection->executeStatement($aendern);

                $time = time();
                $Monate = floor((((($time - 1530480187) / (60 * 60 * 24)) + 4829) / 1.5) / 2);
                $Monate = round($Monate, 0);
                $TBT = $Monate;
                if ($current_character->Tageback > 0) {
                    $TBT = $current_character->TBT;
                    $Monate = $current_character->Bonustage;
                }
                $up = "UPDATE user SET Bonustage = '$Monate', TBT = $TBT WHERE id = '$c_loged'";
                $this->connection->executeStatement($up);
                $this->connection->commit();

                return $this->render('character_creation/success.html.twig');
            }

            $this->connection->rollBack();
            return new Response("Du hast zu viele Punkte verbraucht!");
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    private function isInvalidName(string $fullName): bool
    {
        // Name ist bereits in Benutzung?
        $result = $this->connection->executeQuery("SELECT id FROM user WHERE name LIKE :name LIMIT 1", ['name' => $fullName])->rowCount();
        if ($result > 0) {
            return true;
        }

        // Vorname ist auf Sperrliste?
        $Vorn = (int)$this->connection->executeQuery(
            "SELECT id FROM GespVor WHERE Name = :name LIMIT 1",
            ['name' => $fullName]
        )->fetchFirstColumn();
        if ($Vorn > 0) {
            return true;
        }

        // Nachname ist auf Sperrliste?
        $Nach = (int)$this->connection->executeQuery(
            "SELECT id FROM GespNach WHERE Name = :name LIMIT 1",
            ['name' => $fullName]
        )->fetchFirstColumn();
        if ($Nach > 0) {
            return true;
        }

        // Name ist leer?
        $fullName = htmlentities($fullName);
        if ($fullName == "" || $fullName == " ") {
            return true;
        }

        // Name enthält unerlaubte Zeichen?
        $Namewird = $fullName;
        $suchmuser = ['/[A-Z a-z]/', '/-/', '/ /'];
        $Namewird = preg_replace($suchmuser, "", $Namewird);
        if ($Namewird != "") {
            return true;
        }

        return false;
    }
}
