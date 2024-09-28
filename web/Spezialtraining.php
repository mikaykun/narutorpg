<?php

include __DIR__ . "/../Menus/layout1.inc";

function TpMonat($Niveau): int
{
    return match ($Niveau) {
        2 => 6,
        3 => 8,
        4 => 10,
        default => 0,
    };
}

$rofl = mysql_query("SELECT * FROM Fähigkeiten WHERE id = '$dorfs2->id'");
$u_faehs = mysql_fetch_array($rofl);
$query = mysql_query("SELECT * FROM Jutsuk WHERE id = '$dorfs2->id'");
$u_Jutsu = mysql_fetch_assoc($query);
$Jutsuk = $u_Jutsu;
$tps = new tpKosten();
$tps->thisIsOkMax($u_Jutsu, $dorfs2);
$kampffaehAusnahmen = [2, 4, 15, 75];
include(__DIR__ . "/../layouts/Overview/OverviewLand.php");
include(__DIR__ . "/../layouts/Overview/OverviewLand2.php");

$sql = "SELECT * FROM Besonderheiten WHERE id = '$dorfs->id'";
$query = mysql_query($sql);
$Besonderheiten = mysql_fetch_object($query);

$sql = "SELECT * FROM Fähigkeiten WHERE id = '$dorfs->id'";
$query = mysql_query($sql);
$Faehigks = mysql_fetch_object($query);

$query = mysql_query("SELECT VerboteneJutsu FROM Landdaten WHERE Land = '$dorfs2->Heimatdorf' LIMIT 1");
$LandVerbotene = mysql_fetch_object($query);

if ($Besonderheiten->Sand != 1) {
    $zusatz2 = ' AND Element != \'Sand\'';
}

echo "<tr>
<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='6'><br>";

