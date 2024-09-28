<?php

use function NarutoLegacy\Log\AdminLog;

include __DIR__ . '/../Menus/layout1.inc';

function get_ninja_name(int $id): string
{
    $query = mysql_query("SELECT * FROM user WHERE id = '$id'");
    $grins = mysql_fetch_object($query);
    return $grins->name;
}

$current_user = nrpg_get_current_user();
$dorfs2 = nrpg_get_current_character();

if ($current_user->admin >= 2 || $dorfs2->Spielleiter) {
    $c_loged = \NarutoRPG\SessionHelper::getUserId();
    $pdo = nrpg_get_database();
    $krname = $current_user->name;
    $eintragjetzt = filter_input(INPUT_GET, "eintragjetzt", FILTER_SANITIZE_NUMBER_INT);

    if ($eintragjetzt) {
        $PunkteAktivitaet = 0;
        $maxPuppen = $eintragjetzt;
        $maxPuppen *= 3;
        $gelddazu = $eintragjetzt;
        $gelddazu *= 500;
        while ($eintragjetzt != "0") {
            if ($eintragjetzt == "1") {
                $user = $ninja1;
            }
            if ($eintragjetzt == "2") {
                $user = $ninja2;
            }
            if ($eintragjetzt == "3") {
                $user = $ninja3;
            }
            if ($eintragjetzt == "4") {
                $user = $ninja4;
            }
            if ($eintragjetzt == "5") {
                $user = $ninja5;
            }
            if ($eintragjetzt == "6") {
                $user = $ninja6;
            }
            if ($eintragjetzt == "7") {
                $user = $ninja7;
            }
            if ($eintragjetzt == "8") {
                $user = $ninja8;
            }
            if ($eintragjetzt == "9") {
                $user = $ninja9;
            }
            if ($eintragjetzt == "10") {
                $user = $ninja10;
            }
            if ($eintragjetzt == "11") {
                $user = $ninja11;
            }
            if ($eintragjetzt == "12") {
                $user = $ninja12;
            }
            if ($eintragjetzt == "13") {
                $user = $ninja13;
            }
            if ($eintragjetzt == "14") {
                $user = $ninja14;
            }
            if ($eintragjetzt == "15") {
                $user = $ninja15;
            }
            if ($eintragjetzt == "16") {
                $user = $ninja16;
            }
            if ($eintragjetzt == "17") {
                $user = $ninja17;
            }
            if ($eintragjetzt == "18") {
                $user = $ninja18;
            }
            if ($eintragjetzt == "19") {
                $user = $ninja19;
            }
            if ($eintragjetzt == "20") {
                $user = $ninja20;
            }
            $ergebnis = mysql_query("SELECT * FROM user WHERE id = '$user'") or die("Invalid query");
            $dorfs = mysql_fetch_object($ergebnis);

            if ($dorfs->id > 0) {
                $PunkteAktivitaet += 1;
            }

            $sqlee = "SELECT * FROM Item WHERE Von = '$user' AND Angelegt != ''";
            $queryee = mysql_query($sqlee);
            while ($eer = mysql_fetch_array($queryee)) {
                $iddesitems = $eer['id'];
                $Itemzahl = $Item[$iddesitems];
                if ($Itemzahl != "") {
                    if ($eer['Menge'] != $Itemzahl) {
                        $Date = date("d.m.Y, H:i");
                        if ($Itemzahl <= 0) {
                            mysql_query("DELETE FROM Item WHERE id = '$eer[id]'");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$eer[Von]', '$eer[Item]x$eer[Menge] verloren (Kampf, $dorfs2->name)', '$Date')";
                        } else {
                            mysql_query("UPDATE Item SET Menge = '$Itemzahl' WHERE id = '$eer[id]'");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$eer[Von]', '$eer[Item] von $eer[Menge] auf $Itemzahl (Kampf, $dorfs2->name)', '$Date')";
                        }
                        mysql_query($ins);
                    }

                    $Ladungen = $ItemAusdauer[$iddesitems];
                    if ($eer['Ausdauerhalt'] != $Ladungen) {
                        $up = "UPDATE Item SET Ausdauerhalt = '$Ladungen' WHERE id = '$eer[id]'";
                        mysql_query($up);
                    }

                    $Ladungen = $ItemBeschrankung[$iddesitems];
                    if ($eer['Beschrankunghalt'] != $Ladungen) {
                        $up = "UPDATE Item SET Beschrankunghalt = '$Ladungen' WHERE id = '$eer[id]'";
                        mysql_query($up);
                    }

                    $Ladungen = $ItemBlutung[$iddesitems];
                    if ($eer['Bluthalt'] != $Ladungen) {
                        $up = "UPDATE Item SET Bluthalt = '$Ladungen' WHERE id = '$eer[id]'";
                        mysql_query($up);
                    }
                }
            }

            if ($dorfs->Clan == "Aburame Familie") {
                $itemzahl = match ($eintragjetzt) {
                    "1" => $Kaefer1,
                    "2" => $Kaefer2,
                    "3" => $Kaefer3,
                    "4" => $Kaefer4,
                    "5" => $Kaefer5,
                    "6" => $Kaefer6,
                    "7" => $Kaefer7,
                    "8" => $Kaefer8,
                    "9" => $Kaefer9,
                    "10" => $Kaefer10,
                    "11" => $Kaefer11,
                    "12" => $Kaefer12,
                    "13" => $Kaefer13,
                    "14" => $Kaefer14,
                    "15" => $Kaefer15,
                    "16" => $Kaefer16,
                    "17" => $Kaefer17,
                    "18" => $Kaefer18,
                    "19" => $Kaefer19,
                    "20" => $Kaefer20,
                    default => null,
                };

                if ($itemzahl != null) {
                    $aendern = "UPDATE user set Kaefer = '$itemzahl' WHERE id = '$user'";
                    mysql_query($aendern) or die("Fehler beim eintragen!8");
                }
            }

            if ($eintragjetzt == "1") {
                $zahl = $Kalorien1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Kalorien2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Kalorien3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Kalorien4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Kalorien5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Kalorien6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Kalorien7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Kalorien8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Kalorien9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Kalorien10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Kalorien11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Kalorien12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Kalorien13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Kalorien14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Kalorien15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Kalorien16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Kalorien17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Kalorien18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Kalorien19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Kalorien20;
            }
            mysql_query("UPDATE user set Kalorien = '$zahl' WHERE id = '$user'") or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Armlinks1;
            } elseif ($eintragjetzt == "2") {
                $zahl = $Armlinks2;
            } elseif ($eintragjetzt == "3") {
                $zahl = $Armlinks3;
            } elseif ($eintragjetzt == "4") {
                $zahl = $Armlinks4;
            } elseif ($eintragjetzt == "5") {
                $zahl = $Armlinks5;
            } elseif ($eintragjetzt == "6") {
                $zahl = $Armlinks6;
            } elseif ($eintragjetzt == "7") {
                $zahl = $Armlinks7;
            } elseif ($eintragjetzt == "8") {
                $zahl = $Armlinks8;
            } elseif ($eintragjetzt == "9") {
                $zahl = $Armlinks9;
            } elseif ($eintragjetzt == "10") {
                $zahl = $Armlinks10;
            } elseif ($eintragjetzt == "11") {
                $zahl = $Armlinks11;
            } elseif ($eintragjetzt == "12") {
                $zahl = $Armlinks12;
            } elseif ($eintragjetzt == "13") {
                $zahl = $Armlinks13;
            } elseif ($eintragjetzt == "14") {
                $zahl = $Armlinks14;
            } elseif ($eintragjetzt == "15") {
                $zahl = $Armlinks15;
            } elseif ($eintragjetzt == "16") {
                $zahl = $Armlinks16;
            } elseif ($eintragjetzt == "17") {
                $zahl = $Armlinks17;
            } elseif ($eintragjetzt == "18") {
                $zahl = $Armlinks18;
            } elseif ($eintragjetzt == "19") {
                $zahl = $Armlinks19;
            } elseif ($eintragjetzt == "20") {
                $zahl = $Armlinks20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Armlinks = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Armrechts1;
            } elseif ($eintragjetzt == "2") {
                $zahl = $Armrechts2;
            } elseif ($eintragjetzt == "3") {
                $zahl = $Armrechts3;
            } elseif ($eintragjetzt == "4") {
                $zahl = $Armrechts4;
            } elseif ($eintragjetzt == "5") {
                $zahl = $Armrechts5;
            } elseif ($eintragjetzt == "6") {
                $zahl = $Armrechts6;
            } elseif ($eintragjetzt == "7") {
                $zahl = $Armrechts7;
            } elseif ($eintragjetzt == "8") {
                $zahl = $Armrechts8;
            } elseif ($eintragjetzt == "9") {
                $zahl = $Armrechts9;
            } elseif ($eintragjetzt == "10") {
                $zahl = $Armrechts10;
            } elseif ($eintragjetzt == "11") {
                $zahl = $Armrechts11;
            } elseif ($eintragjetzt == "12") {
                $zahl = $Armrechts12;
            } elseif ($eintragjetzt == "13") {
                $zahl = $Armrechts13;
            } elseif ($eintragjetzt == "14") {
                $zahl = $Armrechts14;
            } elseif ($eintragjetzt == "15") {
                $zahl = $Armrechts15;
            } elseif ($eintragjetzt == "16") {
                $zahl = $Armrechts16;
            } elseif ($eintragjetzt == "17") {
                $zahl = $Armrechts17;
            } elseif ($eintragjetzt == "18") {
                $zahl = $Armrechts18;
            } elseif ($eintragjetzt == "19") {
                $zahl = $Armrechts19;
            } elseif ($eintragjetzt == "20") {
                $zahl = $Armrechts20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Armrechts = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Handrechts1;
            } elseif ($eintragjetzt == "2") {
                $zahl = $Handrechts2;
            } elseif ($eintragjetzt == "3") {
                $zahl = $Handrechts3;
            } elseif ($eintragjetzt == "4") {
                $zahl = $Handrechts4;
            } elseif ($eintragjetzt == "5") {
                $zahl = $Handrechts5;
            } elseif ($eintragjetzt == "6") {
                $zahl = $Handrechts6;
            } elseif ($eintragjetzt == "7") {
                $zahl = $Handrechts7;
            } elseif ($eintragjetzt == "8") {
                $zahl = $Handrechts8;
            } elseif ($eintragjetzt == "9") {
                $zahl = $Handrechts9;
            } elseif ($eintragjetzt == "10") {
                $zahl = $Handrechts10;
            } elseif ($eintragjetzt == "11") {
                $zahl = $Handrechts11;
            } elseif ($eintragjetzt == "12") {
                $zahl = $Handrechts12;
            } elseif ($eintragjetzt == "13") {
                $zahl = $Handrechts13;
            } elseif ($eintragjetzt == "14") {
                $zahl = $Handrechts14;
            } elseif ($eintragjetzt == "15") {
                $zahl = $Handrechts15;
            } elseif ($eintragjetzt == "16") {
                $zahl = $Handrechts16;
            } elseif ($eintragjetzt == "17") {
                $zahl = $Handrechts17;
            } elseif ($eintragjetzt == "18") {
                $zahl = $Handrechts18;
            } elseif ($eintragjetzt == "19") {
                $zahl = $Handrechts19;
            } elseif ($eintragjetzt == "20") {
                $zahl = $Handrechts20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Handrechts = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Handlinks1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Handlinks2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Handlinks3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Handlinks4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Handlinks5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Handlinks6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Handlinks7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Handlinks8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Handlinks9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Handlinks10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Handlinks11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Handlinks12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Handlinks13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Handlinks14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Handlinks15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Handlinks16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Handlinks17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Handlinks18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Handlinks19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Handlinks20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Handlinks = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Beinrechts1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Beinrechts2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Beinrechts3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Beinrechts4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Beinrechts5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Beinrechts6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Beinrechts7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Beinrechts8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Beinrechts9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Beinrechts10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Beinrechts11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Beinrechts12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Beinrechts13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Beinrechts14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Beinrechts15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Beinrechts16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Beinrechts17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Beinrechts18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Beinrechts19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Beinrechts20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Beinrechts = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Beinlinks1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Beinlinks2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Beinlinks3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Beinlinks4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Beinlinks5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Beinlinks6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Beinlinks7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Beinlinks8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Beinlinks9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Beinlinks10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Beinlinks11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Beinlinks12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Beinlinks13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Beinlinks14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Beinlinks15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Beinlinks16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Beinlinks17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Beinlinks18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Beinlinks19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Beinlinks20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Beinlinks = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Kopf1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Kopf2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Kopf3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Kopf4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Kopf5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Kopf6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Kopf7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Kopf8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Kopf9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Kopf10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Kopf11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Kopf12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Kopf13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Kopf14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Kopf15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Kopf16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Kopf17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Kopf18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Kopf19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Kopf20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Kopf = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Hals1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Hals2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Hals3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Hals4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Hals5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Hals6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Hals7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Hals8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Hals9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Hals10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Hals11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Hals12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Hals13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Hals14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Hals15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Hals16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Hals17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Hals18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Hals19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Hals20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Hals = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Brust1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Brust2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Brust3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Brust4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Brust5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Brust6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Brust7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Brust8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Brust9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Brust10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Brust11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Brust12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Brust13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Brust14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Brust15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Brust16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Brust17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Brust18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Brust19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Brust20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Brust = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            if ($eintragjetzt == "1") {
                $zahl = $Rucken1;
            }
            if ($eintragjetzt == "2") {
                $zahl = $Rucken2;
            }
            if ($eintragjetzt == "3") {
                $zahl = $Rucken3;
            }
            if ($eintragjetzt == "4") {
                $zahl = $Rucken4;
            }
            if ($eintragjetzt == "5") {
                $zahl = $Rucken5;
            }
            if ($eintragjetzt == "6") {
                $zahl = $Rucken6;
            }
            if ($eintragjetzt == "7") {
                $zahl = $Rucken7;
            }
            if ($eintragjetzt == "8") {
                $zahl = $Rucken8;
            }
            if ($eintragjetzt == "9") {
                $zahl = $Rucken9;
            }
            if ($eintragjetzt == "10") {
                $zahl = $Rucken10;
            }
            if ($eintragjetzt == "11") {
                $zahl = $Rucken11;
            }
            if ($eintragjetzt == "12") {
                $zahl = $Rucken12;
            }
            if ($eintragjetzt == "13") {
                $zahl = $Rucken13;
            }
            if ($eintragjetzt == "14") {
                $zahl = $Rucken14;
            }
            if ($eintragjetzt == "15") {
                $zahl = $Rucken15;
            }
            if ($eintragjetzt == "16") {
                $zahl = $Rucken16;
            }
            if ($eintragjetzt == "17") {
                $zahl = $Rucken17;
            }
            if ($eintragjetzt == "18") {
                $zahl = $Rucken18;
            }
            if ($eintragjetzt == "19") {
                $zahl = $Rucken19;
            }
            if ($eintragjetzt == "20") {
                $zahl = $Rucken20;
            }
            $zahl *= 100;
            if ($zahl > 0) {
                $verlpn = 1;
            }
            $aendern = "UPDATE Verletzungen set Rucken = '$zahl' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");

            # Da
            $Test = "ArmlinksBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set ArmlinksBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "ArmrechtsBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set ArmrechtsBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "HandrechtsBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set HandrechtsBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "HandlinksBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set HandlinksBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "BeinrechtsBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set BeinrechtsBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "BeinlinksBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set BeinlinksBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "KopfBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set KopfBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "HalsBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set HalsBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "BauchBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set BauchBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            $Test = "RuckenBP$eintragjetzt";
            $Teste = $$Test;
            $Teste *= 100;
            $aendern = "UPDATE Verletzungen set RuckenBP = '$Teste' WHERE id = '$user'";
            mysql_query($aendern) or die("Fehler beim eintragen!9");
            if ($Teste > 0) {
                $verlpn = 1;
            }

            if ($verlpn == 1) {
                (new PrivateMessage())
                    ->to($user)
                    ->subject('Verletzungen')
                    ->body($dorfs2->name . ' hat dir bei einem Kampf Verletzungen eingetragen.')
                    ->send();
            }
            # Da
            if ($eintragjetzt == "1") {
                $winna = $sieger1;
            }
            if ($eintragjetzt == "2") {
                $winna = $sieger2;
            }
            if ($eintragjetzt == "3") {
                $winna = $sieger3;
            }
            if ($eintragjetzt == "4") {
                $winna = $sieger4;
            }
            if ($eintragjetzt == "5") {
                $winna = $sieger5;
            }
            if ($eintragjetzt == "6") {
                $winna = $sieger6;
            }
            if ($eintragjetzt == "7") {
                $winna = $sieger7;
            }
            if ($eintragjetzt == "8") {
                $winna = $sieger8;
            }
            if ($eintragjetzt == "9") {
                $winna = $sieger9;
            }
            if ($eintragjetzt == "10") {
                $winna = $sieger10;
            }
            if ($eintragjetzt == "11") {
                $winna = $sieger11;
            }
            if ($eintragjetzt == "12") {
                $winna = $sieger12;
            }
            if ($eintragjetzt == "13") {
                $winna = $sieger13;
            }
            if ($eintragjetzt == "14") {
                $winna = $sieger14;
            }
            if ($eintragjetzt == "15") {
                $winna = $sieger15;
            }
            if ($eintragjetzt == "16") {
                $winna = $sieger16;
            }
            if ($eintragjetzt == "17") {
                $winna = $sieger17;
            }
            if ($eintragjetzt == "18") {
                $winna = $sieger18;
            }
            if ($eintragjetzt == "19") {
                $winna = $sieger19;
            }
            if ($eintragjetzt == "20") {
                $winna = $sieger20;
            }
            if ($winna == "11") {
                $winna = 1;
            } elseif ($winna == "22") {
                $winna = 2;
            } elseif ($winna == "33") {
                $winna = 3;
            }
            $query = mysql_query("SELECT * FROM user WHERE id = '$user'") or die("Fehler");
            $Userobject = mysql_fetch_object($query);
            $Sieges = $Userobject->Siege;
            $Niederlagens = $Userobject->Niederlagen;
            $Unendschiedens = $Userobject->Unendschieden;
            $Kampfes = $Userobject->Kaempfe;
            if ($winna == 1) {
                if ($Sieges == "") {
                    $Sieges = 0;
                }
                $Sieges += 1;
                $Kampfes += 1;
                $aendern = "UPDATE user SET Siege = '$Sieges' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!11");
                $aendern = "UPDATE user SET Kaempfe = '$Kampfes' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!12");
            } elseif ($winna == 2) {
                if ($Niederlagens == "") {
                    $Niederlagens = "0";
                }
                $Niederlagens += 1;
                $Kampfes += 1;
                $aendern = "UPDATE user set Niederlagen = '$Niederlagens' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!13");
                $aendern = "UPDATE user set Kaempfe = '$Kampfes' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!14");
            } elseif ($winna == 3) {
                if ($Unendschiedens == "") {
                    $Unendschiedens = "0";
                }
                $Unendschiedens += 1;
                $Kampfes += 1;
                $aendern = "UPDATE user set Unendschieden = '$Unendschiedens' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!15");
                $aendern = "UPDATE user set Kaempfe = '$Kampfes' WHERE id = '$user'";
                mysql_query($aendern) or die("Fehler beim eintragen!16");
            }
            $eintragjetzt -= 1;
        }

        $query = mysql_query("SELECT id FROM Inuzuka WHERE Besitzer = '$user'");
        while ($Tier = mysql_fetch_object($query)) {
            $Wert = "TierAus$Tier->id";
            $Ausdauer = $$Wert;
            mysql_query(
                "UPDATE Inuzuka SET Ausdauer = '$Ausdauer' WHERE id = '$Tier->id'"
            ) or die("Fehler bei der Ausdauer des Tieres");
            $Wert = "TierCha$Tier->id";
            $Ausdauer = $$Wert;
            mysql_query(
                "UPDATE Inuzuka SET Chakra = '$Ausdauer' WHERE id = '$Tier->id'"
            ) or die("Fehler bei der Ausdauer des Tieres");
        }

        if ($maxPuppen < 0) {
            $maxPuppen = 0;
        }
        while ($maxPuppen != 0) {
            if ($maxPuppen == "1") {
                $Puppe = $Puppe1;
                $PuppeHaltbar = $PuppeHaltbar1;
            } elseif ($maxPuppen == "2") {
                $Puppe = $Puppe2;
                $PuppeHaltbar = $PuppeHaltbar2;
            } elseif ($maxPuppen == "3") {
                $Puppe = $Puppe3;
                $PuppeHaltbar = $PuppeHaltbar3;
            } elseif ($maxPuppen == "4") {
                $Puppe = $Puppe4;
                $PuppeHaltbar = $PuppeHaltbar4;
            } elseif ($maxPuppen == "5") {
                $Puppe = $Puppe5;
                $PuppeHaltbar = $PuppeHaltbar5;
            } elseif ($maxPuppen == "6") {
                $Puppe = $Puppe6;
                $PuppeHaltbar = $PuppeHaltbar6;
            } elseif ($maxPuppen == "7") {
                $Puppe = $Puppe7;
                $PuppeHaltbar = $PuppeHaltbar7;
            } elseif ($maxPuppen == "8") {
                $Puppe = $Puppe8;
                $PuppeHaltbar = $PuppeHaltbar8;
            } elseif ($maxPuppen == "9") {
                $Puppe = $Puppe9;
                $PuppeHaltbar = $PuppeHaltbar9;
            } elseif ($maxPuppen == "10") {
                $Puppe = $Puppe10;
                $PuppeHaltbar = $PuppeHaltbar10;
            } elseif ($maxPuppen == "11") {
                $Puppe = $Puppe11;
                $PuppeHaltbar = $PuppeHaltbar11;
            } elseif ($maxPuppen == "12") {
                $Puppe = $Puppe12;
                $PuppeHaltbar = $PuppeHaltbar12;
            } elseif ($maxPuppen == "13") {
                $Puppe = $Puppe13;
                $PuppeHaltbar = $PuppeHaltbar13;
            } elseif ($maxPuppen == "14") {
                $Puppe = $Puppe14;
                $PuppeHaltbar = $PuppeHaltbar14;
            } elseif ($maxPuppen == "15") {
                $Puppe = $Puppe15;
                $PuppeHaltbar = $PuppeHaltbar15;
            } elseif ($maxPuppen == "16") {
                $Puppe = $Puppe16;
                $PuppeHaltbar = $PuppeHaltbar16;
            } elseif ($maxPuppen == "17") {
                $Puppe = $Puppe17;
                $PuppeHaltbar = $PuppeHaltbar17;
            } elseif ($maxPuppen == "18") {
                $Puppe = $Puppe18;
                $PuppeHaltbar = $PuppeHaltbar18;
            } elseif ($maxPuppen == "19") {
                $Puppe = $Puppe19;
                $PuppeHaltbar = $PuppeHaltbar19;
            } elseif ($maxPuppen == "20") {
                $Puppe = $Puppe20;
                $PuppeHaltbar = $PuppeHaltbar20;
            } elseif ($maxPuppen == "21") {
                $Puppe = $Puppe1;
                $PuppeHaltbar = $PuppeHaltbar21;
            } elseif ($maxPuppen == "22") {
                $Puppe = $Puppe2;
                $PuppeHaltbar = $PuppeHaltbar22;
            } elseif ($maxPuppen == "23") {
                $Puppe = $Puppe3;
                $PuppeHaltbar = $PuppeHaltbar23;
            } elseif ($maxPuppen == "24") {
                $Puppe = $Puppe4;
                $PuppeHaltbar = $PuppeHaltbar24;
            } elseif ($maxPuppen == "25") {
                $Puppe = $Puppe5;
                $PuppeHaltbar = $PuppeHaltbar25;
            } elseif ($maxPuppen == "26") {
                $Puppe = $Puppe6;
                $PuppeHaltbar = $PuppeHaltbar26;
            } elseif ($maxPuppen == "27") {
                $Puppe = $Puppe7;
                $PuppeHaltbar = $PuppeHaltbar27;
            } elseif ($maxPuppen == "28") {
                $Puppe = $Puppe8;
                $PuppeHaltbar = $PuppeHaltbar28;
            } elseif ($maxPuppen == "29") {
                $Puppe = $Puppe9;
                $PuppeHaltbar = $PuppeHaltbar29;
            } elseif ($maxPuppen == "30") {
                $Puppe = $Puppe10;
                $PuppeHaltbar = $PuppeHaltbar30;
            } elseif ($maxPuppen == "31") {
                $Puppe = $Puppe1;
                $PuppeHaltbar = $PuppeHaltbar31;
            } elseif ($maxPuppen == "32") {
                $Puppe = $Puppe2;
                $PuppeHaltbar = $PuppeHaltbar32;
            } elseif ($maxPuppen == "33") {
                $Puppe = $Puppe3;
                $PuppeHaltbar = $PuppeHaltbar33;
            } elseif ($maxPuppen == "34") {
                $Puppe = $Puppe4;
                $PuppeHaltbar = $PuppeHaltbar34;
            } elseif ($maxPuppen == "35") {
                $Puppe = $Puppe5;
                $PuppeHaltbar = $PuppeHaltbar35;
            } elseif ($maxPuppen == "36") {
                $Puppe = $Puppe6;
                $PuppeHaltbar = $PuppeHaltbar36;
            } elseif ($maxPuppen == "37") {
                $Puppe = $Puppe7;
                $PuppeHaltbar = $PuppeHaltbar37;
            } elseif ($maxPuppen == "38") {
                $Puppe = $Puppe8;
                $PuppeHaltbar = $PuppeHaltbar38;
            } elseif ($maxPuppen == "39") {
                $Puppe = $Puppe9;
                $PuppeHaltbar = $PuppeHaltbar39;
            } elseif ($maxPuppen == "40") {
                $Puppe = $Puppe10;
                $PuppeHaltbar = $PuppeHaltbar40;
            } elseif ($maxPuppen == "41") {
                $Puppe = $Puppe1;
                $PuppeHaltbar = $PuppeHaltbar41;
            } elseif ($maxPuppen == "42") {
                $Puppe = $Puppe2;
                $PuppeHaltbar = $PuppeHaltbar42;
            } elseif ($maxPuppen == "43") {
                $Puppe = $Puppe3;
                $PuppeHaltbar = $PuppeHaltbar43;
            } elseif ($maxPuppen == "44") {
                $Puppe = $Puppe4;
                $PuppeHaltbar = $PuppeHaltbar44;
            } elseif ($maxPuppen == "45") {
                $Puppe = $Puppe5;
                $PuppeHaltbar = $PuppeHaltbar45;
            } elseif ($maxPuppen == "46") {
                $Puppe = $Puppe6;
                $PuppeHaltbar = $PuppeHaltbar46;
            } elseif ($maxPuppen == "47") {
                $Puppe = $Puppe7;
                $PuppeHaltbar = $PuppeHaltbar47;
            } elseif ($maxPuppen == "48") {
                $Puppe = $Puppe8;
                $PuppeHaltbar = $PuppeHaltbar48;
            } elseif ($maxPuppen == "49") {
                $Puppe = $Puppe9;
                $PuppeHaltbar = $PuppeHaltbar49;
            } elseif ($maxPuppen == "50") {
                $Puppe = $Puppe10;
                $PuppeHaltbar = $PuppeHaltbar50;
            } elseif ($maxPuppen == "51") {
                $Puppe = $Puppe1;
                $PuppeHaltbar = $PuppeHaltbar51;
            } elseif ($maxPuppen == "52") {
                $Puppe = $Puppe2;
                $PuppeHaltbar = $PuppeHaltbar52;
            } elseif ($maxPuppen == "53") {
                $Puppe = $Puppe3;
                $PuppeHaltbar = $PuppeHaltbar53;
            } elseif ($maxPuppen == "54") {
                $Puppe = $Puppe4;
                $PuppeHaltbar = $PuppeHaltbar54;
            } elseif ($maxPuppen == "55") {
                $Puppe = $Puppe5;
                $PuppeHaltbar = $PuppeHaltbar55;
            } elseif ($maxPuppen == "56") {
                $Puppe = $Puppe6;
                $PuppeHaltbar = $PuppeHaltbar56;
            } elseif ($maxPuppen == "57") {
                $Puppe = $Puppe7;
                $PuppeHaltbar = $PuppeHaltbar57;
            } elseif ($maxPuppen == "58") {
                $Puppe = $Puppe8;
                $PuppeHaltbar = $PuppeHaltbar58;
            } elseif ($maxPuppen == "59") {
                $Puppe = $Puppe9;
                $PuppeHaltbar = $PuppeHaltbar59;
            } elseif ($maxPuppen == "60") {
                $Puppe = $Puppe10;
                $PuppeHaltbar = $PuppeHaltbar60;
            }
            if ($Puppe > 0) {
                $up = "UPDATE Marionetten SET Haltbarkeit = '$PuppeHaltbar' WHERE id = '$Puppe'";
                mysql_query($up) or die("Fehler bei der Ausdauer des Tieres");
            }
            $maxPuppen -= 1;
        }

        $ninjas1 = get_ninja_name($ninja1);
        $ninjas2 = get_ninja_name($ninja2);
        $ninjas3 = get_ninja_name($ninja3);
        $ninjas4 = get_ninja_name($ninja4);
        $ninjas5 = get_ninja_name($ninja5);
        $ninjas6 = get_ninja_name($ninja6);
        $ninjas7 = get_ninja_name($ninja7);
        $ninjas8 = get_ninja_name($ninja8);
        $ninjas9 = get_ninja_name($ninja9);
        $ninjas10 = get_ninja_name($ninja10);
        $ninjas11 = get_ninja_name($ninja11);
        $ninjas12 = get_ninja_name($ninja12);
        $ninjas13 = get_ninja_name($ninja13);
        $ninjas14 = get_ninja_name($ninja14);
        $ninjas15 = get_ninja_name($ninja15);
        $ninjas16 = get_ninja_name($ninja16);
        $ninjas17 = get_ninja_name($ninja17);
        $ninjas18 = get_ninja_name($ninja18);
        $ninjas19 = get_ninja_name($ninja19);
        $ninjas20 = get_ninja_name($ninja20);

        $stmt = $pdo->prepare(
            "UPDATE user SET Geld = Geld + :money, Aktivitätspunkte = Aktivitätspunkte + :points, APGesamt = APGesamt + 1 WHERE id = :id"
        );
        $stmt->bindParam(":money", $gelddazu, PDO::PARAM_INT);
        $stmt->bindParam(":points", $PunkteAktivitaet, PDO::PARAM_INT);
        $stmt->bindParam(":id", $c_loged, PDO::PARAM_INT);
        $stmt->execute();

        AdminLog(
            'User',
            "$dorfs2->name trägt Kampf ein.",
            $dorfs->id
        );

        $pdo->query("UPDATE allgdata SET Kampfe = CAST(Kampfe AS UNSIGNED) + 1 WHERE id = 1");

        $date = date("d.m.Y");
        $log = "Kampf von $krname am $date eingetragen!<br>
        $ninjas1 $ninjas2 $ninjas3 $ninjas4 $ninjas5 $ninjas6 $ninjas7 $ninjas8 $ninjas9<br>
        $ninjas10 $ninjas11 $ninjas12 $ninjas13 $ninjas14 $ninjas15 $ninjas15 $ninjas16 $ninjas17 $ninjas18 $ninjas19 $ninjas20";
        \NarutoLegacy\Log\Log($log);

        AdminLog(
            'Kampf',
            "Grund des Kampfes: $GrundKampf<br>Kämpfer: $ninjas1 $ninjas2 $ninjas3 $ninjas4 $ninjas5 $ninjas6 $ninjas7 $ninjas8 $ninjas9 $ninjas10 $ninjas11 $ninjas12 $ninjas13 $ninjas14 $ninjas15 $ninjas15 $ninjas16 $ninjas17 $ninjas18 $ninjas19 $ninjas20",
            $c_loged
        );

        echo "Kampf erfolgreich eingetragen!";
    }

    if ($zahlen) {
        $error = 0;
        $zahls = $zahlen;
        while ($zahls != "0") {
            $Ninjaids = "ninja$zahls";
            $Ninjaid = $$Ninjaids;
            $query = mysql_query("SELECT id FROM user WHERE name = '$Ninjaid'") or die("Fehler");
            $Userobject = mysql_fetch_object($query);
            if (is_object($Userobject) && $Userobject->id > 0) {
                $judge_password = $pdo->query(
                    "SELECT KRPW FROM userdaten WHERE id = '$Userobject->id'"
                )->fetchColumn();
                $PWkram = "ninkrpw$zahls";
                $PWkram = $$PWkram;
                if ($PWkram != $judge_password and $dorfs->admin < 3) {
                    $error = 1;
                }

                if ($zahls == 1) {
                    $ninja1 = $Userobject->id;
                } elseif ($zahls == 2) {
                    $ninja2 = $Userobject->id;
                } elseif ($zahls == 3) {
                    $ninja3 = $Userobject->id;
                } elseif ($zahls == 4) {
                    $ninja4 = $Userobject->id;
                } elseif ($zahls == 5) {
                    $ninja5 = $Userobject->id;
                } elseif ($zahls == 6) {
                    $ninja6 = $Userobject->id;
                } elseif ($zahls == 7) {
                    $ninja7 = $Userobject->id;
                } elseif ($zahls == 8) {
                    $ninja8 = $Userobject->id;
                } elseif ($zahls == 9) {
                    $ninja9 = $Userobject->id;
                } elseif ($zahls == 10) {
                    $ninja10 = $Userobject->id;
                } elseif ($zahls == 11) {
                    $ninja11 = $Userobject->id;
                } elseif ($zahls == 12) {
                    $ninja12 = $Userobject->id;
                } elseif ($zahls == 13) {
                    $ninja13 = $Userobject->id;
                } elseif ($zahls == 14) {
                    $ninja14 = $Userobject->id;
                } elseif ($zahls == 15) {
                    $ninja15 = $Userobject->id;
                } elseif ($zahls == 16) {
                    $ninja16 = $Userobject->id;
                } elseif ($zahls == 17) {
                    $ninja17 = $Userobject->id;
                } elseif ($zahls == 18) {
                    $ninja18 = $Userobject->id;
                } elseif ($zahls == 19) {
                    $ninja19 = $Userobject->id;
                } elseif ($zahls == 20) {
                    $ninja20 = $Userobject->id;
                }
            } else {
                $error = 1;
            }
            $zahls -= 1;
        }

        if ($error == 1) {
            echo "Einer der angegebenen User ist nicht vorhanden oder ein Kampfrichterpasswort stimmt nicht!<br><br>";
        } else {
            echo sprintf("<form method='POST' action='Kampfeintrag.php?eintragjetzt=%s'>", $zahlen);
            echo "<b>Grund des Kampfes:</b> <input type='text' name='GrundKampf'><br>
            <b>Wichtig: Bei Items, die abgezogen werden, <b><u>0</u></b> in das Fenster für die Menge eintragen und das Feld <b><u>nicht einfach leer lassen</u></b><br>";
            while ($zahlen != "0") {
                if ($zahlen == "1") {
                    $user = $ninja1;
                }
                if ($zahlen == "2") {
                    $user = $ninja2;
                }
                if ($zahlen == "3") {
                    $user = $ninja3;
                }
                if ($zahlen == "4") {
                    $user = $ninja4;
                }
                if ($zahlen == "5") {
                    $user = $ninja5;
                }
                if ($zahlen == "6") {
                    $user = $ninja6;
                }
                if ($zahlen == "7") {
                    $user = $ninja7;
                }
                if ($zahlen == "8") {
                    $user = $ninja8;
                }
                if ($zahlen == "9") {
                    $user = $ninja9;
                }
                if ($zahlen == "10") {
                    $user = $ninja10;
                }
                if ($zahlen == "11") {
                    $user = $ninja11;
                }
                if ($zahlen == "12") {
                    $user = $ninja12;
                }
                if ($zahlen == "13") {
                    $user = $ninja13;
                }
                if ($zahlen == "14") {
                    $user = $ninja14;
                }
                if ($zahlen == "15") {
                    $user = $ninja15;
                }
                if ($zahlen == "16") {
                    $user = $ninja16;
                }
                if ($zahlen == "17") {
                    $user = $ninja17;
                }
                if ($zahlen == "18") {
                    $user = $ninja18;
                }
                if ($zahlen == "19") {
                    $user = $ninja19;
                }
                if ($zahlen == "20") {
                    $user = $ninja20;
                }
                $sql = "SELECT * FROM user WHERE id = '$user'";
                $query = mysql_query($sql) or die("Fehler");
                $Userobject = mysql_fetch_object($query);
                echo "<table border='0' width='90%'>";
                echo "<tr>";
                echo "<td colspan='2'>Ninja</td>";
                echo "<td colspan='4'><b>$Userobject->name</b></td>";
                echo "<td colspan='2'><input type='hidden' name='ninja$zahlen' value='$user'></td>";
                echo "</tr>";

                if ($Userobject->Clan == "Akimichi Clan") {
                    echo "<tr>";
                    echo "<td colspan='2'><font color='#FFFF99'><b>Kalorien</b></font>:</td>";
                    echo "<td colspan='2'><input type='text' name='Kalorien$zahlen' value='$Userobject->Kalorien'></td>";
                    echo "<td colspan='2'></td>";
                    echo "<td colspan='2'></td>";
                    echo "</tr>";
                }

                if ($Userobject->Clan == "Aburame Familie") {
                    echo "<tr>";
                    echo "<td colspan='2'><font color='#333333'><b>Käfer</b></font>:</td>";
                    echo "<td colspan='2'><input type='text' name='Kaefer$zahlen' value='$Userobject->Kaefer'></td>";
                    echo "<td colspan='2'></td>";
                    echo "<td colspan='2'></td>";
                    echo "</tr>";
                }

                echo "<tr>";
                echo "<td colspan='2'><b>Verbrauchte</b></td>";
                echo "<td colspan='2'><b>Items</b></td>";
                echo "<td colspan='2'></td>";
                echo "<td colspan='2'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='8'>";
                echo "<table border='0' width='100%'>";

                $Angelegtvorher = "";
                $Dudu = 0;
                $sqls = "SELECT * FROM Item WHERE Von = '$Userobject->id' AND Angelegt != '' ORDER BY Angelegt";
                $querys = mysql_query($sqls);
                while ($Itemr = mysql_fetch_object($querys)) {
                    if ($Angelegtvorher == "" and $Itemr->Angelegt == "") {
                        echo "<tr><td colspan='2'><b>Items zu Hause</b></td></tr>";
                        $Angelegtvorher = "LOL";
                    } elseif ($Angelegtvorher == "" and $Itemr->Angelegt != "") {
                        echo "<tr><td colspan='2'><b>Angelegte Items</b></td></tr>";
                        $Angelegtvorher = "ROFL";
                    } elseif ($Angelegtvorher == "LOL" and $Itemr->Angelegt != "") {
                        $Angelegtvorher = "ROFL";
                        if ($Dudu == 1) {
                            echo "</tr>";
                            $Dudu = 0;
                        }
                        echo "<tr><td colspan='2'><br><b>Angelegte Items</b></td></tr>";
                    }
                    $okay = 0;
                    $Angelegt = "<><>$Itemr->Angelegt";
                    if (!str_contains($Angelegt, "<><>Item:")) {
                        $okay = 1;
                    }
                    if (str_contains($Angelegt, "<><>Inu") || str_contains($Angelegt, "<><>Puppe:")) {
                        $okay = 0;
                    }

                    if ($okay == 1) {
                        if ($Dudu == 0) {
                            echo "<tr>";
                        }
                        echo "<td valign='top' width='50%'>";
                        echo "<table border='0' width='100%'><tr>";

                        $queryt = mysql_query("SELECT id FROM Item WHERE Angelegt = 'Item: $Itemr->id'");
                        $Drin = mysql_fetch_object($queryt);
                        if ($Drin->id > 0 or $Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemr->id');\">$Itemr->Item</a></td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                        } else {
                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> $Itemr->Item</td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                        }
                        echo "</tr></table>";

                        echo "<div id='Item$Itemr->id' style='display:none'>";

                        if ($Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                            echo "<table border='0' width='75%'>";
                            $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemr->Item'";
                            $BLAS = mysql_query($BLA);
                            $Itemk = mysql_fetch_object($BLAS);

                            echo "<tr>";
                            echo "<td width='33%'><b>Ausdauer</b></td>";
                            echo "<td width='33%'><b>EP</b></td>";
                            echo "<td width='33%'><b>BP</b></td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemr->id]' size='2' value='$Itemr->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                            echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemr->id]' size='2' value='$Itemr->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                            echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemr->id]' size='2' value='$Itemr->Bluthalt'>/$Itemk->RustBlutung</td>";
                            echo "</tr>";

                            echo "</table>";
                        }

                        $sqlte = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemr->id'";
                        $queryte = mysql_query($sqlte);
                        while ($Itemte = mysql_fetch_object($queryte)) {
                            $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                            $queryteRRR = mysql_query($sqltERE);
                            $Drin = mysql_fetch_object($queryteRRR);
                            echo "<table border='0' width='100%'><tr>";
                            if ($Drin->id > 0 or $Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte->id');\">$Itemte->Item </a>
                                </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                            } else {
                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte->Item
                                </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                            }
                            echo "</table>";

                            echo "<div id='Item$Itemte->id' style='display:none'>";
                            if ($Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                echo "<table border='0' width='75%'>";
                                $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte->Item'";
                                $BLAS = mysql_query($BLA);
                                $Itemk = mysql_fetch_object($BLAS);

                                echo "<tr>";
                                echo "<td width='33%'><b>Ausdauer</b></td>";
                                echo "<td width='33%'><b>EP</b></td>";
                                echo "<td width='33%'><b>BP</b></td>";
                                echo "</tr>";

                                echo "<tr>";
                                echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte->id]' size='2' value='$Itemte->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte->id]' size='2' value='$Itemte->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte->id]' size='2' value='$Itemte->Bluthalt'>/$Itemk->RustBlutung</td>";
                                echo "</tr>";

                                echo "</table>";
                            }

                            $sqlte1 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                            $queryte1 = mysql_query($sqlte1);
                            while ($Itemte1 = mysql_fetch_object($queryte1)) {
                                $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                $queryteRRR = mysql_query($sqltERE);
                                $Drin = mysql_fetch_object($queryteRRR);
                                echo "<table border='0' width='100%'>
                                <tr>";
                                if ($Drin->id > 0 or $Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte1->id');\">$Itemte1->Item</a>
                                    </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                } else {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1->Item
                                    </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                }
                                echo "</table>";
                                echo "<div id='Item$Itemte1->id' style='display:none'>";
                                if ($Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                    echo "<table border='0' width='75%'>";
                                    $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte1->Item'";
                                    $BLAS = mysql_query($BLA);
                                    $Itemk = mysql_fetch_object($BLAS);

                                    echo "<tr>";
                                    echo "<td width='33%'><b>Ausdauer</b></td>";
                                    echo "<td width='33%'><b>EP</b></td>";
                                    echo "<td width='33%'><b>BP</b></td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte1->id]' size='2' value='$Itemte1->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte1->id]' size='2' value='$Itemte1->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte1->id]' size='2' value='$Itemte1->Bluthalt'>/$Itemk->RustBlutung</td>";
                                    echo "</tr>";

                                    echo "</table>";
                                }
                                $sqlte12 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                $queryte12 = mysql_query($sqlte12);
                                while ($Itemte12 = mysql_fetch_object($queryte12)) {
                                    $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                    $queryteRRR = mysql_query($sqltERE);
                                    $Drin = mysql_fetch_object($queryteRRR);
                                    echo "<table border='0' width='100%'>
                                    <tr>";
                                    if ($Drin->id > 0 or $Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte12->id');\">$Itemte12->Item</a>
                                        </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                    } else {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte12->Item
                                        </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                    }
                                    echo "</table>";
                                    echo "<div id='Item$Itemte12->id' style='display:none'>";
                                    if ($Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                        echo "<table border='0' width='75%'>";
                                        $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte12->Item'";
                                        $BLAS = mysql_query($BLA);
                                        $Itemk = mysql_fetch_object($BLAS);

                                        echo "<tr>";
                                        echo "<td width='33%'><b>Ausdauer</b></td>";
                                        echo "<td width='33%'><b>EP</b></td>";
                                        echo "<td width='33%'><b>BP</b></td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte12->id]' size='2' value='$Itemte12->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte12->id]' size='2' value='$Itemte12->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte12->id]' size='2' value='$Itemte12->Bluthalt'>/$Itemk->RustBlutung</td>";
                                        echo "</tr>";

                                        echo "</table>";
                                    }
                                    $sqlte123 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                    $queryte123 = mysql_query($sqlte123);
                                    while ($Itemte123 = mysql_fetch_object($queryte123)) {
                                        $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                        $queryteRRR = mysql_query($sqltERE);
                                        $Drin = mysql_fetch_object($queryteRRR);
                                        echo "<table border='0' width='100%'>
                                        <tr>";
                                        if ($Drin->id > 0 or $Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte123->id');\">$Itemte123->Item</a>
                                            </td><td><input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                        } else {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte123->Item</td><td>
                                            <input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                        }
                                        echo "</table>";
                                        echo "<div id='Item$Itemte123->id' style='display:none'>";
                                        if ($Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                            echo "<table border='0' width='75%'>";
                                            $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte123->Item'";
                                            $BLAS = mysql_query($BLA);
                                            $Itemk = mysql_fetch_object($BLAS);

                                            echo "<tr>";
                                            echo "<td width='33%'><b>Ausdauer</b></td>";
                                            echo "<td width='33%'><b>EP</b></td>";
                                            echo "<td width='33%'><b>BP</b></td>";
                                            echo "</tr>";

                                            echo "<tr>";
                                            echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte123->id]' size='2' value='$Itemte123->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte123->id]' size='2' value='$Itemte123->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte123->id]' size='2' value='$Itemte123->Bluthalt'>/$Itemk->RustBlutung</td>";
                                            echo "</tr>";

                                            echo "</table>";
                                        }
                                        $sqlte1234 = "SELECT id, Item, Menge FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                        $queryte1234 = mysql_query($sqlte1234);
                                        while ($Itemte1234 = mysql_fetch_object($queryte1234)) {
                                            echo "<table border='0' width='100%'>
                                            <tr>";
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1234->Item</td><td>
                                            <input type='text' name='Item[$Itemte1234->id]' size='2' value='$Itemte1234->Menge'><br></td>";
                                            echo "</table>";
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";

                        echo "</td>";

                        if ($Dudu == 1) {
                            echo "</tr>";
                            $Dudu = -1;
                        }
                        $Dudu += 1;
                    }
                }

                echo "</table></td></tr>";
                echo "</table>";

                if ($Userobject->Clan == "Inuzuka Familie") {
                    $sql = "SELECT COUNT(*) FROM Inuzuka WHERE Besitzer = '$user'";
                    $query = mysql_query($sql);
                    $Tierzahl = mysql_fetch_row($query);
                    $ZahlTiere = $Tierzahl[0];
                    $sql = "SELECT * FROM Inuzuka WHERE Besitzer = '$user'";
                    $query = mysql_query($sql) or die("Fehler");
                    while ($Tier = mysql_fetch_object($query)) {
                        $Tiernummer += 1;
                        echo "<table border='0' width='90%'>";
                        echo "<tr>";
                        echo "<td><b>Tier</b></td>";
                        echo "<td><b>von</b></td>";
                        echo "<td><b>$Userobject->name</b></td>";
                        echo "<td><b>($Tier->Name)</b></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><font color='#FF0000'><b>Ausdauer</b></font>:</td>";
                        echo "<td><input type='hidden' name='Tier$Tiernummer' value='$Tier->id'>
                        <input type='text' name='TierAus$Tier->id' value='$Tier->Ausdauer'>";
                        if ($ZahlTiere == 1) {
                            $ausdauer = $Userobject->Ausdauer;
                            $ausdauer *= 0.8;
                        } elseif ($ZahlTiere == 2) {
                            $ausdauer = $Userobject->Ausdauer;
                            $ausdauer *= 0.56;
                        } else {
                            $ausdauer = $Userobject->Ausdauer;
                            $ausdauer *= 0.4;
                        }
                        echo "/$ausdauer";
                        echo "</td>";
                        echo "<td><font color='blue'><b>Chakra</b></font>:</td>";
                        echo "<td><input type='text' name='TierCha$Tier->id' value='$Tier->Chakra'>";
                        if ($ZahlTiere == 1) {
                            $ausdauer = $Userobject->Chakra;
                            $ausdauer *= 0.8;
                        } elseif ($ZahlTiere == 2) {
                            $ausdauer = $Userobject->Chakra;
                            $ausdauer *= 0.56;
                        } else {
                            $ausdauer = $Userobject->Chakra;
                            $ausdauer *= 0.4;
                        }
                        echo "/$ausdauer</td>";
                        echo "</tr>";
                        echo "</table>";

                        echo "<table border='0' width='90%'>";

                        echo "<tr>";
                        echo "<td colspan='2' width='20%'><b>Verbrauchte</b></td>";
                        echo "<td colspan='2' width='20%'><b>Items</b></td>";
                        echo "<td colspan='2'></td>";
                        echo "<td colspan='2'></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='8'>";
                        echo "<table border='0' width='100%'>";

                        $Angelegtvorher = "";
                        $Dudu = 0;
                        $sqls = "SELECT * FROM Item WHERE Von = '$Userobject->id' AND Angelegt LIKE 'Inu$Tier->id %' ORDER BY Angelegt";
                        $querys = mysql_query($sqls);
                        while ($Itemr = mysql_fetch_object($querys)) {
                            if ($Angelegtvorher == "" and $Itemr->Angelegt == "") {
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><b>Items zu Hause</b></td></tr>";
                                $Angelegtvorher = "LOL";
                            } elseif ($Angelegtvorher == "" and $Itemr->Angelegt != "") {
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><b>Angelegte Items</b></td></tr>";
                                $Angelegtvorher = "ROFL";
                            } elseif ($Angelegtvorher == "LOL" and $Itemr->Angelegt != "") {
                                $Angelegtvorher = "ROFL";
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><br><b>Angelegte Items</b></td></tr>";
                            }

                            $okay = 0;
                            $Angelegt = "<><>$Itemr->Angelegt";
                            $pos = strpos($Angelegt, "<><>Item:");
                            if ($pos === false) {
                                $okay = 1;
                            } else {
                            }
                            $pos = strpos($Angelegt, "<><>Puppe:");
                            if ($pos === false) {
                            } else {
                                $okay = 0;
                            }

                            if ($okay == 1) {
                                if ($Dudu == 0) {
                                    echo "<tr>";
                                }
                                echo "<td valign='top' width='50%'>";
                                echo "<table border='0' width='100%'><tr>";
                                $sqlt = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemr->id'";
                                $queryt = mysql_query($sqlt);
                                $Drin = mysql_fetch_object($queryt);
                                if ($Drin->id > 0 or $Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemr->id');\">$Itemr->Item</a></td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                                } else {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> $Itemr->Item</td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                                }
                                echo "</tr>
                                </table>";
                                echo "<div id='Item$Itemr->id' style='display:none'>";

                                if ($Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                                    echo "<table border='0' width='75%'>";
                                    $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemr->Item'";
                                    $BLAS = mysql_query($BLA);
                                    $Itemk = mysql_fetch_object($BLAS);

                                    echo "<tr>";
                                    echo "<td width='33%'><b>Ausdauer</b></td>";
                                    echo "<td width='33%'><b>EP</b></td>";
                                    echo "<td width='33%'><b>BP</b></td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemr->id]' size='2' value='$Itemr->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemr->id]' size='2' value='$Itemr->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemr->id]' size='2' value='$Itemr->Bluthalt'>/$Itemk->RustBlutung</td>";
                                    echo "</tr>";

                                    echo "</table>";
                                }

                                $sqlte = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemr->id'";
                                $queryte = mysql_query($sqlte);
                                while ($Itemte = mysql_fetch_object($queryte)) {
                                    $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                                    $queryteRRR = mysql_query($sqltERE);
                                    $Drin = mysql_fetch_object($queryteRRR);
                                    echo "<table border='0' width='100%'>
                                    <tr>";
                                    if ($Drin->id > 0 or $Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte->id');\">$Itemte->Item </a>
                                        </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                                    } else {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte->Item
                                        </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                                    }
                                    echo "</table>";

                                    echo "<div id='Item$Itemte->id' style='display:none'>";
                                    if ($Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                        echo "<table border='0' width='75%'>";
                                        $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte->Item'";
                                        $BLAS = mysql_query($BLA);
                                        $Itemk = mysql_fetch_object($BLAS);

                                        echo "<tr>";
                                        echo "<td width='33%'><b>Ausdauer</b></td>";
                                        echo "<td width='33%'><b>EP</b></td>";
                                        echo "<td width='33%'><b>BP</b></td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte->id]' size='2' value='$Itemte->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte->id]' size='2' value='$Itemte->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte->id]' size='2' value='$Itemte->Bluthalt'>/$Itemk->RustBlutung</td>";
                                        echo "</tr>";

                                        echo "</table>";
                                    }
                                    $sqlte1 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                                    $queryte1 = mysql_query($sqlte1);
                                    while ($Itemte1 = mysql_fetch_object($queryte1)) {
                                        $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                        $queryteRRR = mysql_query($sqltERE);
                                        $Drin = mysql_fetch_object($queryteRRR);
                                        echo "<table border='0' width='100%'>
                                        <tr>";
                                        if ($Drin->id > 0 or $Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte1->id');\">$Itemte1->Item</a>
                                            </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                        } else {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1->Item
                                            </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                        }
                                        echo "</table>";
                                        echo "<div id='Item$Itemte1->id' style='display:none'>";
                                        if ($Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                            echo "<table border='0' width='75%'>";
                                            $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte1->Item'";
                                            $BLAS = mysql_query($BLA);
                                            $Itemk = mysql_fetch_object($BLAS);

                                            echo "<tr>";
                                            echo "<td width='33%'><b>Ausdauer</b></td>";
                                            echo "<td width='33%'><b>EP</b></td>";
                                            echo "<td width='33%'><b>BP</b></td>";
                                            echo "</tr>";

                                            echo "<tr>";
                                            echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte1->id]' size='2' value='$Itemte1->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte1->id]' size='2' value='$Itemte1->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte1->id]' size='2' value='$Itemte1->Bluthalt'>/$Itemk->RustBlutung</td>";
                                            echo "</tr>";

                                            echo "</table>";
                                        }
                                        $sqlte12 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                        $queryte12 = mysql_query($sqlte12);
                                        while ($Itemte12 = mysql_fetch_object($queryte12)) {
                                            $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                            $queryteRRR = mysql_query($sqltERE);
                                            $Drin = mysql_fetch_object($queryteRRR);
                                            echo "<table border='0' width='100%'>
                                            <tr>";
                                            if ($Drin->id > 0 or $Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte12->id');\">$Itemte12->Item</a>
                                                </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                            } else {
                                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte12->Item
                                                </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                            }
                                            echo "</table>";
                                            echo "<div id='Item$Itemte12->id' style='display:none'>";
                                            if ($Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                                echo "<table border='0' width='75%'>";
                                                $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte12->Item'";
                                                $BLAS = mysql_query($BLA);
                                                $Itemk = mysql_fetch_object($BLAS);

                                                echo "<tr>";
                                                echo "<td width='33%'><b>Ausdauer</b></td>";
                                                echo "<td width='33%'><b>EP</b></td>";
                                                echo "<td width='33%'><b>BP</b></td>";
                                                echo "</tr>";

                                                echo "<tr>";
                                                echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte12->id]' size='2' value='$Itemte12->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                                echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte12->id]' size='2' value='$Itemte12->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                                echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte12->id]' size='2' value='$Itemte12->Bluthalt'>/$Itemk->RustBlutung</td>";
                                                echo "</tr>";

                                                echo "</table>";
                                            }
                                            $sqlte123 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                            $queryte123 = mysql_query($sqlte123);
                                            while ($Itemte123 = mysql_fetch_object($queryte123)) {
                                                $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                                $queryteRRR = mysql_query($sqltERE);
                                                $Drin = mysql_fetch_object($queryteRRR);
                                                echo "<table border='0' width='100%'>
                                                <tr>";
                                                if ($Drin->id > 0 or $Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte123->id');\">$Itemte123->Item</a>
                                                    </td><td><input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                                } else {
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte123->Item</td><td>
                                                    <input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                                }
                                                echo "</table>";
                                                echo "<div id='Item$Itemte123->id' style='display:none'>";
                                                if ($Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                                    echo "<table border='0' width='75%'>";
                                                    $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte123->Item'";
                                                    $BLAS = mysql_query($BLA);
                                                    $Itemk = mysql_fetch_object($BLAS);

                                                    echo "<tr>";
                                                    echo "<td width='33%'><b>Ausdauer</b></td>";
                                                    echo "<td width='33%'><b>EP</b></td>";
                                                    echo "<td width='33%'><b>BP</b></td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                    echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte123->id]' size='2' value='$Itemte123->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                                    echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte123->id]' size='2' value='$Itemte123->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                                    echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte123->id]' size='2' value='$Itemte123->Bluthalt'>/$Itemk->RustBlutung</td>";
                                                    echo "</tr>";

                                                    echo "</table>";
                                                }
                                                $sqlte1234 = "SELECT id, Item, Menge FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                                $queryte1234 = mysql_query($sqlte1234);
                                                while ($Itemte1234 = mysql_fetch_object($queryte1234)) {
                                                    echo "<table border='0' width='100%'>
                                                    <tr>";
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1234->Item</td><td>
                                                    <input type='text' name='Item[$Itemte1234->id]' size='2' value='$Itemte1234->Menge'><br></td>";
                                                    echo "</table>";
                                                }
                                                echo "</div>";
                                            }
                                            echo "</div>";
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                echo "</div>";

                                echo "</td>";

                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = -1;
                                }
                                $Dudu += 1;
                            }
                        }
                        echo "</table></td></tr>";
                    }
                }

                if ($Userobject->Clan == "Ningyosenshu Clan") {
                    $sql = "SELECT * FROM Marionetten WHERE Besitzer = '$user'";
                    $query = mysql_query($sql) or die("Fehler");
                    while ($Puppe = mysql_fetch_array($query)) {
                        $Puppennr += 1;
                        $Puppenid = $Puppe["id"];
                        $Name = $Puppe["Name"];
                        $Haltbarkeit = $Puppe["Haltbarkeit"];
                        $Haltbarkeitmax = $Puppe["Haltbarkeitmax"];
                        echo "<table border='0' width='90%'>";
                        echo "<tr>";
                        echo "<td><b>Marionette Nr. $Puppennr</b></td>";
                        echo "<td><b>von</b></td>";
                        echo "<td><b>$Userobject->name</b></td>";
                        echo "<td><b>($Name)</b></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><font color='#993300'><b>Haltbarkeit</b></font>:</td>";
                        echo "<td><input type='hidden' name='Puppe$Puppennr' value='$Puppenid'><input type='text' name='PuppeHaltbar$Puppennr' value='$Haltbarkeit'>";
                        echo "/$Haltbarkeitmax";
                        echo "</td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        echo "</table>";

                        echo "<table border='0' width='90%'>";

                        echo "<tr>";
                        echo "<td colspan='2' width='20%'><b>Verbrauchte</b></td>";
                        echo "<td colspan='2' width='20%'><b>Items</b></td>";
                        echo "<td colspan='2'></td>";
                        echo "<td colspan='2'></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='8'>";
                        echo "<table border='0' width='100%'>";

                        $Angelegtvorher = "";
                        $Dudu = 0;
                        $sqls = "SELECT * FROM Item WHERE Von = '$Userobject->id' AND Angelegt LIKE 'Puppe:$Puppenid|%' ORDER BY Angelegt";
                        $querys = mysql_query($sqls);
                        while ($Itemr = mysql_fetch_object($querys)) {
                            if ($Angelegtvorher == "" and $Itemr->Angelegt == "") {
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><b>Items zu Hause</b></td></tr>";
                                $Angelegtvorher = "LOL";
                            } elseif ($Angelegtvorher == "" and $Itemr->Angelegt != "") {
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><b>Angelegte Items</b></td></tr>";
                                $Angelegtvorher = "ROFL";
                            } elseif ($Angelegtvorher == "LOL" and $Itemr->Angelegt != "") {
                                $Angelegtvorher = "ROFL";
                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = 0;
                                }
                                echo "<tr><td colspan='2'><br><b>Angelegte Items</b></td></tr>";
                            }

                            $okay = 0;
                            $Angelegt = "<><>$Itemr->Angelegt";
                            $pos = strpos($Angelegt, "<><>Item:");
                            if ($pos === false) {
                                $okay = 1;
                            } else {
                            }

                            if ($okay == 1) {
                                if ($Dudu == 0) {
                                    echo "<tr>";
                                }
                                echo "<td valign='top' width='50%'>";
                                echo "<table border='0' width='100%'>
                                <tr>";
                                $sqlt = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemr->id'";
                                $queryt = mysql_query($sqlt);
                                $Drin = mysql_fetch_object($queryt);
                                if ($Drin->id > 0 or $Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemr->id');\">$Itemr->Item</a></td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                                } else {
                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;-> $Itemr->Item</td><td><input type='text' name='Item[$Itemr->id]' size='2' value='$Itemr->Menge'></td>";
                                }
                                echo "</tr>
                                </table>";
                                echo "<div id='Item$Itemr->id' style='display:none'>";

                                if ($Itemr->Ausdauerhalt > 0 or $Itemr->Beschrankunghalt > 0 or $Itemr->Bluthalt > 0) {
                                    echo "<table border='0' width='75%'>";
                                    $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemr->Item'";
                                    $BLAS = mysql_query($BLA);
                                    $Itemk = mysql_fetch_object($BLAS);

                                    echo "<tr>";
                                    echo "<td width='33%'><b>Ausdauer</b></td>";
                                    echo "<td width='33%'><b>EP</b></td>";
                                    echo "<td width='33%'><b>BP</b></td>";
                                    echo "</tr>";

                                    echo "<tr>";
                                    echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemr->id]' size='2' value='$Itemr->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemr->id]' size='2' value='$Itemr->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                    echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemr->id]' size='2' value='$Itemr->Bluthalt'>/$Itemk->RustBlutung</td>";
                                    echo "</tr>";

                                    echo "</table>";
                                }

                                $sqlte = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemr->id'";
                                $queryte = mysql_query($sqlte);
                                while ($Itemte = mysql_fetch_object($queryte)) {
                                    $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                                    $queryteRRR = mysql_query($sqltERE);
                                    $Drin = mysql_fetch_object($queryteRRR);
                                    echo "<table border='0' width='100%'>
                                    <tr>";
                                    if ($Drin->id > 0 or $Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte->id');\">$Itemte->Item </a>
                                        </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                                    } else {
                                        echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte->Item
                                        </td><td><input type='text' name='Item[$Itemte->id]' size='2' value='$Itemte->Menge'><br></td>";
                                    }
                                    echo "</table>";

                                    echo "<div id='Item$Itemte->id' style='display:none'>";
                                    if ($Itemte->Ausdauerhalt > 0 or $Itemte->Beschrankunghalt > 0 or $Itemte->Bluthalt > 0) {
                                        echo "<table border='0' width='75%'>";
                                        $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte->Item'";
                                        $BLAS = mysql_query($BLA);
                                        $Itemk = mysql_fetch_object($BLAS);

                                        echo "<tr>";
                                        echo "<td width='33%'><b>Ausdauer</b></td>";
                                        echo "<td width='33%'><b>EP</b></td>";
                                        echo "<td width='33%'><b>BP</b></td>";
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte->id]' size='2' value='$Itemte->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte->id]' size='2' value='$Itemte->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                        echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte->id]' size='2' value='$Itemte->Bluthalt'>/$Itemk->RustBlutung</td>";
                                        echo "</tr>";

                                        echo "</table>";
                                    }

                                    $sqlte1 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte->id'";
                                    $queryte1 = mysql_query($sqlte1);
                                    while ($Itemte1 = mysql_fetch_object($queryte1)) {
                                        $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                        $queryteRRR = mysql_query($sqltERE);
                                        $Drin = mysql_fetch_object($queryteRRR);
                                        echo "<table border='0' width='100%'>
                                        <tr>";
                                        if ($Drin->id > 0 or $Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte1->id');\">$Itemte1->Item</a>
                                            </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                        } else {
                                            echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1->Item
                                            </td><td><input type='text' name='Item[$Itemte1->id]' size='2' value='$Itemte1->Menge'><br></td>";
                                        }
                                        echo "</table>";
                                        echo "<div id='Item$Itemte1->id' style='display:none'>";
                                        if ($Itemte1->Ausdauerhalt > 0 or $Itemte1->Beschrankunghalt > 0 or $Itemte1->Bluthalt > 0) {
                                            echo "<table border='0' width='75%'>";
                                            $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte1->Item'";
                                            $BLAS = mysql_query($BLA);
                                            $Itemk = mysql_fetch_object($BLAS);

                                            echo "<tr>";
                                            echo "<td width='33%'><b>Ausdauer</b></td>";
                                            echo "<td width='33%'><b>EP</b></td>";
                                            echo "<td width='33%'><b>BP</b></td>";
                                            echo "</tr>";

                                            echo "<tr>";
                                            echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte1->id]' size='2' value='$Itemte1->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte1->id]' size='2' value='$Itemte1->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                            echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte1->id]' size='2' value='$Itemte1->Bluthalt'>/$Itemk->RustBlutung</td>";
                                            echo "</tr>";

                                            echo "</table>";
                                        }
                                        $sqlte12 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte1->id'";
                                        $queryte12 = mysql_query($sqlte12);
                                        while ($Itemte12 = mysql_fetch_object($queryte12)) {
                                            $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                            $queryteRRR = mysql_query($sqltERE);
                                            $Drin = mysql_fetch_object($queryteRRR);
                                            echo "<table border='0' width='100%'>
                                            <tr>";
                                            if ($Drin->id > 0 or $Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte12->id');\">$Itemte12->Item</a>
                                                </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                            } else {
                                                echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte12->Item
                                                </td><td><input type='text' name='Item[$Itemte12->id]' size='2' value='$Itemte12->Menge'><br></td>";
                                            }
                                            echo "</table>";
                                            echo "<div id='Item$Itemte12->id' style='display:none'>";
                                            if ($Itemte12->Ausdauerhalt > 0 or $Itemte12->Beschrankunghalt > 0 or $Itemte12->Bluthalt > 0) {
                                                echo "<table border='0' width='75%'>";
                                                $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte12->Item'";
                                                $BLAS = mysql_query($BLA);
                                                $Itemk = mysql_fetch_object($BLAS);

                                                echo "<tr>";
                                                echo "<td width='33%'><b>Ausdauer</b></td>";
                                                echo "<td width='33%'><b>EP</b></td>";
                                                echo "<td width='33%'><b>BP</b></td>";
                                                echo "</tr>";

                                                echo "<tr>";
                                                echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte12->id]' size='2' value='$Itemte12->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                                echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte12->id]' size='2' value='$Itemte12->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                                echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte12->id]' size='2' value='$Itemte12->Bluthalt'>/$Itemk->RustBlutung</td>";
                                                echo "</tr>";

                                                echo "</table>";
                                            }
                                            $sqlte123 = "SELECT * FROM Item WHERE Angelegt = 'Item: $Itemte12->id'";
                                            $queryte123 = mysql_query($sqlte123);
                                            while ($Itemte123 = mysql_fetch_object($queryte123)) {
                                                $sqltERE = "SELECT id FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                                $queryteRRR = mysql_query($sqltERE);
                                                $Drin = mysql_fetch_object($queryteRRR);
                                                echo "<table border='0' width='100%'>
                                                <tr>";
                                                if ($Drin->id > 0 or $Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> <a href=\"javascript:show('Item$Itemte123->id');\">$Itemte123->Item</a>
                                                    </td><td><input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                                } else {
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte123->Item</td><td>
                                                    <input type='text' name='Item[$Itemte123->id]' size='2' value='$Itemte123->Menge'><br></td>";
                                                }
                                                echo "</table>";
                                                echo "<div id='Item$Itemte123->id' style='display:none'>";
                                                if ($Itemte123->Ausdauerhalt > 0 or $Itemte123->Beschrankunghalt > 0 or $Itemte123->Bluthalt > 0) {
                                                    echo "<table border='0' width='75%'>";
                                                    $BLA = "SELECT * FROM Itemsk WHERE Name = '$Itemte123->Item'";
                                                    $BLAS = mysql_query($BLA);
                                                    $Itemk = mysql_fetch_object($BLAS);

                                                    echo "<tr>";
                                                    echo "<td width='33%'><b>Ausdauer</b></td>";
                                                    echo "<td width='33%'><b>EP</b></td>";
                                                    echo "<td width='33%'><b>BP</b></td>";
                                                    echo "</tr>";

                                                    echo "<tr>";
                                                    echo "<td width='33%'><input type='text' name='ItemAusdauer[$Itemte123->id]' size='2' value='$Itemte123->Ausdauerhalt'>/$Itemk->RustAusdauer</td>";
                                                    echo "<td width='33%'><input type='text' name='ItemBeschrankung[$Itemte123->id]' size='2' value='$Itemte123->Beschrankunghalt'>/$Itemk->RustBeschrankung</td>";
                                                    echo "<td width='33%'><input type='text' name='ItemBlutung[$Itemte123->id]' size='2' value='$Itemte123->Bluthalt'>/$Itemk->RustBlutung</td>";
                                                    echo "</tr>";

                                                    echo "</table>";
                                                }
                                                $sqlte1234 = "SELECT id, Item, Menge FROM Item WHERE Angelegt = 'Item: $Itemte123->id'";
                                                $queryte1234 = mysql_query($sqlte1234);
                                                while ($Itemte1234 = mysql_fetch_object($queryte1234)) {
                                                    echo "<table border='0' width='100%'>
                                                    <tr>";
                                                    echo "<td width='75%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-> $Itemte1234->Item</td><td>
                                                    <input type='text' name='Item[$Itemte1234->id]' size='2' value='$Itemte1234->Menge'><br></td>";
                                                    echo "</table>";
                                                }
                                                echo "</div>";
                                            }
                                            echo "</div>";
                                        }
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                echo "</div>";

                                echo "</td>";

                                if ($Dudu == 1) {
                                    echo "</tr>";
                                    $Dudu = -1;
                                }
                                $Dudu += 1;
                            }
                        }
                        echo "</table></td></tr>";
                    }
                }


                $sql = "SELECT * FROM Verletzungen WHERE id = '$user'";
                $query = mysql_query($sql) or die("Fehler");
                $Verletzungen = mysql_fetch_object($query);

                echo "<table border='0' width='90%'>
                <tr>
                <td colspan='8'><b>Verletzungen (EP | BP):</b></td>
                </tr>
                <tr>
                <td>Arme/Schultern</td><td>L: <input type='text' name='Armlinks$zahlen' size='2' value='";
                $Verletzungen->Armlinks /= 100;
                echo "$Verletzungen->Armlinks'> | <input type='text' name='ArmlinksBP$zahlen' size='2' value='";
                $Verletzungen->ArmlinksBP /= 100;
                echo "$Verletzungen->ArmlinksBP'>
                <br>R:<input type='text' name='Armrechts$zahlen' size='2' value='";
                $Verletzungen->Armrechts /= 100;
                echo "$Verletzungen->Armrechts'> | <input type='text' name='ArmrechtsBP$zahlen' size='2' value='";
                $Verletzungen->ArmrechtsBP /= 100;
                echo "$Verletzungen->ArmrechtsBP'>
                </td>
                <td>Beine</td><td>L: <input type='text' name='Beinlinks$zahlen' size='2' value='";
                $Verletzungen->Beinlinks /= 100;
                echo "$Verletzungen->Beinlinks'> | <input type='text' name='BeinlinksBP$zahlen' size='2' value='";
                $Verletzungen->BeinlinksBP /= 100;
                echo "$Verletzungen->BeinlinksBP'>
                <br>R:<input type='text' name='Beinrechts$zahlen' size='2' value='";
                $Verletzungen->Beinrechts /= 100;
                echo "$Verletzungen->Beinrechts'> | <input type='text' name='BeinrechtsBP$zahlen' size='2' value='";
                $Verletzungen->BeinrechtsBP /= 100;
                echo "$Verletzungen->BeinrechtsBP'>
                </td>
                </tr>
                <tr>
                <td>Kopf</td><td><input type='text' name='Kopf$zahlen' size='2' value='";
                $Verletzungen->Kopf /= 100;
                echo "$Verletzungen->Kopf'> | <input type='text' name='KopfBP$zahlen' size='2' value='";
                $Verletzungen->KopfBP /= 100;
                echo "$Verletzungen->KopfBP'>
                </td>
                <td>Hals</td><td><input type='text' name='Hals$zahlen' size='2' value='";
                $Verletzungen->Hals /= 100;
                echo "$Verletzungen->Hals'> | <input type='text' name='HalsBP$zahlen' size='2' value='";
                $Verletzungen->HalsBP /= 100;
                echo "$Verletzungen->HalsBP'>
                </td>
                <td>Bauch</td><td><input type='text' name='Bauch$zahlen' size='2' value='";
                $Verletzungen->Bauch /= 100;
                echo "$Verletzungen->Bauch'> | <input type='text' name='BauchBP$zahlen' size='2' value='";
                $Verletzungen->BauchBP /= 100;
                echo "$Verletzungen->BauchBP'>
                </td>
                </tr>
                <tr>
                <td>Rücken</td><td colspan='7'><input type='text' name='Rucken$zahlen' size='2' value='";
                $Verletzungen->Rucken /= 100;
                echo "$Verletzungen->Rucken'> | <input type='text' name='RuckenBP$zahlen' size='2' value='";
                $Verletzungen->Rucken /= 100;
                echo "$Verletzungen->Rucken'>
                </td>
                </tr>

                </table>
                Sieg: <input type='radio' name='sieger$zahlen' value='11'>Niederlage: <input type='radio' name='sieger$zahlen' value='22'>Unentschieden: <input type='radio' name='sieger$zahlen' value='33'><br>
                <hr><br><center>";

                $zahlen -= 1;
            }
            echo "Sonstiges(Kopierte Techs etc.):<br><textarea rows='6' name='Sonstiges' cols='45'></textarea><br>";
            echo "<select name='Kampfart' id='Kampfart'><option selected>Normaler Kampf</option>
            <option>Pr&uuml;fungskampf</option><option>Todeskampf</option></select><br>";
            echo "<input type='submit' value='Eintragen'></form>";
        }
    } else {
        echo sprintf("<form method='post' action='Kampfeintrag.php?kzahl=%s'>", $kzahl);
        echo "<select name='kzahl'>
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
        <option>9</option>
        <option>10</option>
        <option>11</option>
        <option>12</option>
        <option>13</option>
        <option>14</option>
        <option>15</option>
        <option>16</option>
        <option>17</option>
        <option>18</option>
        <option>19</option>
        <option>20</option>
        </select><input type='submit' name='Submit' value='Kämpferzahl ändern'></form>";
        if ($kzahl) {
            echo sprintf("<form method='post' action='Kampfeintrag.php?zahlen=%s'>", $kzahl);
            $zahls = $kzahl;
            while ($zahls != "0") {
                echo "Kämpfer:<input name='ninja$zahls' id=\"theText\" type=text autocomplete=off onkeyup=\"editForm(this.value)\"><div id=\"livesearch\"></div> KR-PW:  <input type='text' name='ninkrpw$zahls'><br>";
                $zahls -= 1;
            }
            echo "<input type='submit' name='Submit' value='Diese Ninja auswählen'></form>";
        }
    }
} else {
    echo "Sie haben keine Rechte diese Seite zu betreten!";
}

get_footer();