if ($dorfs->id < 1) {
    echo "Wird überarbeitet";
} else {
    if ($dorfs2->Rang != "Akademist") {
        echo "<b>Jutsu und Fähigkeiten über das Center erlernen</b><br><br>";

        ///ANTRAGMACH FAENGT HIER AN!!!
        if (isset($_GET['Antragmach']) && $dorfs2->CenterPunkte > 0) {
            $sql = "SELECT * FROM Trainingsanträge WHERE User = '$c_loged' AND `done` = '0'";
            $query = mysql_query($sql);
            $num = 0;
            while ($Antrag = mysql_fetch_object($query)) {
                $num++;
            }
            if ($num > 0) {
                echo 'Du hast bereits ein oder mehr Trainings &uuml;ber das System beantragt. Bitte l&ouml;sche diese oder f&uuml;hre sie durch, bevor du ein neues beantragst.';
            } else {
                $LFKontrolle = 1;
                $Adminkontrolle = 1;
                if ($ArtSpez == "Jutsu") {
                    $JutsuSpez2 = str_replace(" ", "abba", $JutsuSpez);
                    $sql = "SELECT * FROM Jutsu WHERE `Name` = '$JutsuSpez2' ORDER BY Jutsutyp DESC, Name";
                    $query = mysql_query($sql);
                    while ($Jutsu = mysql_fetch_object($query)) {
                        if ($tps->thisJutsuOk($Jutsu, $dorfs2, $Faehigks->Siegelkunst) === false) {
                            echo "$Jutsu->Name kannst du nicht erlernen.<br><br> $Jutsu->Name<br>$Jutsu->Jutsubraucht";
                        } else {
                            $TP = $tps->howMuchIsThisJutsu($Jutsu->Name, 0, $Jutsuk, 1);

                            $VerboteneJutsu = 0;
                            if (str_contains($LandVerbotene->VerboteneJutsu, "|$Jutsu->Name|")) {
                                $VerboteneJutsu = 1;
                                $LFKontrolle = 0;
                                echo "Verbotene Jutsu<br>";
                            }
                            if ($Jutsu->Eigenejutsu == 1) {
                                $Adminkontrolle = 0;
                                echo "Eigenentwicklung<br>";
                            }
                            $JutsuAnforderung = $Jutsu->Taijutsu;
                            if ($Jutsu->Ninjutsu > $JutsuAnforderung) {
                                $JutsuAnforderung = $Jutsu->Ninjutsu;
                            }
                            if ($Jutsu->Genjutsu > $JutsuAnforderung) {
                                $JutsuAnforderung = $Jutsu->Genjutsu;
                            }

                            if ($JutsuAnforderung >= 9) {
                                $Adminkontrolle = 0;
                            }


                            $CP = round(($TP / TpMonat($dorfs2->Niveau)) / 2 + 0.04, 1);
                            //edit by Kado aufgrund folgenden Treads: http://www.narutorpg.de/Forum_Topic.php?Top=30374 gab es nen grund weshalb man > und nicht >= gemacht hat?
                            if ($dorfs2->CenterPunkte - $CP >= 0) {
                                $ins = "INSERT INTO Trainingsanträge (User, Art, TPweg, DauerEE, Name, Stufe, LFKontrolle, Adminkontrolle, Dorf) VALUES ('$dorfs->id', 'Jutsu', '$TP','$Dauer','$JutsuSpez', '$JutsuAnforderung', '$LFKontrolle', '$Adminkontrolle', '$dorfs2->Heimatdorf')";
                                mysql_query($ins);
                                $up = "UPDATE user SET Centerpunkte = Centerpunkte-$CP WHERE id = '$dorfs->id'";
                                mysql_query($up);
                            } else {
                                echo 'Du hast nicht genügend Centerpunke f&uuml;r dieses Training.';
                            }
                        }
                    }
                } elseif ($ArtSpez == "Fähigkeit") {
                    $sql = "SELECT $FaehigkeitSpezi FROM Fähigkeiten WHERE id = '$dorfs->id'";
                    $query = mysql_query($sql);
                    $Jutsuk = mysql_fetch_object($query);

                    $Geht = "";
                    $sql = "SELECT * FROM Informationen_Faehs WHERE id > '0' AND Verstecken = '0' AND `Tabellenname` = '$FaehigkeitSpezi'";
                    $query = mysql_query($sql);
                    $Faehigkeiter = mysql_fetch_array($query, MYSQL_ASSOC);

                    $Faehname = $Faehigkeiter['Tabellenname'];

                    $Werthast = (int)$Jutsuk->$Faehname;

                    if ($Werthast < 4) {
                        $Wertwird = $Werthast + 1;
                        if ($Wertwird <= $Faehigkeiter['Maxwert']) {
                            if ($Wertwird <= 4) {
                                if ($dorfs2->Niveau < 3 && $Wertwird > 2) {
                                    echo "Du kannst das nicht lernen";
                                } elseif ($dorfs2->Niveau < 4 && $Wertwird > 3) {
                                    echo "Du kannst das nicht lernen";
                                } elseif ((!in_array($Faehigkeiter['id'], $kampffaehAusnahmen)
                                        && $Faehigkeiter['Bereich'] == 'Kampf')
                                    && ($dorfs2->Taijutsu < 6 ||
                                        ($dorfs2->Taijutsu < 8 && $Wertwird > 2)
                                        ||
                                        ($dorfs2->Taijutsu < 10 && $Wertwird > 3))) {
                                    echo "Du kannst das nicht lernen";
                                } elseif ($Faehigkeiter['id'] == 51
                                    && ($dorfs2->Ninjutsu < 6 ||
                                        ($dorfs2->Ninjutsu < 8 && $Wertwird > 2)
                                        ||
                                        ($dorfs2->Ninjutsu < 10 && $Wertwird > 3))) {
                                    echo "Du kannst das nicht lernen";
                                } else {
                                    if ($Wertwird == 4) {
                                        $Adminkontrolle = 0;
                                    }
                                    //Chuunin nur bis Stufe 3

                                    //Genin nur bis Stufe 2
                                    $Geht = "$Geht|" . $Faehigkeiter['Tabellenname'] . "|";
                                }
                            }
                        }
                    }

                    $pos = strpos($Geht, "|$FaehigkeitSpezi|");
                    if ($pos === false) {
                        echo "Das kannst du nicht erlernen.<br><br>";
                    } else {
                        $Nextrang = $Jutsuk->$FaehigkeitSpezi;
                        $Nextrang += 1;
                        $TP = $tps->howMuchIsThisFaeh($Nextrang, $Faehigkeiter);


                        $CP = round(($TP / TpMonat($dorfs2->Niveau)) / 2 + 0.04, 1, PHP_ROUND_HALF_UP);
                        //edit by Kado aufgrund folgenden Treads: http://www.narutorpg.de/Forum_Topic.php?Top=30374 gab es nen grund weshalb man > und nicht >= gemacht hat?
                        if ($dorfs2->CenterPunkte - $CP >= 0) {
                            $ins = "INSERT INTO Trainingsanträge (User, Art, DauerEE,TPweg, Name, Stufe, LFKontrolle, Adminkontrolle, Dorf)
                        VALUES ('$dorfs->id', 'Fähigkeit', '$Dauer','$TP','$FaehigkeitSpezi', '$Nextrang', '$LFKontrolle', '$Adminkontrolle', '$dorfs2->Heimatdorf')";
                            mysql_query($ins);
                            $up = "UPDATE user SET Centerpunkte = Centerpunkte-$CP WHERE id = '$dorfs->id'";
                            mysql_query($up);
                        } else {
                            echo 'Du hast nicht genügend Centerpunke f&uuml;r dieses Training.';
                        }
                    }
                }
            }
        }
        ///ANTRAGMACH HOERT HIER AUF!!!

        //Loeschung Bestaetigung faengt hier an
        $LoeschSpez = (int)filter_input(INPUT_GET, 'LoeschSpez', FILTER_SANITIZE_NUMBER_INT);
        if ($LoeschSpez > 0) {
            echo "Den Antrag wirklich löschen?<br><a href='?LoeschenSpez=$LoeschSpez'>Ja, löschen</a><br><br>";
        }
        //Loeschung Bestaetigung hoeart hier auf

        //Loeschung ausfuehren
        $LoeschenSpez = (int)filter_input(INPUT_GET, 'LoeschenSpez', FILTER_SANITIZE_NUMBER_INT);
        if ($LoeschenSpez > 0) {
            if ($dorfs2->Training != "Training einer Jutsu" and $dorfs2->Training != "Training einer Fähigkeit") {
                $sql = "SELECT `TPweg` FROM Trainingsanträge WHERE User = '$c_loged' AND id = '$LoeschenSpez' AND Done = '0'";
                $query = mysql_query($sql);
                $Antrag = mysql_fetch_object($query);
                $CP = round(($Antrag->TPweg / TpMonat($dorfs2->Niveau)) / 2 + 0.04, 1, PHP_ROUND_HALF_UP);
                $up = "UPDATE user SET Centerpunkte = Centerpunkte+$CP WHERE id = '$dorfs->id'";
                mysql_query($up);
                $del = "DELETE FROM Trainingsanträge WHERE User = '$dorfs->id' AND id = '$LoeschenSpez' AND Done = '0'";
                mysql_query($del);
            } else {
                echo "Anträge auf Center-Training können nicht gelöscht werden, während du ein Center-Training durchführst.<br><br>";
            }
        }
        //Loeschung ende

        //HATE, SO MUCH HATE - Okay, hier wird Training eingetragen. Wieso da die TP-Kosten und ein Preis berechnet, aber nie verwendet werden weiß kein Mensch
        $TrainSpezi = (int)filter_input(INPUT_GET, 'TrainSpezi', FILTER_SANITIZE_NUMBER_INT);
        if ($TrainSpezi > 0) {
            $sql = "SELECT * FROM Trainingsanträge WHERE User = '$c_loged' AND `done` = '0'";
            $query = mysql_query($sql);
            $num = 0;
            while ($Antrag = mysql_fetch_object($query)) {
                $num++;
            }
            if ($num > 1) {
                echo 'Du hast mehr als 1 Centertraining eingestellt, bitte l&ouml;sche zun&auml;chst alle anderen, bevor du das Training einstellst.';
            } else {
                $sql = "SELECT * FROM Trainingsanträge WHERE User = '$c_loged' AND id = '$TrainSpezi' ORDER BY id DESC";
                $query = mysql_query($sql);
                $Antrag = mysql_fetch_object($query);
                if ($Antrag->id > 0 and $Antrag->Adminkontrolle == 1 and $Antrag->LFKontrolle == 1) {
                    if ($Antrag->Art == "Jutsu") {
                        $Dauer = $Antrag->DauerEE;
                        $TP = $Antrag->TPweg;
                    } elseif ($Antrag->Art == "Fähigkeit") {
                        $Dauer = $Antrag->DauerEE;
                        $TP = $Antrag->TPweg;
                    }

                    if ($dorfs2->Trainingspunkte >= ($TP - TpMonat($dorfs2->Niveau)) || $TP == 0) {
                        $Dateingert = date("d.m.Y");
                        $sqlAntrag = "SELECT TPweg, Name, Art, id, Stufe FROM Trainingsanträge WHERE User = '$dorfs2->id' AND id = '$Antrag->id' AND `Done` = '0' ORDER BY id DESC";
                        $queryAntrag = mysql_query($sqlAntrag);
                        $Antrag = mysql_fetch_object($queryAntrag);
                        $TP = $Antrag->TPweg;
                        $Jutsuname = str_replace(" ", "abba", $Antrag->Name);
                        $sqlAntrag = "SELECT Clan,Element FROM Jutsu WHERE Name = '$Jutsuname'";
                        $queryAntrag = mysql_query($sqlAntrag);
                        $Jutsu = mysql_fetch_object($queryAntrag);
                        if (($Jutsu->Clan == $dorfs2->Clan || $Jutsu->Element == 'Sand') and $dorfs2->ClanTP > 0) {
                            $clantp = ($dorfs2->ClanTP >= $TP) ? $TP : ($dorfs2->ClanTP);
                            $TP = ($dorfs2->ClanTP >= $TP) ? 0 : ($TP - $dorfs2->ClanTP);
                            $up = "UPDATE user SET ClanTP = ClanTP-$clantp WHERE id = '$dorfs2->id'";
                            mysql_query($up);
                            if ($TP > 0) {
                                $up = "UPDATE user SET Trainingspunkte = Trainingspunkte-$TP WHERE id = '$dorfs2->id'";
                                mysql_query($up);
                            }
                        } else {
                            $up = "UPDATE user SET Trainingspunkte = Trainingspunkte-$TP WHERE id = '$dorfs2->id'";
                            mysql_query($up);
                        }
                        if ($Antrag->Art == "Jutsu") {
                            $up = "UPDATE Jutsuk SET $Jutsuname = '1' WHERE id = '$dorfs2->id'";
                            mysql_query($up);
                        }
                        if ($Antrag->Art == "Fähigkeit") {
                            $up = "UPDATE Fähigkeiten SET $Jutsuname = '$Antrag->Stufe' WHERE id = '$dorfs2->id'";
                            mysql_query($up);
                        }
                        $up = "UPDATE Trainingsanträge SET Datum = '$Dateingert', Done = '1' WHERE id = '$Antrag->id'";
                        mysql_query($up);
                        $sqlAntrag = "SELECT Leiter FROM Teams WHERE id = '$dorfs2->Team'";
                        $queryAntrag = mysql_query($sqlAntrag);
                        $jouninAntrag = mysql_fetch_object($queryAntrag);
                        $gp = new gefahrenPotential();
                        $ENDTP = $dorfs2->Trainingspunkte - $TP;
                        $ENDCLANTP = $dorfs2->ClanTP - $clantp;
                        $User = $dorfs2;
                        $takes = '';
                        if (isset($clantp) && $clantp > 0) {
                            $takes = " (" . $User->ClanTP . " - " . $clantp . " = " . $ENDCLANTP . " ClanTP)";
                        }
                        if (isset($TP) && ($TP > 0 || $clantp == 0)) {
                            $takes .= " (" . $User->Trainingspunkte . " - " . $TP . " = " . $ENDTP . " TP)";
                        }
                        $insert = "INSERT INTO NPCSystem (NPC, Text, Datum, Land, Training, Ninkriegt, Passiertemit, TP)
                              VALUES ('" . $jouninAntrag->Leiter . "', 'Training per System: " . $User->name . " erhält " . $Antrag->Name . $takes . "','" . $Dateingert . "', '" . $User->Heimatdorf . "',
                              '" . $User->id . " " . $Jutsuname . " 1', '" . $User->id . "', '1', '1')";
                        mysql_query($insert);
                        echo "Das Training wurde eingetragen.<br><br>";
                    }
                } else {
                    echo "Dieses Training ist dir noch nicht gestattet.<br><br>";
                }
            }
        }
        //HATE, SO MUCH HATE ENDE

        echo "Du hast noch noch <b><u>$dorfs2->CenterPunkte</u></b> Centerpunkte.
        <br>Du besitzt momentan <b><u>" . $dorfs2->Trainingspunkte . "</u></b> TP.<br /><br>Folgende Anträge hast du gestellt:<br><br>
        <table border='0' width='100%'>
        <tr>
        <td width='40%'><b>Jutsu/Fähigkeit</b></td>
        <td><b>Dauer</b></td>
        <td><b>TP</b></td>
        <td align='center'><b>Admin</b></td>
        <td align='center'><b>LF</b></td>
        <td><b>Trainieren</b></td>
        </tr>";

        //Scheint alles auszugeben und dabei nochmal alles zu berechnen, total unnötigt
        $timingsen = 0;
        while ($timingsen < 2) {
            if ($timingsen == 0) {
                $Antrage_sql = "SELECT * FROM Trainingsanträge WHERE User = '$c_loged' AND Done = '0' ORDER BY id DESC";
            }
            if ($timingsen == 1) {
                $Antrage_sql = "SELECT * FROM Trainingsanträge WHERE User = '$c_loged' AND Done = '1' ORDER BY id DESC LIMIT 5";
            }
            $Antrage_query = mysql_query($Antrage_sql);
            while ($Antrag = mysql_fetch_object($Antrage_query)) {
                if ($Antrag->Adminkontrolle == 0) {
                    $Admin = "<i>N.V.</i>";
                } elseif ($Antrag->Adminkontrolle == 1) {
                    $Admin = "<font color='#006600'>&#10004;</font>";
                } else {
                    $Admin = "<font color='#660000'>&#10008;</font>";
                }
                if ($Antrag->LFKontrolle == 0) {
                    $LFKo = "<i>N.V.</i>";
                } elseif ($Antrag->LFKontrolle == 1) {
                    $LFKo = "<font color='#006600'>&#10004;</font>";
                } else {
                    $LFKo = "<font color='#660000'>&#10008;</font>";
                }

                if ($Antrag->Art == "Jutsu") {
                    $Dauer = $Antrag->DauerEE;
                    $TP = $Antrag->TPweg;
                } elseif ($Antrag->Art == "Fähigkeit") {
                    $sql = "SELECT * FROM Informationen_Faehs WHERE Tabellenname = '$Antrag->Name'";
                    $query = mysql_query($sql);
                    $Info = mysql_fetch_object($query);
                    $Dauer = $Antrag->DauerEE;
                    $TP = $Antrag->TPweg;
                }
                echo "<tr>
                <td><a href=\"javascript:show('Antrag$Antrag->id');\">";
                if ($Antrag->Art == "Fähigkeit") {
                    echo "$Info->Name";
                } else {
                    echo "$Antrag->Name";
                }
                echo " ($Antrag->Stufe)</a>";
                echo "</td>
                <td>$Dauer Tag(e)</td>
                <td>$TP</td>
                <td align='center'>$Admin</td>
                <td align='center'>$LFKo</td>
                <td>";
                if ($Antrag->Done == 1) {
                    echo "Genutzt!";
                } else {
                    if ($Antrag->Adminkontrolle == 1 and $Antrag->LFKontrolle == 1) {
                        echo "<a href='?TrainSpezi=$Antrag->id'>Trainieren</a>";
                    } else {
                        echo "Nicht möglich";
                    }

                    echo "<br><a href='?LoeschSpez=$Antrag->id'>Löschen</a>";
                }
                $Antrag->Adminkommentar = nl2br($Antrag->Adminkommentar);
                $Antrag->LFKommentar = nl2br($Antrag->LFKommentar);
                echo "</td>
                </tr>
                <tr>
                <td colspan='8'>
                <div id='Antrag$Antrag->id'>";
                if ($Antrag->Adminkommentar != "") {
                    echo "<b>Admin-Kommentar:</b><br>$Antrag->Adminkommentar</div>";
                }
                if ($Antrag->LFKommentar != "") {
                    echo "<b>LF-Kommentar:</b><br>$Antrag->LFKommentar</div>";
                }

                echo "</td>
                </tr>";
                echo "<style>#Antrag$Antrag->id { display:none; }</style>";
            }
            $timingsen += 1;
        }
        //Scheint alles auszugeben und dabei nochmal alles zu berechnen, total unnötigt - Ende
        echo "</table><br>";

        if ($dorfs2->CenterPunkte > 0) {
            $query = mysql_query("SELECT COUNT(*) as count FROM Trainingsanträge WHERE User = '$c_loged' AND `done` = 0");
            $num = (int)mysqli_fetch_column($query);

            if ($num > 0) {
                echo 'Du hast bereits ein oder mehrere Centertrainings beantragt, bitte trainiere oder l&ouml;sche dieses, bevor du ein neues beantragst.';
            } else {
                echo "
                    <script language='javascript'>
                    function update(currentValue) {
                        document.getElementById('currentValue').style.display = block;
                    }
                    </script>

                    <form method='POST' action='?Antragmach=1'>
                    <b>Neuer Antrag auf Training per Script*:</b><br><br>
                    <table border='0' width='90%'>
                    <tr>
                    <td width='20%'><b>Art:</b></td>
                    <td>
                    <select onchange='' name='ArtSpez'>
                    <option>Jutsu
                    <option>Fähigkeit
                    </select></td>
                    <td><div id='test'></div></td>
                    </tr>
                    <tr>
                    <td><b>Jutsu</b>
                    </td>
                    <br>
                    <td><select name='JutsuSpez'>";

                $sql = "SELECT * FROM Jutsu WHERE (Clan = '' OR Clan = '$dorfs2->Clan')$zusatz2 AND Jutsutyp != 'Sonstiges' ORDER BY Jutsutyp DESC, Name";
                $query = mysql_query($sql);
                $Artvorher = "";
                while ($Jutsu = mysql_fetch_object($query)) {
                    if ($Jutsu->Taijutsu > $dorfs2->Taijutsu || $Jutsu->Ninjutsu > $dorfs2->Ninjutsu || $Jutsu->Genjutsu > $dorfs2->Genjutsu) {
                        continue;
                    }

                    $Wert = $Jutsuk[$Jutsu->Name] ?? 0;

                    if ($Wert >= 1) {
                        continue;
                    }

                    $Jutsu->Name = str_replace("abba", " ", $Jutsu->Name);
                    if ($tps->thisJutsuOk($Jutsu, $dorfs2, $Faehigks->Siegelkunst) !== false) {
                        if ($Jutsu->Jutsutyp != $Artvorher) {
                            echo "<option>--- $Jutsu->Jutsutyp ---</option>";
                            $Artvorher = $Jutsu->Jutsutyp;
                        }

                        $Jutsurang = $Jutsu->Taijutsu;
                        if ($Jutsurang < $Jutsu->Ninjutsu) {
                            $Jutsurang = $Jutsu->Ninjutsu;
                        }
                        if ($Jutsurang < $Jutsu->Genjutsu) {
                            $Jutsurang = $Jutsu->Genjutsu;
                        }
                        if ($Jutsurang < 2) {
                            $Jutsurangs = "E-Rang";
                        } elseif ($Jutsurang < 3) {
                            $Jutsurangs = "D-Rang";
                        } elseif ($Jutsurang < 5) {
                            $Jutsurangs = "C-Rang";
                        } elseif ($Jutsurang < 7) {
                            $Jutsurangs = "B-Rang";
                        } elseif ($Jutsurang < 9) {
                            $Jutsurangs = "A-Rang";
                        } else {
                            $Jutsurangs = "S-Rang";
                        }

                        if ($Jutsu->Element != "Keins") {
                            $Jutsurangs = "$Jutsurangs - $Jutsu->Element";
                        }
                        echo "<option value='$Jutsu->Name'>$Jutsu->Name ($Jutsurangs)";

                        $Jutsu->Name = str_replace(" ", "abba", $Jutsu->Name);
                        $pos = strpos($LandVerbotene->VerboteneJutsu, "|$Jutsu->Name|");
                        if ($pos === false) {
                        } else {
                            echo " - VERBOTEN";
                        }

                        if ($Jutsu->Eigenejutsu == 1) {
                            echo " - Eigenentwicklung";
                        }
                    }
                }
                echo "</td></tr><tr>
                    <td><b>Fähigkeiten</b>
                    </td>
                    <td><select name='FaehigkeitSpezi'>";
                $sql = "SELECT * FROM Fähigkeiten WHERE id = '$dorfs->id'";
                $query = mysql_query($sql);
                $Jutsuk = mysql_fetch_object($query);

                $sql = "SELECT * FROM Informationen_Faehs WHERE id > '0' AND Verstecken = '0' ORDER BY Name";
                $query = mysql_query($sql);
                while ($Faehigkeiter = mysql_fetch_object($query)) {
                    $Faehname = $Faehigkeiter->Tabellenname;
                    $Werthast = (int)$Jutsuk->$Faehname;

                    if ($Werthast < 4) {
                        $Wertwird = $Werthast + 1;
                        if ($Wertwird <= $Faehigkeiter->Maxwert && $Wertwird <= 4) {
                            if ($dorfs2->Niveau < 3 && $Wertwird > 2) {
                            } elseif ($dorfs2->Niveau < 4 && $Wertwird > 3) {
                            } elseif ((!in_array($Faehigkeiter->id, $kampffaehAusnahmen)
                                    && $Faehigkeiter->Bereich == 'Kampf')
                                && ($dorfs2->Taijutsu < 6 ||
                                    ($dorfs2->Taijutsu < 8 && $Wertwird > 2)
                                    ||
                                    ($dorfs2->Taijutsu < 10 && $Wertwird > 3))) {
                            } else {
                                //Chuunin nur bis Stufe 3

                                //Genin nur bis Stufe 2
                                echo "<option value='$Faehigkeiter->Tabellenname'>$Faehigkeiter->Name $Wertwird";
                            }
                        }
                    }
                }

                echo "</select>
                </td>
                </tr>
                <tr>
                <td colspan='4'>
                * Kosten und weitere Informationen zum Training werden nach dem Erstellen des Antrags einsehbar. Dieses Script unterliegt bestimmten Regeln,
                <a href='https://wiki.narutorpg.de/index.php?title=Center-Training'>die ihr im Wiki einsehen könnt</a>.<br>
                <input type='submit' value='Antrag stellen'>
                </td>
                </tr>
                </table>
                </form>";
            }
        }
    }
}
echo "</td></tr></table>";

get_footer();
