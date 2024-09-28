<?php

include(__DIR__ . "/../Menus/layout1.inc");

$dorfs = nrpg_get_current_user();
$dorfs2 = nrpg_get_current_character();
$pdo = nrpg_get_database();

$query = $pdo->query("SELECT * FROM allgdata WHERE id = 1 LIMIT 1");
$Allgdata = $query->fetchObject();

$time = time();
$EE_query = mysql_query("SELECT id, lastadmin, Lastpm, Ninja, Topic FROM X_Jutsueintrag WHERE Aktiv = 1 AND lastadmin > lastuser ORDER BY id DESC");
while ($EE = mysql_fetch_object($EE_query)) {
    $Unbeant = $time - $EE->lastadmin;
    $Unbeantwortseit = $time - $EE->lastadmin;
    $Tage = floor($Unbeantwortseit / (3600 * 24));

    if ($Tage < $Allgdata->TageStandby) {
        if ($EE->Lastpm > 0 and $EE->Lastpm > $EE->lastadmin) {
            $Unbeant = $time - $EE->Lastpm;
        }

        $Zeitmax = $Allgdata->Tagenachricht * 3600 * 24;
        if ($Unbeant > $Zeitmax) {
            (new PrivateMessage())
                ->to($EE->Ninja)
                ->subject("Eigenentwicklung $EE->Topic bereits länger unbeantwortet")
                ->body("Deine Eigenentwicklung $EE->Topic wurde nun bereits seit $Tage Tag(en) nicht von dir beantwortet. Wenn sie nach $Allgdata->TageStandby Tag(en) nicht beantwortet wurde wird sie automatisch in den inaktiven Modus verschoben.")
                ->send();
            mysql_query("UPDATE X_Jutsueintrag SET Lastpm = '$time' WHERE id = '$EE->id'");
        }
    } else {
        (new PrivateMessage())
            ->to($EE->Ninja)
            ->subject("$EE->Topic nun inaktiv")
            ->body("Deine Eigenentwicklung $EE->Topic wurde auf Grund fehlender Aktivität von deiner Seite ($Allgdata->TageStandby Tage) automatisch in den inaktiven Modus verschoben.")
            ->send();
        mysql_query("UPDATE X_Jutsueintrag SET Warteschlange = 0, Aktiv = 0, Inaktiv = 1 WHERE id = '$EE->id'");
    }
}


$query33 = $pdo->query("SELECT COUNT(*) FROM X_Jutsueintrag WHERE Zustand = 0 AND Entwickende = 0 AND Aktiv = 1");
$Zahl2 = $query33->fetchColumn();

while ($Zahl2 < $Allgdata->MaxEEs) {
    $query = $pdo->query("SELECT id, Ninja, Topic FROM X_Jutsueintrag WHERE Warteschlange > 0 AND Inaktiv = 0 ORDER BY Warteschlange, id LIMIT 1");
    $NeueEE = $query->fetchObject();

    if (!is_object($NeueEE) || $NeueEE->id < 1) {
        $Zahl2 = $Allgdata->MaxEEs;
    } else {
        (new PrivateMessage())
            ->to($NeueEE->Ninja)
            ->subject("$NeueEE->Topic ab jetzt aktiv")
            ->body("Deine Eigenentwicklung $NeueEE->Topic ist ab jetzt nicht mehr in der Warteschleife und steht zur Bearbeitung durch die Regel-Co-Administratoren bereit.")
            ->send();

        $pdo->exec("UPDATE X_Jutsueintrag SET Aktiv = 1, AusStandby = 0, Warteschlange = 0 WHERE id = '$NeueEE->id'");
        $Zahl2 += 1;
    }
}

$tps = new tpKosten();
$query = mysql_query("SELECT * FROM Jutsuk WHERE id = '$dorfs2->id' LIMIT 1");
$u_Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
$query = mysql_query("SELECT * FROM Fähigkeiten WHERE id = '$dorfs2->id'");
$u_Fähigkeiten = mysql_fetch_array($query, MYSQL_ASSOC);
$TPausgegeben = $tps->tpBackGesamt($dorfs2, $u_Jutsu, $u_Fähigkeiten);
$EETPausgegeben = $tps->howMuchRAllEEs($dorfs2, $u_Jutsu);
$query = mysql_query("SELECT * FROM allgdata WHERE id = 1");
$Allgdata = mysql_fetch_object($query);
$sql = "SELECT COUNT( `id` ) as cnt
FROM `Missionen`
WHERE `Ninja` LIKE '%|$dorfs2->id|%' AND `Abschlusszeit` > ( UNIX_TIMESTAMP() -60 *60 *24 *356 ) ";
$query = mysql_query($sql);
$MissisLastYear = mysql_fetch_array($query, MYSQL_ASSOC);
$MissisLastYear = $MissisLastYear['cnt'];

include(__DIR__ . "/../layouts/Overview/Overview1.php");

echo "<tr><td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'><br>";

$ofgenin = (int)filter_input(INPUT_GET, 'ofgenin', FILTER_SANITIZE_NUMBER_INT);
if ($ofgenin != 0) {
    $query = mysql_query("SELECT id FROM NPC WHERE User = '$dorfs->id'");
    $Umayourgenin = mysql_fetch_object($query);
    if (is_object($Umayourgenin) && $Umayourgenin->id > 0) {
        $dorfs2->Rang = "Aka";
    }
}

if ($dorfs->CoAdmin > 0 and $dorfs2->Rang == "Akademist") {
    $dorfs2->Rang = "Aka";
}

if ($dorfs2->Rang != "Akademist" and $dorfs2->Rang != "") {
    $bistnjouninvonwem = 0;

    if ($ofgenin > 0) {
        $query = mysql_query("SELECT Team, id, name FROM user WHERE id = '$ofgenin'");
        $Umayourgenin = mysql_fetch_object($query);
        $query = mysql_query("SELECT Leiter FROM Teams WHERE id = '$Umayourgenin->Team'");
        $Gruppe = mysql_fetch_object($query);
        if ($Gruppe->Leiter == \NarutoRPG\SessionHelper::getUserId()) {
            $bistnjouninvonwem = 1;
        }
    }

    if ($Ubersicht == 1) {
        if ($bistnjouninvonwem == 1) {
            echo "<u><b>Eigenentwicklungen von $Umayourgenin->name</b></u><br><br>";
        } else {
            echo "<u><b><a href='https://wiki.narutorpg.de/index.php?title=Eigenentwicklung'>Eigenentwicklungen</a></b></u><br>";

            $sql2 = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Ninja != '$c_loged' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0'";
            $query2 = mysql_query($sql2);
            $Mission = mysql_fetch_row($query2);
            echo "Momentan sind $Mission[0] Vorschläge neben deinen Vorschlägen in Bearbeitung.<br>";


            $open = 1;
            if ($open != 0) {
                $sql2 = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Eingetragen = '0' AND Zustand != '2'";
                $query2 = mysql_query($sql2);
                $Mission = mysql_fetch_row($query2);
                if ($Mission[0] < 1) {
                    if ($TPausgegeben < 80) {
                        echo 'Du musst mindestens 80 TP ausgeben, ehe du eine EE beantragen kannst.';
                    } elseif ($dorfs->is_mult === 1 && $dorfs->multFrei != 1) {
                        echo 'Du kannst keine EE beantragen, da du einen inoffiziellen Multi hast, der kürzer als dieser Account existiert. Falls du die <a href="https://wiki.narutorpg.de/index.php?title=Multiusing#Weiterkommen_mit_dem_eigenen_Charakter">Bedingungen</a> dafür erfüllst einen Multi für Eigenentwicklungen freischalten zu lassen stelle eine <a href="/Anfragen.php?Ubersicht=1">Anfrage</a>, um die Freischaltung durchführen zu lassen.';
                    } elseif (($TPausgegeben - $EETPausgegeben) < 400 && ($EETPausgegeben / $TPausgegeben > 0.15)) {
                        echo 'Du hast unter 400 TP für nicht-EEs ausgegeben und kannst daher maximal 15% deiner ausgegebenen TP in EEs investieren.';
                    } elseif ($MissisLastYear < 2 || $dorfs2->Inaktiviteat == 1) {
                        echo 'Du musst mindestens 2 Missionen im letzten Jahr absolviert haben und aktiv sein, um eine EE beantragen zu können.';
                    } else {
                        echo "<br><a href='?Entwickeln=1'>Einen Entwicklungsvorschlag eintragen</a><br><br>";
                    }
                } else {
                    echo "<br><i>Du hast derzeit bereits eine Eigenentwicklung in Bearbeitung oder die fertige Entwicklung noch nicht umgesetzt.</i><br><br>";
                }
            } else {
                echo 'Derzeit können keine neuen EEs beantragt werden. Bitte beachte die Neuerungen, um zu erfahren, wann dies wieder möglich sein wird';
            }
        }
        if ($bistnjouninvonwem == 1) {
            echo "<b>Jutsuentwicklungen von $Umayourgenin->name:</b><br>";
        } else {
            echo "<b>Deine Jutsuentwicklungen:</b><br>";
        }
        echo "<table border='0' width='100%'>";
        echo "<tr>";
        echo "<td width='40%'><b>Jutsuvorschlag</b></td>";
        echo "<td width='15%'><b>Zugeteilt</b></td>";
        echo "<td width='10%'><b>Bearbeiten</b></td>";
        echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
        echo "</tr>";
        if ($bistnjouninvonwem == 1) {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$Umayourgenin->id' AND Art = '1' ORDER BY Zustand, Entwickende, lastact";
        } else {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Art = '1' ORDER BY Zustand, Entwickende, lastact";
        }
        $query2 = mysql_query($sql2);
        while ($Mission = mysql_fetch_object($query2)) {
            $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
            $query = mysql_query($sql);
            $Nin = mysql_fetch_object($query);
            $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
            $query = mysql_query($sql);
            $lastPoster = mysql_fetch_object($query);
            echo "<tr>";
            echo "<td>$Mission->Topic";
            if ($Mission->Stufe != "") {
                echo " ($Mission->Stufe)";
            }
            if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#660000'>Abgelehnt</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#006600'>Akzeptiert</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                echo " - <b><font color='#006600'>Eingetragen</font></b>";
            }

            if ($Mission->Warteschlange > 0) {
                echo " - <b><font color='#CC6600'>Warteschlange</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 0 and $Mission->Inaktiv > 0) {
                echo " - <b><font color='#CC6600'>Standby</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 1) {
                echo " - <b><font color='#CC6600'>In Bearbeitung</font></b>";
            }

            if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
            }
            if ($Mission->Anderungswunsch > 0) {
                echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
            }
            echo "</td>";
            echo "<td>";
            if ($Mission->Adminwilldie != "") {
                $ROFL = explode(",", $Mission->Adminwilldie);
                $Zahl = 0;
                while ($ROFL[$Zahl] != "") {
                    if ($Zahl > 0) {
                        echo ", ";
                    }
                    $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                    $query = mysql_query($sql);
                    $Nin2 = mysql_fetch_object($query);
                    echo "$Nin2->name";
                    $Zahl += 1;
                }
            } else {
                echo "Niemand";
            }
            echo "</td>";
            echo "<td><a href='?Betrachtedu=$Mission->id";
            if ($bistnjouninvonwem == 1) {
                echo "&ofgenin=$ofgenin";
            }
            echo "'>";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "<font color='#cc0033'>";
            }
            echo "Betrachten";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "</font>";
            }
            echo "</a></td>";
            echo "<td>";
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                echo "-";
            } else {
                $Zeit = time() - $Mission->lastact;
                $Stunden = 0;
                $Tage = 0;
                while ($Zeit >= 86400) {
                    $Zeit -= 86400;
                    $Tage += 1;
                }
                while ($Zeit >= 3600) {
                    $Zeit -= 3600;
                    $Stunden += 1;
                }
                echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><br>";
        if ($bistnjouninvonwem == 1) {
            echo "<b>Waffenentwicklungen von $Umayourgenin->name:</b><br>";
        } else {
            echo "<b>Deine Waffenentwicklungen:</b><br>";
        }
        echo "<table border='0' width='100%'>";
        echo "<tr>";
        echo "<td width='40%'><b>Waffenvorschlag</b></td>";
        echo "<td width='15%'><b>Zugeteilt</b></td>";
        echo "<td width='10%'><b>Bearbeiten</b></td>";
        echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
        echo "</tr>";
        if ($bistnjouninvonwem == 1) {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$Umayourgenin->id' AND Art = '2' ORDER BY Zustand, Entwickende, lastact";
        } else {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Art = '2' ORDER BY Zustand, Entwickende, lastact";
        }
        $query2 = mysql_query($sql2);
        while ($Mission = mysql_fetch_object($query2)) {
            $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
            $query = mysql_query($sql);
            $Nin = mysql_fetch_object($query);
            $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
            $query = mysql_query($sql);
            $lastPoster = mysql_fetch_object($query);
            echo "<tr>";
            echo "<td>$Mission->Topic";
            if ($Mission->Stufe != "") {
                echo " ($Mission->Stufe)";
            }
            if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#660000'>Abgelehnt</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#006600'>Akzeptiert</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                echo " - <b><font color='#006600'>Eingetragen</font></b>";
            }

            if ($Mission->Warteschlange > 0) {
                echo " - <b><font color='#CC6600'>Warteschlange</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 0 and $Mission->Inaktiv > 0) {
                echo " - <b><font color='#CC6600'>Standby</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 1) {
                echo " - <b><font color='#CC6600'>In Bearbeitung</font></b>";
            }

            if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
            }
            if ($Mission->Anderungswunsch > 0) {
                echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
            }
            echo "</td>";
            echo "<td>";
            if ($Mission->Adminwilldie != "") {
                $ROFL = explode(",", $Mission->Adminwilldie);
                $Zahl = 0;
                while ($ROFL[$Zahl] != "") {
                    if ($Zahl > 0) {
                        echo ", ";
                    }
                    $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                    $query = mysql_query($sql);
                    $Nin2 = mysql_fetch_object($query);
                    echo "$Nin2->name";
                    $Zahl += 1;
                }
            } else {
                echo "Niemand";
            }
            echo "</td>";
            echo "<td><a href='?Betrachtedu=$Mission->id";
            if ($bistnjouninvonwem == 1) {
                echo "&ofgenin=$ofgenin";
            }
            echo "'>";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "<font color='#cc0033'>";
            }
            echo "Betrachten";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "</font>";
            }
            echo "</a></td>";
            echo "<td>";
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                echo "-";
            } else {
                $Zeit = time() - $Mission->lastact;
                $Stunden = 0;
                $Tage = 0;
                while ($Zeit >= 86400) {
                    $Zeit -= 86400;
                    $Tage += 1;
                }
                while ($Zeit >= 3600) {
                    $Zeit -= 3600;
                    $Stunden += 1;
                }
                echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><br>";

        if ($bistnjouninvonwem == 1) {
            echo "<b>Sonstige Entwicklungen von $Umayourgenin->name:</b><br>";
        } else {
            echo "<b>Deine sonstigen Entwicklungen:</b><br>";
        }
        echo "<table border='0' width='100%'>";
        echo "<tr>";
        echo "<td width='40%'><b>Vorschlag</b></td>";
        echo "<td width='15%'><b>Zugeteilt</b></td>";
        echo "<td width='10%'><b>Bearbeiten</b></td>";
        echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
        echo "</tr>";
        if ($bistnjouninvonwem == 1) {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$Umayourgenin->id' AND Art = '3' ORDER BY Zustand, Entwickende, lastact";
        } else {
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Art = '3' ORDER BY Zustand, Entwickende, lastact";
        }
        $query2 = mysql_query($sql2);
        while ($Mission = mysql_fetch_object($query2)) {
            $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
            $query = mysql_query($sql);
            $Nin = mysql_fetch_object($query);
            $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
            $query = mysql_query($sql);
            $lastPoster = mysql_fetch_object($query);
            echo "<tr>";
            echo "<td>$Mission->Topic";
            if ($Mission->Stufe != "") {
                echo " ($Mission->Stufe)";
            }
            if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#660000'>Abgelehnt</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                echo " - <b><font color='#006600'>Akzeptiert</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                echo " - <b><font color='#006600'>Eingetragen</font></b>";
            }

            if ($Mission->Warteschlange > 0) {
                echo " - <b><font color='#CC6600'>Warteschlange</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 0 and $Mission->Inaktiv > 0) {
                echo " - <b><font color='#CC6600'>Standby</font></b>";
            }
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 0 and $Mission->Aktiv == 1) {
                echo " - <b><font color='#CC6600'>In Bearbeitung</font></b>";
            }

            if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
            }
            if ($Mission->Anderungswunsch > 0) {
                echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
            }
            echo "</td>";
            echo "<td>";
            if ($Mission->Adminwilldie != "") {
                $ROFL = explode(",", $Mission->Adminwilldie);
                $Zahl = 0;
                while ($ROFL[$Zahl] != "") {
                    if ($Zahl > 0) {
                        echo ", ";
                    }
                    $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                    $query = mysql_query($sql);
                    $Nin2 = mysql_fetch_object($query);
                    echo "$Nin2->name";
                    $Zahl += 1;
                }
            } else {
                echo "Niemand";
            }
            echo "</td>";
            echo "<td><a href='?Betrachtedu=$Mission->id";
            if ($bistnjouninvonwem == 1) {
                echo "&ofgenin=$ofgenin";
            }
            echo "'>";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "<font color='#cc0033'>";
            }
            echo "Betrachten";
            if ($Mission->lastuser <= $Mission->lastadmin) {
                echo "</font>";
            }
            echo "</a></td>";
            echo "<td>";
            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                echo "-";
            } else {
                $Zeit = time() - $Mission->lastact;
                $Stunden = 0;
                $Tage = 0;
                while ($Zeit >= 86400) {
                    $Zeit -= 86400;
                    $Tage += 1;
                }
                while ($Zeit >= 3600) {
                    $Zeit -= 3600;
                    $Stunden += 1;
                }
                echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } elseif ($Entwickeln == 1) {
        $sql2 = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Eingetragen = '0' AND Zustand != '2'";
        $query2 = mysql_query($sql2);
        $Mission = mysql_fetch_row($query2);
        if ($Mission[0] < 1) {
            if ($TPausgegeben < 80) {
                echo 'Du musst mindestens 80 TP ausgeben, ehe du eine EE beantragen kannst.';
            } elseif ($dorfs->is_mult === 1 && $dorfs->multFrei != 1) {
                echo 'Du kannst keine EE beantragen, da du einen inoffiziellen Multi hast, der kürzer als dieser Account existiert.';
            } elseif (($TPausgegeben - $EETPausgegeben) < 400 && ($EETPausgegeben / $TPausgegeben > 0.15)) {
                echo 'Du hast unter 400 TP für nicht-EEs ausgegeben und kannst daher maximal 15% deiner ausgegebenen TP in EEs investieren.';
            } elseif ($MissisLastYear < 2 || $dorfs2->Inaktiviteat == 1) {
                echo 'Du musst mindestens 2 Missionen im letzten Jahr absolviert haben und aktiv sein, um eine EE beantragen zu können.';
            } else {
                echo "<b>Vorgehen bei Entwicklungen</b><br><br>
									<table border='0' width='90%'>
									<tr>
									<td>
									Auf Grund der Tatsache, dass sehr oft Entwicklungen zurückgezogen werden oder sich als zu teuer für den Spieler erweisen und damit die gesamte Ausarbeitung umsonst war solltet ihr
									folgende Punkte bereits zu Beginn eurer Entwicklung erfragen:<br>
									1. Informiert euch, ob die Entwicklung in der Art möglich ist.<br>
									2. Informiert euch über die TP Kosten der Entwicklung.<br>
									3. Informiert euch darüber, ob ihr die Anforderungen für die Entwicklung erfüllt.<br>
									4. Arbeitet eure Entwicklung aus. \"Ich möchte in die und die Richtung irgendwas haben\" reicht nicht aus. Die Administration ist nicht dafür zuständig euch eine komplette
									Ausarbeitung zu liefern.<br>
									5. Formuliert eure Entwicklung deutlich. Legt sie vorher anderen Spielern vor und fragt, ob Sie diese verstehen.<br>
									6. Haltet RP-Anteile knapp und präzise. Sagt wie das im RP ablaufen soll, aber erzählt uns nicht eine ellenlange Geschichte. Die lenkt nur vom für uns wesentlichen ab. Wenn die
									Geschichte komplizierter sein soll, dann muss sie per RP-Erlaubnis gehandhabt werden.<br>
									7. Nutzt BB-Codes und teilt die EE übersichtlich ein! Das erleichtert uns das Verständnis und verkürzt damit die Bearbeitungszeit!<br>
									8. Kein wall of text bitte. Schreibt eure Entwicklungen präzise und nutzt lieber Stichworte als ellenlange Texte, durch die man erstmal durchsteigen muss.<br>
									</td>
									</tr>
									</table>
									<br><br>";

                echo "<SCRIPT language=JavaScript type=text/javascript>
									<!--
									// bbCode control by
									// subBlue design
									// www.subBlue.com

									// Startup variables
									var imageTag = false;
								var theSelection = false;

								// Check for Browser & Platform for PC & IE specific bits
								// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
								var clientPC = navigator.userAgent.toLowerCase(); // Get client info
								var clientVer = parseInt(navigator.appVersion); // Get browser version

								var is_ie = ((clientPC.indexOf(\"msie\") != -1) && (clientPC.indexOf(\"opera\") == -1));
								var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
										&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
										&& (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
								var is_moz = 0;

								var is_win = ((clientPC.indexOf(\"win\")!=-1) || (clientPC.indexOf(\"16bit\") != -1));
								var is_mac = (clientPC.indexOf(\"mac\")!=-1);

								// Helpline messages
								b_help = \"Text in fett: [b]Text[/b] (alt+b)\";
								i_help = \"Text in kursiv: [i]Text[/i] (alt+i)\";
								u_help = \"Unterstrichener Text: [u]Text[/u] (alt+u)\";
								q_help = \"Zitat: [quote]Text[/quote] (alt+q)\";
								c_help = \"Code anzeigen: [code]Code[/code] (alt+c)\";
								l_help = \"Liste: [list]Text[/list] (alt+l)\";
								o_help = \"Geordnete Liste: [list=]Text[/list] (alt+o)\";
								p_help = \"Bild einfügen: [img]http://URL_des_Bildes[/img] (alt+p)\";
								w_help = \"URL einfügen: [url]http://URL[/url] oder [url=http://url]URL Text[/url] (alt+w)\";
								t_help = \"Spoiler einfügen: [SPOILER]Text[SPOILER]\";
								a_help = \"Alle offenen BBCodes schließen\";
								s_help = \"Schriftfarbe: [color=red]Text[/color] Tipp: Du kannst ebenfalls color=#FF0000 benutzen\";
								f_help = \"Schriftgröße: [size=x-small]Kleiner Text[/size]\";

								// Define the bbCode tags
								bbcode = new Array();
								bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[SPOILER]','[SPOILER]');
								imageTag = false;

								// Shows the help messages in the helpline window
								function helpline(help) {
									document.post.helpbox.value = eval(help + \"_help\");
								}


								// Replacement for arrayname.length property
								function getarraysize(thearray) {
									for (i = 0; i < thearray.length; i++) {
										if ((thearray[i] == \"undefined\") || (thearray[i] == \"\") || (thearray[i] == null))
											return i;
									}
									return thearray.length;
								}

								// Replacement for arrayname.push(value) not implemented in IE until version 5.5
								// Appends element to the array
								function arraypush(thearray,value) {
									thearray[ getarraysize(thearray) ] = value;
								}

								// Replacement for arrayname.pop() not implemented in IE until version 5.5
								// Removes and returns the last element of an array
								function arraypop(thearray) {
									thearraysize = getarraysize(thearray);
									retval = thearray[thearraysize - 1];
									delete thearray[thearraysize - 1];
									return retval;
								}


								function checkForm() {

									formErrors = false;

									if (document.post.Ausarbeitung.value.length < 2) {
										formErrors = \"Du musst zu deinem Beitrag einen Text eingeben.\";
									}

									if (formErrors) {
										alert(formErrors);
										return false;
									} else {
										bbstyle(-1);
										//formObj.preview.disabled = true;
										//formObj.submit.disabled = true;
										return true;
									}
								}

								function emoticon(text) {
									var txtarea = document.post.Ausarbeitung;
									text = ' ' + text + ' ';
									if (txtarea.createTextRange && txtarea.caretPos) {
										var caretPos = txtarea.caretPos;
										caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
										txtarea.focus();
									} else {
										txtarea.value  += text;
										txtarea.focus();
									}
								}

								function bbfontstyle(bbopen, bbclose) {
									var txtarea = document.post.Ausarbeitung;

									if ((clientVer >= 4) && is_ie && is_win) {
										theSelection = document.selection.createRange().text;
										if (!theSelection) {
											txtarea.value += bbopen + bbclose;
											txtarea.focus();
											return;
										}
										document.selection.createRange().text = bbopen + theSelection + bbclose;
										txtarea.focus();
										return;
									}
									else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
									{
										mozWrap(txtarea, bbopen, bbclose);
										return;
									}
									else
									{
										txtarea.value += bbopen + bbclose;
										txtarea.focus();
									}
									storeCaret(txtarea);
								}


								function bbstyle(bbnumber) {
									var txtarea = document.post.Ausarbeitung;

									txtarea.focus();
									donotinsert = false;
									theSelection = false;
									bblast = 0;

									if (bbnumber == -1) { // Close all open tags & default button names
										while (bbcode[0]) {
											butnumber = arraypop(bbcode) - 1;
											txtarea.value += bbtags[butnumber + 1];
											buttext = eval('document.post.addbbcode' + butnumber + '.value');
											eval('document.post.addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');
										}
										imageTag = false; // All tags are closed including image tags :D
										txtarea.focus();
										return;
									}

									if ((clientVer >= 4) && is_ie && is_win)
									{
										theSelection = document.selection.createRange().text; // Get text selection
										if (theSelection) {
											// Add tags around selection
											document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
											txtarea.focus();
											theSelection = '';
											return;
										}
									}
									else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
									{
										mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
										return;
									}

									// Find last occurance of an open tag the same as the one just clicked
									for (i = 0; i < bbcode.length; i++) {
										if (bbcode[i] == bbnumber+1) {
											bblast = i;
											donotinsert = true;
										}
									}

									if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
										while (bbcode[bblast]) {
											butnumber = arraypop(bbcode) - 1;
											txtarea.value += bbtags[butnumber + 1];
											buttext = eval('document.post.addbbcode' + butnumber + '.value');
											eval('document.post.addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');
											imageTag = false;
										}
										txtarea.focus();
										return;
									} else { // Open tags

										if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
											txtarea.value += bbtags[15];
											lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
											document.post.addbbcode14.value = \"Img\";	// Return button back to normal state
												imageTag = false;
										}

										// Open tag
										txtarea.value += bbtags[bbnumber];
										if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
										arraypush(bbcode,bbnumber+1);
										eval('document.post.addbbcode'+bbnumber+'.value += \"*\"');
										txtarea.focus();
										return;
									}
									storeCaret(txtarea);
								}

								// From http://www.massless.org/mozedit/
								function mozWrap(txtarea, open, close)
								{
									var selLength = txtarea.textLength;
									var selStart = txtarea.selectionStart;
									var selEnd = txtarea.selectionEnd;
									if (selEnd == 1 || selEnd == 2)
										selEnd = selLength;

									var s1 = (txtarea.value).substring(0,selStart);
									var s2 = (txtarea.value).substring(selStart, selEnd)
										var s3 = (txtarea.value).substring(selEnd, selLength);
									txtarea.value = s1 + open + s2 + close + s3;
									return;
								}

								// Insert at Claret position. Code from
								// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
								function storeCaret(textEl) {
									if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
								}

								//-->
								</SCRIPT>";

                echo "<form name=post method='POST' action='?Entwickle=1'><b>Art der Entwicklung</b>:
									<select name='Artvors'>
									<option value='1'>Jutsuentwicklung<option value='2'>Itementwicklungen<option value='3'>Sonstige Entwicklung</select><br>
									<b>Genauer:</b> <select name='Artgenau'>
									<option value='1'>Taijutsu
									<option value='2'>Ninjutsu
									<option value='3'>Genjutsu
									<option value='10'>Siegel
									<option value='4'>Item mit Chakra-Einfluss
									<option value='5'>Item auf Technik-Basis
									<option value='6'>Item mit Technik und Chakra
									<option value='7'>Sonstiges Item (z.B. normale Klinge)
									<option value='8'>Fähigkeit
									<option value='9'>BE/Clan-Jutsu/Fähigkeit
									<option value='11'>Kuchiyose
									</select><br>
									<b>Niveau der Entwicklung:</b> <select name='NiveauVorsch'>
									<option value='1'>E-Rang
									<option value='2'>D-Rang
									<option value='3'>C-Rang
									<option value='4'>B-Rang
									<option value='5'>A-Rang
									<option value='6'>S-Rang
									</select><br>
									<b>Titel der Entwicklung:</b> <input type='text' name='NamederE' size='30' maxlength='50'><br>
									<b>Ausarbeitung des Vorschlags: (z.B. Fähigkeiten der Jutsu etc.)</b><br>

									<TABLE cellSpacing=0 cellPadding=2 width=450 border=0>
									<TBODY>
									<TR vAlign=center align=middle>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('b')\" style=\"FONT-WEIGHT: bold; WIDTH: 30px\" accessKey=b onclick=bbstyle(0) type=button value=\" B \" name=addbbcode0>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('i')\" style=\"WIDTH: 30px; FONT-STYLE: italic\" accessKey=i onclick=bbstyle(2) type=button value=\" i \" name=addbbcode2>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('u')\" style=\"WIDTH: 30px; TEXT-DECORATION: underline\" accessKey=u onclick=bbstyle(4) type=button value=\" u \" name=addbbcode4>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('q')\" style=\"WIDTH: 50px\" accessKey=q onclick=bbstyle(6) type=button value=Quote name=addbbcode6>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('l')\" style=\"WIDTH: 40px\" accessKey=l onclick=bbstyle(10) type=button value=List name=addbbcode10>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('o')\" style=\"WIDTH: 40px\" accessKey=o onclick=bbstyle(12) type=button value=List= name=addbbcode12>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('p')\" style=\"WIDTH: 40px\" accessKey=p onclick=bbstyle(14) type=button value=Img name=addbbcode14>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('w')\" style=\"WIDTH: 40px; TEXT-DECORATION: underline\" accessKey=w onclick=bbstyle(16) type=button value=URL name=addbbcode16>
									</SPAN></TD>
									<TD><SPAN class=genmed><INPUT class=button onmouseover=\"helpline('t')\" style=\"WIDTH: 60px; TEXT-DECORATION: underline\" accessKey=t onclick=bbstyle(18) type=button value=SPOILER name=addbbcode18>
									</SPAN></TD>
									</TR>
									<TR>
									<TD colSpan=9>
									<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=0>
									<TBODY>
									<TR>
									<TD><SPAN class=genmed> Schriftfarbe: <SELECT onmouseover=\"helpline('s')\"
									onchange=\"bbfontstyle('[color=' + this.form.addbbcode22.options[this.form.addbbcode22.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\"
									name=addbbcode22> <OPTION class=genmed
									style=\"COLOR: black; BACKGROUND-COLOR: #fafafa\" value=#444444
									selected>Standard</OPTION> <OPTION class=genmed
									style=\"COLOR: darkred; BACKGROUND-COLOR: #fafafa\"
									value=darkred>Dunkelrot</OPTION> <OPTION class=genmed
									style=\"COLOR: red; BACKGROUND-COLOR: #fafafa\" value=red>Rot</OPTION> <OPTION
									class=genmed style=\"COLOR: orange; BACKGROUND-COLOR: #fafafa\"
									value=orange>Orange</OPTION> <OPTION class=genmed
									style=\"COLOR: brown; BACKGROUND-COLOR: #fafafa\" value=brown>Braun</OPTION>
									<OPTION class=genmed style=\"COLOR: yellow; BACKGROUND-COLOR: #fafafa\"
									value=yellow>Gelb</OPTION> <OPTION class=genmed
									style=\"COLOR: green; BACKGROUND-COLOR: #fafafa\" value=green>Grün</OPTION>
									<OPTION class=genmed style=\"COLOR: olive; BACKGROUND-COLOR: #fafafa\"
									value=olive>Oliv</OPTION> <OPTION class=genmed
									style=\"COLOR: cyan; BACKGROUND-COLOR: #fafafa\" value=cyan>Cyan</OPTION> <OPTION
									class=genmed style=\"COLOR: blue; BACKGROUND-COLOR: #fafafa\"
									value=blue>Blau</OPTION> <OPTION class=genmed
									style=\"COLOR: darkblue; BACKGROUND-COLOR: #fafafa\"
									value=darkblue>Dunkelblau</OPTION> <OPTION class=genmed
									style=\"COLOR: indigo; BACKGROUND-COLOR: #fafafa\" value=indigo>Indigo</OPTION>
									<OPTION class=genmed style=\"COLOR: violet; BACKGROUND-COLOR: #fafafa\"
									value=violet>Violett</OPTION> <OPTION class=genmed
									style=\"COLOR: white; BACKGROUND-COLOR: #fafafa\" value=white>Weiß</OPTION>
									<OPTION class=genmed style=\"COLOR: black; BACKGROUND-COLOR: #fafafa\"
									value=black>Schwarz</OPTION></SELECT>  Schriftgröße:<SELECT
									onmouseover=\"helpline('f')\"
									onchange=\"bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')\"
									name=addbbcode20> <OPTION class=genmed value=7>Winzig</OPTION> <OPTION
									class=genmed value=9>Klein</OPTION> <OPTION class=genmed value=12
									selected>Normal</OPTION> <OPTION class=genmed value=18>Groß</OPTION> <OPTION
									class=genmed value=24>Riesig</OPTION></SELECT> </SPAN></TD>
									<TD noWrap align=right><SPAN class=gensmall><A class=genmed
									onmouseover=\"helpline('a')\" href=\"javascript:bbstyle(-1)\">Tags
									schließen</A></SPAN></TD></TR></TBODY></TABLE></TD></TR>
									<TR>
									<TD colSpan=9><SPAN class=gensmall><INPUT class=helpline
									style=\"FONT-SIZE: 10px; WIDTH: 600px\" maxLength=100 size=60
									value=\"Code anzeigen: [code]Code[/code] (alt+c)\" name=helpbox> </SPAN></TD></TR>
									<TR>
									<TD colSpan=9><SPAN class=gen><TEXTAREA class=post onkeyup=storeCaret(this); style=\"WIDTH: 600; height: 242px;\" onclick=storeCaret(this); tabIndex=3 name=Ausarbeitung rows=15 wrap=virtual cols=35 onselect=storeCaret(this);></TEXTAREA>
									</SPAN></TD></TR></TBODY></TABLE>
									<br>
									<b>Kommentar: (Was ist das \"muss\" dieses Vorschlags, was ist minder wichtig, weitere Gedankengänge, etc.)</b><br>
									<textarea name='Kommentar' style='height: 167px;' rows='10' cols='80'></textarea><br>
									<input type='submit' value='Vorschlag eintragen'></form>";
            }
        } else {
            echo "<br><i>Du hast derzeit bereits eine Eigenentwicklung in Bearbeitung oder die fertige Entwicklung noch nicht umgesetzt.</i><br><br>";
        }
    } elseif ($Entwickle == 1) {
        $query2 = mysql_query("SELECT COUNT(*) FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Eingetragen = '0' AND Zustand != '2'");
        $Mission = mysql_fetch_row($query2);
        if ($Mission[0] < 1) {
            if ($TPausgegeben < 80) {
                echo 'Du musst mindestens 80 TP ausgeben, ehe du eine EE beantragen kannst.';
            } elseif ($dorfs->is_mult === 1 && $dorfs->multFrei != 1) {
                echo 'Du kannst keine EE beantragen, da du einen inoffiziellen Multi hast, der kürzer als dieser Account existiert.';
            } elseif ($Artvors == 1 or $Artvors == 2 or $Artvors == 3) {
                $Ausarbeitung = str_replace("'", "\"", $Ausarbeitung);
                $Ausarbeitung = htmlentities($Ausarbeitung);
                $NamederE = str_replace("'", "\"", $NamederE);
                $NamederE = htmlentities($NamederE);
                $Kommentar = str_replace("'", "\"", $Kommentar);
                $Kommentar = htmlentities($Kommentar);
                $NiveauVorsch = str_replace("'", "\"", $NiveauVorsch);
                $NiveauVorsch = htmlentities($NiveauVorsch);
                $time = time();
                $ins = "INSERT INTO X_Jutsueintrag (Ninja, Art, Waffe, Topic, admintext, usertext, lastuser, lastact, Niveau, Artgenau, Warteschlange) VALUES ('$c_loged', '$Artvors', '$Ausarbeitung', '$NamederE', '', '', '$time', '$time', '$NiveauVorsch', '$Artgenau', '$time')";
                $ins = mysql_query($ins) or die("Fehler");

                $sql = "SELECT id FROM X_Jutsueintrag WHERE Ninja = '$c_loged' AND Waffe = '$Ausarbeitung' AND Topic = '$NamederE' ORDER BY id DESC";
                $query = mysql_query($sql);
                $Eintrag = mysql_fetch_object($query);

                $Datum = date("d.m.Y, H:i");

                $ins = "INSERT INTO X_Posts (Von, Topic, Text, Postdatum) VALUES ('', '$Eintrag->id', '$Ausarbeitung', '$Datum')";
                $ins = mysql_query($ins) or die("Fehler");

                $ins = "INSERT INTO X_Posts (Von, Topic, Text, Postdatum) VALUES ('$c_loged', '$Eintrag->id', '$Kommentar', '$Datum')";
                $ins = mysql_query($ins) or die("Fehler");

                echo "Der Vorschlag wurde eingetragen, in der nächsten Zeit wird sich ein jemand um diesen Vorschlag kümmern, dies kann jedoch eine Weile dauern.<br><a href='?Ubersicht=1'>Zurück</a>";
            }
        }
    } elseif ($Betrachtedu >= 1) {
        if ($bistnjouninvonwem == 1) {
            $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$Betrachtedu' AND Ninja = '$Umayourgenin->id'";
        } else {
            $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$Betrachtedu' AND Ninja = '$c_loged'";
        }
        $query = mysql_query($sql);
        $Vorschlag = mysql_fetch_object($query);
        $sql = "SELECT id, name FROM user WHERE id = '$Vorschlag->Ninja'";
        $query = mysql_query($sql);
        $Nin = mysql_fetch_object($query);
        if ($bistnjouninvonwem == 1) {
            echo "Vorschlag von $Umayourgenin->name: <b>$Vorschlag->Topic</b>";
            if ($Vorschlag->Stufe != "") {
                echo " ($Vorschlag->Stufe)";
            }
            echo "<br><a href='?Ubersicht=1&ofgenin=$ofgenin'>Zurück</a><br><br>";
        } else {
            echo "Dein Vorschlag: <b>$Vorschlag->Topic</b>";
            if ($Vorschlag->Stufe != "") {
                echo " ($Vorschlag->Stufe)";
            }
            echo "<br><a href='?Ubersicht=1'>Zurück</a><br>";
        }

        if ($Vorschlag->Entwickende == 1 and $Vorschlag->Ninja == $dorfs->id) {
            if ($plzchange == 1) {
                $sql = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Anderungswunsch > '0' AND Ninja = '$dorfs->id'";
                $query = mysql_query($sql);
                $Zahl = mysql_fetch_row($query);
                if ($Zahl[0] < 2) {
                    $lasttime = time();
                    if ($Allgdata->Handlungsbedarf == 0) {
                        $lasttime = time();
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                    } elseif ($Allgdata->Handlungsbedarf == 1) {
                        $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '1' ORDER BY Warteschlange DESC";
                        $query = mysql_query($sql);
                        $Letzte = mysql_fetch_object($query);
                        $lasttime = $Letzte->Warteschlange + 1;
                        if ($Letzte->id < 1) {
                            $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '0' ORDER BY Warteschlange";
                            $query = mysql_query($sql);
                            $Letzte = mysql_fetch_object($query);
                            $lasttime = $Letzte->Warteschlange - 500;
                        }
                        if ($lasttime < 1) {
                            $lasttime = time();
                        }
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '1' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                    } elseif ($Allgdata->Handlungsbedarf == 2) {
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '1' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                        mysql_query($up);
                    }
                    if ($lasttime < 1) {
                        $lasttime = time();
                    }

                    $Vorschlag->Anderungswunsch = 1;
                }
            }

            if ($plznochange == 1) {
                $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                mysql_query($up);
                $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                mysql_query($up);
                $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                mysql_query($up);
                $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Vorschlag->id' AND Ninja = '$dorfs->id'";
                mysql_query($up);
                $Vorschlag->Anderungswunsch = 0;
            }

            if ($Vorschlag->Anderungswunsch == 0) {
                $sql = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Anderungswunsch > '0' AND Ninja = '$dorfs->id'";
                $query = mysql_query($sql);
                $Zahl = mysql_fetch_row($query);
                if ($Zahl[0] < 2) {
                    echo "<a href='?Betrachtedu=$Vorschlag->id&plzchange=1'>Änderung erbitten</a><br>";
                }
            } else {
                echo "<a href='?Betrachtedu=$Vorschlag->id&plznochange=1'>Keine Änderung mehr erbitten</a><br>";
            }
        }
        echo "<br>";


        echo "<IFRAME name=Topic frameBorder=0 src='/Includes/EE2/Forum_Topic.php?Topic=$Vorschlag->id' width=100% height=500></iframe>";
        echo "<IFRAME name=Post frameBorder=0 src='/Includes/EE2/Forum_Post.php?Topic=$Vorschlag->id' width=100% height=420></iframe>";
    } elseif ($Bearbeitedu) {
        $sql = "SELECT id, usertext, lastadmin, lastuser FROM X_Jutsueintrag WHERE id = '$Bearbeitedu' AND Ninja = '$c_loged'";
        $query = mysql_query($sql);
        $Vorschlag = mysql_fetch_object($query);
        if ($Vorschlag->id > 0) {
            $Meinungdusa = str_replace("'", "\"", $Meinungdusa);
            $Meinungdusa = htmlentities($Meinungdusa);
            $time = time();
            $Datum = date("d.m.Y, H:i");

            if ($Vorschlag->usertext != "") {
                $newtext = "";
                if ($Vorschlag->lastadmin <= $Vorschlag->lastuser) {
                    $Zahl = 1;
                    $Person = explode("%&%&&&%%", $Vorschlag->usertext);
                    while ($Person[$Zahl] != "") {
                        if ($Zahl == 1) {
                            $newtext = "%&%&&&%%$dorfs2->name, $Datum%&%$Meinungdusa%&%$c_loged" . "$newtext";
                        } else {
                            $newtext = "$newtext%&%&&&%%$Person[$Zahl]";
                        }

                        $Zahl += 1;
                    }
                } else {
                    $newtext = "%&%&&&%%$dorfs2->name, $Datum%&%$Meinungdusa%&%$c_loged" . "$Vorschlag->usertext";
                    echo "dap";
                }
            } else {
                $newtext = "%&%&&&%%$dorfs2->name, $Datum%&%$Meinungdusa%&%$c_loged";
                echo "dabei";
            }

            $up = "UPDATE X_Jutsueintrag SET usertext = '$newtext' WHERE id = '$Bearbeitedu'";
            mysql_query($up);
            $up = "UPDATE X_Jutsueintrag SET Dateuser = '$Datum' WHERE id = '$Bearbeitedu'";
            mysql_query($up);
            $up = "UPDATE X_Jutsueintrag SET lastuser = '$time' WHERE id = '$Bearbeitedu'";
            mysql_query($up);
            $up = "UPDATE X_Jutsueintrag SET lastact = '$time' WHERE id = '$Bearbeitedu'";
            mysql_query($up);
            echo "Erfolgreich überarbeitet!<br><br><a href='?Betrachtedu=$Bearbeitedu'>Zurück</a>";
            $Jutsubetracht = $Bearbeite;
        }
    } elseif ($Waffeerstellenwillstdu == 1) {
        echo "<form method='POST' action='?Waffeerstellenduwillst=1'><input type='submit' value='Übermitteln'></form>";
    } elseif ($dorfs->admin == "3" or $dorfs->Logprufer == 1 or $dorfs->CoAdmin >= 3) {
        if ($dorfs->admin == 3 or $dorfs->CoAdmin >= 3) {
            if ($Eintrag) {
                $sql = "SELECT id, Adminwilldie FROM X_Jutsueintrag WHERE id = '$Eintrag'";
                $query = mysql_query($sql);
                $Vorschlag = mysql_fetch_object($query);
                if ($Vorschlag->id > 0) {
                    $Adminswollen = "$Vorschlag->Adminwilldie$c_loged,";
                    $up = "UPDATE X_Jutsueintrag SET Adminwilldie = '$Adminswollen' WHERE id = '$Vorschlag->id'";
                    mysql_query($up);
                    echo "Du hast dich für diesen Vorschlag angemeldet.<br><br>";
                    $Jutsubetracht = $Vorschlag->id;
                }
            }
            if ($Austrag) {
                $sql = "SELECT id, Adminwilldie FROM X_Jutsueintrag WHERE id = '$Austrag'";
                $query = mysql_query($sql);
                $Vorschlag = mysql_fetch_object($query);
                if ($Vorschlag->id > 0) {
                    $Adminswollen = "Anfanghier$Vorschlag->Adminwilldie";
                    $Adminswollen = str_replace(",$c_loged,", "", $Adminswollen);
                    $Adminswollen = str_replace("Anfanghier$c_loged,", "", $Adminswollen);
                    $Adminswollen = str_replace("Anfanghier", "", $Adminswollen);
                    $up = "UPDATE X_Jutsueintrag SET Adminwilldie = '$Adminswollen' WHERE id = '$Vorschlag->id'";
                    mysql_query($up);
                    echo "Du hast dich für diesen Vorschlag ausgetragen.<br><br>";
                    $Jutsubetracht = $Vorschlag->id;
                }
            }
            if ($Bearbeite) {
                $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$Bearbeite'";
                $query = mysql_query($sql);
                $Vorschlager = mysql_fetch_object($query);

                if ($NameEEntwicklung != "") {
                    $NameEEntwicklung = htmlentities($NameEEntwicklung);
                    $NameEEntwicklung = str_replace("'", "\"", $NameEEntwicklung);
                    $up = "UPDATE X_Jutsueintrag SET Topic = '$NameEEntwicklung' WHERE id = '$Bearbeite'";
                    mysql_query($up);


                    $Artvors = htmlentities($Artvors);
                    $Artvors = str_replace("'", "\"", $Artvors);
                    $up = "UPDATE X_Jutsueintrag SET Art = '$Artvors' WHERE id = '$Bearbeite'";
                    mysql_query($up);

                    $NiveauVorsch = htmlentities($NiveauVorsch);
                    $NiveauVorsch = str_replace("'", "\"", $NiveauVorsch);
                    $up = "UPDATE X_Jutsueintrag SET Niveau = '$NiveauVorsch' WHERE id = '$Bearbeite'";
                    mysql_query($up);

                    $Artgenau = htmlentities($Artgenau);
                    $Artgenau = str_replace("'", "\"", $Artgenau);
                    $up = "UPDATE X_Jutsueintrag SET Artgenau = '$Artgenau' WHERE id = '$Bearbeite'";
                    mysql_query($up);

                    $time = time();
                    if ($Entwickende == 1 and $Vorschlager->Entwickende != 1) {
                        $up = "UPDATE X_Jutsueintrag SET Entwickende = '1' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    } elseif ($Entwickende != 1 and $Vorschlager->Entwickende != 0) {
                        $up = "UPDATE X_Jutsueintrag SET Entwickende = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);

                        $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '1' ORDER BY Warteschlange DESC";
                        $query = mysql_query($sql);
                        $Letzte = mysql_fetch_object($query);
                        $lasttime = $Letzte->Warteschlange + 1;
                        if ($Letzte->id < 1) {
                            $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '0' ORDER BY Warteschlange";
                            $query = mysql_query($sql);
                            $Letzte = mysql_fetch_object($query);
                            $lasttime = $Letzte->Warteschlange - 500;
                        }
                        if ($lasttime < 1) {
                            $lasttime = time();
                        }

                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '1' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    }

                    if ($Anderungswunsch == 1 and $Vorschlager->Anderungswunsch != 1) {
                        if ($Allgdata->Handlungsbedarf == 0) {
                            $lasttime = time();
                            $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $lasttime = time();
                        } elseif ($Allgdata->Handlungsbedarf == 1) {
                            $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '1' ORDER BY Warteschlange DESC";
                            $query = mysql_query($sql);
                            $Letzte = mysql_fetch_object($query);
                            $lasttime = $Letzte->Warteschlange + 1;
                            if ($Letzte->id < 1) {
                                $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND AusStandby = '0' ORDER BY Warteschlange";
                                $query = mysql_query($sql);
                                $Letzte = mysql_fetch_object($query);
                                $lasttime = $Letzte->Warteschlange - 500;
                            }
                            if ($Letzte->Warteschlange < 1) {
                                $lasttime = time();
                            }
                            $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET AusStandby = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                        } elseif ($Allgdata->Handlungsbedarf == 2) {
                            $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Aktiv = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                        }
                        if ($lasttime < 1) {
                            $lasttime = time();
                        }

                        $Vorschlager->Anderungswunsch = 1;
                    } elseif ($Anderungswunsch != 1 and $Vorschlager->Anderungswunsch != 0) {
                        $up = "UPDATE X_Jutsueintrag SET Anderungswunsch = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET AusStandby = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    }


                    if ($Kampfstil == 1) {
                        $up = "UPDATE X_Jutsueintrag SET Kampfstil = '1' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    } else {
                        $up = "UPDATE X_Jutsueintrag SET Kampfstil = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    }

                    if ($Standby == 1 and $Vorschlager->Inaktiv != 1) {
                        $up = "UPDATE X_Jutsueintrag SET Inaktiv = '1' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    } elseif ($Standby != 1 and $Vorschlager->Inaktiv != 0) {
                        $up = "UPDATE X_Jutsueintrag SET Inaktiv = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);

                        if ($Allgdata->Inaktiv == 0) {
                            $lasttime = time();
                            $up = "UPDATE X_Jutsueintrag SET AusStandby= '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Inaktiv = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $lasttime = time();
                        } elseif ($Allgdata->Inaktiv == 1) {
                            $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND Inaktiv = '0' AND AusStandby= '1' ORDER BY Warteschlange DESC";
                            $query = mysql_query($sql);
                            $Letzte = mysql_fetch_object($query);
                            $lasttime = $Letzte->Warteschlange + 1;
                            if ($Letzte->id < 1) {
                                $sql = "SELECT id, Warteschlange FROM X_Jutsueintrag WHERE Aktiv = '0' AND Zustand = '0' AND Entwickende = '0' AND Inaktiv = '0' AND Inaktiv = '0' AND AusStandby= '0' ORDER BY Warteschlange";
                                $query = mysql_query($sql);
                                $Letzte = mysql_fetch_object($query);
                                $lasttime = $Letzte->Warteschlange - 500;
                            }
                            if ($lasttime < 1) {
                                $lasttime = time();
                            }
                            $up = "UPDATE X_Jutsueintrag SET AusStandby= '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Inaktiv = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '$lasttime' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                        } elseif ($Allgdata->Inaktiv == 2) {
                            $up = "UPDATE X_Jutsueintrag SET Inaktiv = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Aktiv = '1' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                            $up = "UPDATE X_Jutsueintrag SET AusStandby= '0' WHERE id = '$Bearbeite'";
                            mysql_query($up);
                        }
                    }

                    if ($Eingetragenda == 1) {
                        $up = "UPDATE X_Jutsueintrag SET Eingetragen = '1' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    } else {
                        $up = "UPDATE X_Jutsueintrag SET Eingetragen = '0' WHERE id = '$Bearbeite'";
                        mysql_query($up);
                    }

                    echo "Erfolgreich überarbeitet!<br><br>";
                    $Jutsubetracht = $Bearbeite;
                }
            }
            if ($Entfernens >= 1) {
                $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$Entfernens'";
                $query = mysql_query($sql);
                $Vorschlag = mysql_fetch_object($query);
                if ($Vorschlag->Zustand > 0) {
                    $Inhalt = "Gänzliche Löschung";
                    $sele = " checked";
                } else {
                    $Inhalt = "";
                    $sele = "";
                }
                echo "<form method='POST' action='?Entferne=$Vorschlag->id'>Mit welcher Begründung soll der Vorschlag gelöscht werden?<br>
                        <textarea name='Begrundungs' style='height: 167px;' rows='10' cols='80'>$Inhalt</textarea><br>";
                if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                    echo "<input type='checkbox' name='ganzloesche' value='1'$sele> Vorschlag ganz löschen<br>";
                }
                echo "<input type='submit' value='Vorschlag löschen'></form>";
            }
            if ($Entferne >= 1) {
                if ($Begrundungs != "") {
                    $sql = "SELECT id, Ninja, Topic, Zustand FROM X_Jutsueintrag WHERE id = '$Entferne'";
                    $query = mysql_query($sql);
                    $Vorschlag = mysql_fetch_object($query);
                    $adminser = 0;
                    if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                        $adminser = 1;
                    }
                    if ($adminser == 1 and $ganzloesche == 1) {
                        $up = "DELETE FROM X_Jutsueintrag WHERE id = '$Entferne'";
                        mysql_query($up);
                        $up = "DELETE FROM X_Posts WHERE Topic = '$Entferne'";
                        mysql_query($up);
                        $Date = date("d.m.Y, H:i");
                        if ($Vorschlag->Zustand < 1) {
                            $Begrundungs = str_replace("'", "\"", $Begrundungs);
                            $Begrundungs = htmlentities($Begrundungs);
                            $aendern = "UPDATE user set nm = '1' WHERE id = '$Vorschlag->Ninja'";
                            $update = mysql_query($aendern);
                            $eintrag2 = "INSERT INTO Posteingang (An, Von, Betreff, Text, Datum, Gelesen) VALUES ('$Vorschlag->Ninja', 'System', '$Vorschlag->Topic abgelehnt', 'Dein Vorschlag $Vorschlag->Topic wurde mit folgender Begründung abgelehnt: $Begrundungs', '$Date', '0')";
                            $eintrag2 = mysql_query($eintrag2) or die("Senden fehlgeschlagen!");
                        }
                        echo "Der Vorschlag wurde abgelehnt!<br><a href='?'>Zurück</a><br>";
                    } else {
                        $up = "UPDATE X_Jutsueintrag SET Zustand = '2' WHERE id = '$Entferne'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Warteschlange = '0' WHERE id = '$Entferne'";
                        mysql_query($up);
                        $up = "UPDATE X_Jutsueintrag SET Aktiv = '0' WHERE id = '$Entferne'";
                        mysql_query($up);
                        $Date = date("d.m.Y, H:i");
                        if ($Vorschlag->Zustand < 1) {
                            $Begrundungs = str_replace("'", "\"", $Begrundungs);
                            $Begrundungs = htmlentities($Begrundungs);
                            $aendern = "UPDATE user set nm = '1' WHERE id = '$Vorschlag->Ninja'";
                            $update = mysql_query($aendern);
                            $eintrag2 = "INSERT INTO Posteingang (An, Von, Betreff, Text, Datum, Gelesen) VALUES ('$Vorschlag->Ninja', 'System', '$Vorschlag->Topic abgelehnt', 'Dein Vorschlag $Vorschlag->Topic wurde mit folgender Begründung abgelehnt:<br>$Begrundungs', '$Date', '0')";
                            $eintrag2 = mysql_query($eintrag2) or die("Senden fehlgeschlagen!");
                        }
                        echo "Der Vorschlag wurde abgelehnt!<br><a href='?'>Zurück</a><br>";
                    }
                } else {
                    echo "Es muss eine Begründung für die Entfernung des Vorschlages gegeben werden!";
                }
            }
            if ($Jutsubetracht >= 1) {
                $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$Jutsubetracht'";
                $query = mysql_query($sql);
                $Vorschlag = mysql_fetch_object($query);
                $sql = "SELECT id, name FROM user WHERE id = '$Vorschlag->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);

                $Datum = date("d.m.Y");
                $Zeit = date("H:i");
                $pos = strpos($Vorschlag->Einsicht, "|$dorfs->id,$Datum|");
                if ($pos === false) {
                    $Einsicht = unserialize($Vorschlag->Einsicht);

                    if ($Einsicht == "") {
                        $Einsicht = array();
                    }

                    $Einsicht[] = "|$dorfs->id,$Datum|$Zeit|";
                    $Einsicht = serialize($Einsicht);

                    $up = "UPDATE X_Jutsueintrag SET Einsicht = '$Einsicht' WHERE id = '$Jutsubetracht'";
                    mysql_query($up);
                }

                echo "<b>$Vorschlag->Topic von <a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></b><br><a href='?'>Zurück</a>";

                if ($ZweiteMeinung > 0) {
                    if ($Vorschlag->ZweiteMeinung < 1) {
                        $up = "UPDATE X_Jutsueintrag SET ZweiteMeinung = '$dorfs->id' WHERE id = '$Vorschlag->id' AND ZweiteMeinung = '0'";
                        mysql_query($up);
                        $Vorschlag->ZweiteMeinung = $dorfs->id;
                        echo "<br>Zweite Meinung wird erbeten<br>";
                    } elseif ($Vorschlag->ZweiteMeinung == $dorfs->id) {
                        $up = "UPDATE X_Jutsueintrag SET ZweiteMeinung = '0' WHERE id = '$Vorschlag->id' AND ZweiteMeinung = '$dorfs->id'";
                        mysql_query($up);
                        $Vorschlag->ZweiteMeinung = 0;
                        echo "<br>Zweite Meinung wird nicht weiter erbeten<br>";
                    }
                }

                if ($Vorschlag->ZweiteMeinung < 1) {
                    echo "<br><a href='?Jutsubetracht=$Vorschlag->id&ZweiteMeinung=$Vorschlag->id'>Zweite Meinung erbitten</a>";
                } elseif ($Vorschlag->ZweiteMeinung == $dorfs->id) {
                    echo "<br><a href='?Jutsubetracht=$Vorschlag->id&ZweiteMeinung=$Vorschlag->id'>Zweite Meinung entfernen</a>";
                }

                echo " - <a href='?Entfernens=$Vorschlag->id'>Diesen Vorschlag löschen</a><br><br>";

                echo "<IFRAME name=Topic frameBorder=0 src='Includes/EE2/Forum_Topic.php?Topic=$Vorschlag->id' width=100% height=500></iframe>";


                if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                    echo "<div id='NormBearbeitung' style='display:block'>";
                }

                echo "<IFRAME name=Post frameBorder=0 src='Includes/EE2/Forum_Post.php?Topic=$Vorschlag->id' width=99% height=420></iframe>";

                echo "<form method='POST' action='?Bearbeite=$Vorschlag->id'>
                        <b>Name der Eigenentwicklung</b><br>
                        <input type='text' name='NameEEntwicklung' size='80' value='$Vorschlag->Topic'><br>";

                echo "<b>Art der Entwicklung</b>:
                        <select name='Artvors'>
                        <option value='1'";
                if ($Vorschlag->Art == 1) {
                    echo " selected";
                }
                echo ">Jutsuentwicklung
                        <option value='2'";
                if ($Vorschlag->Art == 2) {
                    echo " selected";
                }
                echo ">Itementwicklungen
                        <option value='3'";
                if ($Vorschlag->Art == 3) {
                    echo " selected";
                }
                echo ">Sonstige Entwicklung
                        </select><br>
                        <b>Genauer:</b> <select name='Artgenau'>
                        <option value='1'";
                if ($Vorschlag->Artgenau == 1) {
                    echo " selected";
                }
                echo ">Taijutsu
                        <option value='2'";
                if ($Vorschlag->Artgenau == 2) {
                    echo " selected";
                }
                echo ">Ninjutsu
                        <option value='3'";
                if ($Vorschlag->Artgenau == 3) {
                    echo " selected";
                }
                echo ">Genjutsu
                        <option value='10'";
                if ($Vorschlag->Artgenau == 10) {
                    echo " selected";
                }
                echo ">Siegel
                        <option value='4'";
                if ($Vorschlag->Artgenau == 4) {
                    echo " selected";
                }
                echo ">Item mit Chakra-Einfluss
                        <option value='5'";
                if ($Vorschlag->Artgenau == 5) {
                    echo " selected";
                }
                echo ">Item auf Technik-Basis
                        <option value='6'";
                if ($Vorschlag->Artgenau == 6) {
                    echo " selected";
                }
                echo ">Item mit Technik und Chakra
                        <option value='7'";
                if ($Vorschlag->Artgenau == 7) {
                    echo " selected";
                }
                echo ">Sonstiges Item (z.B. normale Klinge)
                        <option value='8'";
                if ($Vorschlag->Artgenau == 8) {
                    echo " selected";
                }
                echo ">Fähigkeit
                        <option value='9'";
                if ($Vorschlag->Artgenau == 9) {
                    echo " selected";
                }
                echo ">BE/Clan Jutsu/Fähigkeit
                        <option value='11'";
                if ($Vorschlag->Artgenau == 11) {
                    echo " selected";
                }
                echo ">Kuchiyose
                        </select><br>
                        <b>Niveau der Entwicklung:</b> <select name='NiveauVorsch'>
                        <option value='1'";
                if ($Vorschlag->Niveau == 1) {
                    echo " selected";
                }
                echo ">E-Rang
                        <option value='2'";
                if ($Vorschlag->Niveau == 2) {
                    echo " selected";
                }
                echo ">D-Rang
                        <option value='3'";
                if ($Vorschlag->Niveau == 3) {
                    echo " selected";
                }
                echo ">C-Rang
                        <option value='4'";
                if ($Vorschlag->Niveau == 4) {
                    echo " selected";
                }
                echo ">B-Rang
                        <option value='5'";
                if ($Vorschlag->Niveau == 5) {
                    echo " selected";
                }
                echo ">A-Rang
                        <option value='6'";
                if ($Vorschlag->Niveau == 6) {
                    echo " selected";
                }
                echo ">S-Rang
                        </select><br>";

                echo "<table border='0' width='90%'><tr>";

                if ($Vorschlag->Entwickende == 1) {
                    $che = " checked";
                } else {
                    $che = "";
                }
                echo "<td><n>Entwicklung beendet?</b></td><td><input type='checkbox' value='1' name='Entwickende'$che></td>";
                if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                    if ($Vorschlag->Anderungswunsch == 1) {
                        $che = " checked";
                    } else {
                        $che = "";
                    }
                    echo "<td><n>Änderungswunsch?</b></td><td><input type='checkbox' value='1' name='Anderungswunsch'$che></td></tr>";

                    if ($Vorschlag->Eingetragen == 1) {
                        $che = " checked";
                    } else {
                        $che = "";
                    }
                    echo "<tr><td><n>Eingetragen?</b></td><td><input type='checkbox' value='1' name='Eingetragenda'$che></td>";

                    if ($Vorschlag->Kampfstil == 1) {
                        $che = " checked";
                    } else {
                        $che = "";
                    }
                    echo "<td><n>Kampfstil?</b></td><td><input type='checkbox' value='1' name='Kampfstil'$che></td></tr>";

                    if ($Vorschlag->Inaktiv == 1) {
                        $che = " checked";
                    } else {
                        $che = "";
                    }
                    echo "<td><n>Inaktiv?</b></td><td><input type='checkbox' value='1' name='Standby'$che></td></tr>";
                }
                echo "</tr></table>";
                echo "<input type='submit' value='Eintragen'></form>";
            } elseif ($Search == 1) {
                $sql = "SELECT id, name FROM user WHERE name = '$Searchfor'";
                $query = mysql_query($sql);
                $Ninja = mysql_fetch_object($query);
                if ($Ninja->id > 0) {
                    echo "<b>Entwicklungen von $Ninja->name</b><br><a href='?'>Zurück</a><br><br>";

                    echo "<div id='Unfertiges' style='display:block'>";
                    echo "<b>Jutsuentwicklungen:</b><br>";
                    echo "<table border='0' width='100%'>";
                    echo "<tr>";
                    echo "<td width='15%'><b>Ninja</b></td>";
                    echo "<td width='40%'><b>Jutsuvorschlag</b></td>";
                    echo "<td width='15%'><b>Zugeteilt</b></td>";
                    echo "<td width='10%'><b>Bearbeiten</b></td>";
                    echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
                    echo "</tr>";
                    $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '1' AND Ninja = '$Ninja->id' ORDER BY Zustand, Entwickende, lastact";
                    $query2 = mysql_query($sql2);
                    while ($Mission = mysql_fetch_object($query2)) {
                        $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                        $query = mysql_query($sql);
                        $Nin = mysql_fetch_object($query);
                        $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                        $query = mysql_query($sql);
                        $lastPoster = mysql_fetch_object($query);
                        echo "<tr>";
                        echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                        echo "<td>$Mission->Topic";
                        if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                            echo " - <b><font color='#006600'>Eingetragen</font></b>";
                        }


                        if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                            echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                        }
                        if ($Mission->Anderungswunsch > 0) {
                            echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($Mission->Adminwilldie != "") {
                            $ROFL = explode(",", $Mission->Adminwilldie);
                            $Zahl = 0;
                            while ($ROFL[$Zahl] != "") {
                                if ($Zahl > 0) {
                                    echo ", ";
                                }
                                $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                                $query = mysql_query($sql);
                                $Nin2 = mysql_fetch_object($query);
                                echo "$Nin2->name";
                                $Zahl += 1;
                            }
                        } else {
                            echo "Niemand";
                        }
                        echo "</td>";
                        echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "<font color='#cc0033'>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "<font color='#330000'>";
                        }
                        echo "Betrachten";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "</font>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "</font>";
                        }
                        echo "</a></td>";
                        echo "<td>";
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                            echo "-";
                        } else {
                            $Zeit = time() - $Mission->lastact;
                            $Stunden = 0;
                            $Tage = 0;
                            while ($Zeit >= 86400) {
                                $Zeit -= 86400;
                                $Tage += 1;
                            }
                            while ($Zeit >= 3600) {
                                $Zeit -= 3600;
                                $Stunden += 1;
                            }
                            echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<br><br>";
                    echo "<b>Waffenentwicklungen:</b><br>";
                    echo "<table border='0' width='100%'>";
                    echo "<tr>";
                    echo "<td width='15%'><b>Ninja</b></td>";
                    echo "<td width='40%'><b>Waffenvorschlag</b></td>";
                    echo "<td width='15%'><b>Zugeteilt</b></td>";
                    echo "<td width='10%'><b>Bearbeiten</b></td>";
                    echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
                    echo "</tr>";
                    $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '2' AND Ninja = '$Ninja->id' ORDER BY Zustand, Entwickende, lastact";
                    $query2 = mysql_query($sql2);
                    while ($Mission = mysql_fetch_object($query2)) {
                        $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                        $query = mysql_query($sql);
                        $Nin = mysql_fetch_object($query);
                        $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                        $query = mysql_query($sql);
                        $lastPoster = mysql_fetch_object($query);
                        echo "<tr>";
                        echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                        echo "<td>$Mission->Topic";

                        if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                            echo " - <b><font color='#006600'>Eingetragen</font></b>";
                        }
                        if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                            echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                        }
                        if ($Mission->Anderungswunsch > 0) {
                            echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($Mission->Adminwilldie != "") {
                            $ROFL = explode(",", $Mission->Adminwilldie);
                            $Zahl = 0;
                            while ($ROFL[$Zahl] != "") {
                                if ($Zahl > 0) {
                                    echo ", ";
                                }
                                $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                                $query = mysql_query($sql);
                                $Nin2 = mysql_fetch_object($query);
                                echo "$Nin2->name";
                                $Zahl += 1;
                            }
                        } else {
                            echo "Niemand";
                        }
                        echo "</td>";
                        echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "<font color='#cc0033'>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "<font color='#330000'>";
                        }
                        echo "Betrachten";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "</font>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "</font>";
                        }
                        echo "</a></td>";
                        echo "<td>";
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                            echo "-";
                        } else {
                            $Zeit = time() - $Mission->lastact;
                            $Stunden = 0;
                            $Tage = 0;
                            while ($Zeit >= 86400) {
                                $Zeit -= 86400;
                                $Tage += 1;
                            }
                            while ($Zeit >= 3600) {
                                $Zeit -= 3600;
                                $Stunden += 1;
                            }
                            echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<br><br>";

                    echo "<b>Sonstige Entwicklung:</b><br>";
                    echo "<table border='0' width='100%'>";
                    echo "<tr>";
                    echo "<td width='15%'><b>Ninja</b></td>";
                    echo "<td width='40%'><b>Vorschlag</b></td>";
                    echo "<td width='15%'><b>Zugeteilt</b></td>";
                    echo "<td width='10%'><b>Bearbeiten</b></td>";
                    echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
                    echo "</tr>";
                    $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '3' AND Ninja = '$Ninja->id' ORDER BY Zustand, Entwickende, lastact";
                    $query2 = mysql_query($sql2);
                    while ($Mission = mysql_fetch_object($query2)) {
                        $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                        $query = mysql_query($sql);
                        $Nin = mysql_fetch_object($query);
                        $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                        $query = mysql_query($sql);
                        $lastPoster = mysql_fetch_object($query);
                        echo "<tr>";
                        echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                        echo "<td>$Mission->Topic";

                        if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                            echo " - <b><font color='#006600'>Eingetragen</font></b>";
                        }
                        if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                            echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                        }
                        if ($Mission->Anderungswunsch > 0) {
                            echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($Mission->Adminwilldie != "") {
                            $ROFL = explode(",", $Mission->Adminwilldie);
                            $Zahl = 0;
                            while ($ROFL[$Zahl] != "") {
                                if ($Zahl > 0) {
                                    echo ", ";
                                }
                                $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                                $query = mysql_query($sql);
                                $Nin2 = mysql_fetch_object($query);
                                echo "$Nin2->name";
                                $Zahl += 1;
                            }
                        } else {
                            echo "Niemand";
                        }
                        echo "</td>";
                        echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "<font color='#cc0033'>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "<font color='#330000'>";
                        }
                        echo "Betrachten";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "</font>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "</font>";
                        }
                        echo "</a></td>";
                        echo "<td>";
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                            echo "-";
                        } else {
                            $Zeit = time() - $Mission->lastact;
                            $Stunden = 0;
                            $Tage = 0;
                            while ($Zeit >= 86400) {
                                $Zeit -= 86400;
                                $Tage += 1;
                            }
                            while ($Zeit >= 3600) {
                                $Zeit -= 3600;
                                $Stunden += 1;
                            }
                            echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    echo "</div>";
                } else {
                    echo "Diesen Ninja gibt es nicht.<br><a href='?'>Zurück</a>";
                }
            } else {
                echo "<script type=\"text/javascript\">
                        <!--
                        function show(id)
                        {
                            var id = id;
                            if (id == 'Unfertiges')
                            {
                                Unfertiges.style.display = 'block';
                                Fertiges.style.display = 'none';
                                Eingetragenes.style.display = 'none';
                                Standby.style.display = 'none';
                            }
                            if (id == 'Fertiges')
                            {
                                Unfertiges.style.display = 'none';
                                Fertiges.style.display = 'block';
                                Eingetragenes.style.display = 'none';
                                Standby.style.display = 'none';
                            }
                            if (id == 'Eingetragenes')
                            {
                                Unfertiges.style.display = 'none';
                                Fertiges.style.display = 'none';
                                Eingetragenes.style.display = 'block';
                                Standby.style.display = 'none';
                            }
                            if (id == 'Standby')
                            {
                                Unfertiges.style.display = 'none';
                                Fertiges.style.display = 'none';
                                Eingetragenes.style.display = 'none';
                                Standby.style.display = 'block';
                            }
                        }
                    -->
                        </script>";

                echo "<script type=\"text/javascript\">
                        <!--
                        function shows(id)
                        {
                            if (document.getElementById(id).style.display == 'block')
                            {
                                document.getElementById(id).style.display = 'none';
                            }
                            else
                            {
                                document.getElementById(id).style.display = 'block';
                            }
                        }
                    -->
                        </script>";

                echo "<a href=\"javascript:show('Unfertiges');\">Unfertige Entwicklungen anzeigen</a> -
                        <a href=\"javascript:show('Fertiges');\">Fertige Entwicklungen anzeigen</a>
                        - <a href=\"javascript:show('Eingetragenes');\">Eingetragene Entwicklungen anzeigen</a><br>
                        <a href=\"javascript:show('Standby');\">Standby Entwicklungen anzeigen</a>";

                echo "<form method='POST' action='?Search=1'>
                        Entwicklungen von Spielern <input type='text' name='Searchfor'> <input type='submit' value='suchen'>
                        </form>";

                echo "<div id='Unfertiges' style='display:block'>";

                $sql33 = "SELECT COUNT(*) FROM  X_Jutsueintrag WHERE Zustand = '0' AND Entwickende = '0' AND Aktiv = '1' AND lastadmin < lastuser";
                $query33 = mysql_query($sql33);
                $Zahl = mysql_fetch_row($query33);

                $sql33 = "SELECT COUNT(*) FROM  X_Jutsueintrag WHERE Zustand = '0' AND Entwickende = '0' AND Aktiv = '1'";
                $query33 = mysql_query($sql33);
                $Zahl2 = mysql_fetch_row($query33);
                echo "<b>Aktive EEs ($Zahl[0]/$Zahl2[0] unbearbeitet):</b><br>";
                echo "<table border='0' width='100%'>";
                echo "<tr>";
                echo "<td width='15%'><b>Reihenfolge</b></td>";
                echo "<td width='15%'><b>Ninja</b></td>";
                echo "<td width='5%'><b>AusgTP</b></td>";
                echo "<td width='5%'><b>EETPOk</b></td>";
                echo "<td width='5%'><b>Niveau</b></td>";
                echo "<td width='35%'><b>Vorschlag</b></td>";
                echo "<td width='15%'><b>Zugeteilt</b></td>";
                echo "<td width='10%'><b>Bearbeiten</b></td>";
                echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
                echo "</tr>";
                $Art = 1;
                while ($Art < 4) {
                    if ($Art == 1) {
                        echo "<tr><td colspan='6' align='center'><br><b>Jutsu-Entwicklungen</b><br><br></td></tr>";
                    }
                    if ($Art == 2) {
                        echo "<tr><td colspan='6' align='center'><br><b>Waffen-Entwicklungen</b><br><br></td></tr>";
                    }
                    if ($Art == 3) {
                        echo "<tr><td colspan='6' align='center'><br><b>Sonstige Entwicklungen</b><br><br></td></tr>";
                    }

                    $ids = array();
                    $sql2 = "SELECT id FROM X_Jutsueintrag WHERE Zustand = '0' AND Entwickende = '0' AND Aktiv = '1' ORDER BY lastact";
                    $query2 = mysql_query($sql2);
                    while ($EEIds = mysql_fetch_object($query2)) {
                        $ids[] = $EEIds->id;
                        asort($ids);
                    }
                    $EEidS = [];
                    foreach ($ids as $EEid) {
                        $EEidS[] = $EEid;
                    }
                    $Zahl = 0;
                    while ($Zahl < 2) {
                        if ($Zahl == 0) {
                            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Anderungswunsch = '1' AND Art = '$Art' AND Entwickende = '1' AND Aktiv = '1' ORDER BY lastact";
                        }
                        if ($Zahl == 1) {
                            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Zustand = '0' AND Art = '$Art' AND Entwickende = '0' AND Aktiv = '1' ORDER BY lastact";
                        }
                        $query2 = mysql_query($sql2);
                        while ($Mission = mysql_fetch_object($query2)) {
                            $sql = "SELECT * FROM user WHERE id = '$Mission->Ninja'";
                            $query = mysql_query($sql);
                            $Nin = mysql_fetch_object($query);
                            $sql = "SELECT * FROM Jutsuk WHERE id = '$Nin->id'";
                            $query = mysql_query($sql);
                            $u_Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
                            $sql = "SELECT * FROM Fähigkeiten WHERE id = '$Nin->id'";
                            $query = mysql_query($sql);
                            $u_Fähigkeiten = mysql_fetch_array($query, MYSQL_ASSOC);
                            $TPausgegeben = $tps->tpBackGesamt($Nin, $u_Jutsu, $u_Fähigkeiten);
                            $EETPausgegeben = $tps->howMuchRAllEEs($Nin, $u_Jutsu);
                            $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                            $query = mysql_query($sql);
                            $lastPoster = mysql_fetch_object($query);
                            echo "<tr>";
                            echo "<td>" . array_search($Mission->id, $EEidS) . "</td>";
                            echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                            echo "<td>" . $TPausgegeben . "</td>";
                            if (($TPausgegeben - $EETPausgegeben) < 400) {
                                $TPEEOK = floor($TPausgegeben * 0.15 - $EETPausgegeben);
                            } else {
                                $TPEEOK = 'unbegrenzt';
                            }
                            echo "<td>" . $TPEEOK . "</td>";
                            echo "<td>";
                            if ($Mission->Niveau == 1) {
                                echo "<b>E</b>";
                            } elseif ($Mission->Niveau == 2) {
                                echo "<b>D</b>";
                            } elseif ($Mission->Niveau == 3) {
                                echo "<b>C</b>";
                            } elseif ($Mission->Niveau == 4) {
                                echo "<b>B</b>";
                            } elseif ($Mission->Niveau == 5) {
                                echo "<b>A</b>";
                            } elseif ($Mission->Niveau == 6) {
                                echo "<b>S</b>";
                            } else {
                                echo "<b>-</b>";
                            }
                            echo "</td>";
                            echo "<td>$Mission->Topic";
                            if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                                echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                            }
                            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                                echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                            }
                            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                                echo " - <b><font color='#006600'>Eingetragen</font></b>";
                            }

                            if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                                echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                            }
                            if ($Mission->Anderungswunsch > 0) {
                                echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                            }
                            echo "</td>";
                            echo "<td>";
                            if ($Mission->Adminwilldie != "") {
                                $ROFL = explode(",", $Mission->Adminwilldie);
                                $Zahl = 0;
                                while ($ROFL[$Zahl] != "") {
                                    if ($Zahl > 0) {
                                        echo ", ";
                                    }
                                    $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                                    $query = mysql_query($sql);
                                    $Nin2 = mysql_fetch_object($query);
                                    echo "$Nin2->name";
                                    $Zahl += 1;
                                }
                            } else {
                                echo "Niemand";
                            }
                            echo "</td>";
                            echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                            if ($Mission->lastadmin <= $Mission->lastuser) {
                                echo "<font color='#cc0033'>";
                            } elseif ($Mission->Adminlast != $dorfs->id) {
                                echo "<font color='#330000'>";
                            }
                            echo "Betrachten";
                            if ($Mission->lastadmin <= $Mission->lastuser) {
                                echo "</font>";
                            } elseif ($Mission->Adminlast != $dorfs->id) {
                                echo "</font>";
                            }


                            echo "</a></td>";
                            echo "<td>";
                            if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                                echo "-";
                            } else {
                                $Zeit = time() - $Mission->lastact;
                                $Stunden = 0;
                                $Tage = 0;
                                while ($Zeit >= 86400) {
                                    $Zeit -= 86400;
                                    $Tage += 1;
                                }
                                while ($Zeit >= 3600) {
                                    $Zeit -= 3600;
                                    $Stunden += 1;
                                }
                                echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        $Zahl += 1;
                    }
                    $Art += 1;
                }
                echo "</table>";
                echo "<br><br>";


            if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                echo "<br><br>";
                echo "<b><a href=\"javascript:shows('Abgelehnte');\">Abgelehnte Entwicklung:</a></b><br>
                        <div id='Abgelehnte' style='display:none'>";
                echo "<table border='0' width='100%'>";
                echo "<tr>";
                echo "<td width='20%'><b>Art</b></td>";
                echo "<td width='15%'><b>Ninja</b></td>";
                echo "<td width='40%'><b>Vorschlag</b></td>";
                echo "<td width='15%'><b>Zustand</b></td>";
                echo "<td width='10%'><b>Bearbeiten</b></td>";
                echo "</tr>";
                $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Zustand > '0' AND Entwickende = '0' ORDER BY id DESC";
                $query2 = mysql_query($sql2);
                while ($Mission = mysql_fetch_object($query2)) {
                    $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                    $query = mysql_query($sql);
                    $Nin = mysql_fetch_object($query);
                    echo "<tr>";
                    echo "<td>";
                    if ($Mission->Art == 1) {
                        echo "Jutsuvorschlag";
                    }
                    if ($Mission->Art == 2) {
                        echo "Waffenvorschlag";
                    }
                    if ($Mission->Art == 3) {
                        echo "Sonstiger Vorschlag";
                    }

                    echo "</td>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic</td>";
                    echo "<td>";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    echo "Betrachten";
                    echo "</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            }
            echo "</div>";

            echo "<div id='Standby' style='display:none'>";

            $sql33 = "SELECT COUNT(*) FROM  X_Jutsueintrag WHERE Zustand = '0' AND Entwickende = '0' AND Aktiv = '1' AND lastadmin < lastuser";
            $query33 = mysql_query($sql33);
            $Zahl = mysql_fetch_row($query33);

            $sql33 = "SELECT COUNT(*) FROM  X_Jutsueintrag WHERE Zustand = '0' AND Entwickende = '0' AND Aktiv = '1'";
            $query33 = mysql_query($sql33);
            $Zahl2 = mysql_fetch_row($query33);
            echo "<b>Standby-EEs</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='5%'><b>Niveau</b></td>";
            echo "<td width='35%'><b>Vorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $Art = 1;
            while ($Art < 4) {
                if ($Art == 1) {
                    echo "<tr><td colspan='6' align='center'><br><b>Jutsu-Entwicklungen</b><br><br></td></tr>";
                }
                if ($Art == 2) {
                    echo "<tr><td colspan='6' align='center'><br><b>Waffen-Entwicklungen</b><br><br></td></tr>";
                }
                if ($Art == 3) {
                    echo "<tr><td colspan='6' align='center'><br><b>Sonstige Entwicklungen</b><br><br></td></tr>";
                }
                $Zahl = 0;
                while ($Zahl < 1) {
                    if ($Zahl == 0) {
                        $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Zustand = '0' AND Art = '$Art' AND Entwickende = '0' AND Aktiv = '0' AND Inaktiv = '1' ORDER BY lastact";
                    }
                    $query2 = mysql_query($sql2);
                    while ($Mission = mysql_fetch_object($query2)) {
                        $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                        $query = mysql_query($sql);
                        $Nin = mysql_fetch_object($query);
                        echo "<tr>";
                        echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                        echo "<td>";
                        if ($Mission->Niveau == 1) {
                            echo "<b>E</b>";
                        } elseif ($Mission->Niveau == 2) {
                            echo "<b>D</b>";
                        } elseif ($Mission->Niveau == 3) {
                            echo "<b>C</b>";
                        } elseif ($Mission->Niveau == 4) {
                            echo "<b>B</b>";
                        } elseif ($Mission->Niveau == 5) {
                            echo "<b>A</b>";
                        } elseif ($Mission->Niveau == 6) {
                            echo "<b>S</b>";
                        } else {
                            echo "<b>-</b>";
                        }
                        echo "</td>";
                        echo "<td>$Mission->Topic";
                        if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                            echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                        }
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                            echo " - <b><font color='#006600'>Eingetragen</font></b>";
                        }

                        if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                            echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                        }
                        if ($Mission->Anderungswunsch > 0) {
                            echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                        }
                        echo "</td>";
                        echo "<td>";
                        if ($Mission->Adminwilldie != "") {
                            $ROFL = explode(",", $Mission->Adminwilldie);
                            $Zahl = 0;
                            while ($ROFL[$Zahl] != "") {
                                if ($Zahl > 0) {
                                    echo ", ";
                                }
                                $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                                $query = mysql_query($sql);
                                $Nin2 = mysql_fetch_object($query);
                                echo "$Nin2->name";
                                $Zahl += 1;
                            }
                        } else {
                            echo "Niemand";
                        }
                        echo "</td>";
                        echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "<font color='#cc0033'>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "<font color='#330000'>";
                        }
                        echo "Betrachten";
                        if ($Mission->lastadmin <= $Mission->lastuser) {
                            echo "</font>";
                        } elseif ($Mission->Adminlast != $dorfs->id) {
                            echo "</font>";
                        }
                        echo "</a></td>";
                        echo "<td>";
                        if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                            echo "-";
                        } else {
                            $Zeit = time() - $Mission->lastact;
                            $Stunden = 0;
                            $Tage = 0;
                            while ($Zeit >= 86400) {
                                $Zeit -= 86400;
                                $Tage += 1;
                            }
                            while ($Zeit >= 3600) {
                                $Zeit -= 3600;
                                $Stunden += 1;
                            }
                            echo "$Tage Tag(e), $Stunden Stunde(n)";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    $Zahl += 1;
                }
                $Art += 1;
            }
            echo "</table>";
            echo "<br><br>";

            echo "</div>";

            echo "<div id='Fertiges' style='display:none'>";
            echo "<b>Jutsuentwicklungen</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Jutsuvorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $Tageher = 0;
            $ZahlEEs = 0;
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '1' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '0' ORDER BY lastact";
            $query2 = mysql_query($sql2);
            while ($Mission = mysql_fetch_object($query2)) {
                $query = mysql_query("SELECT id, name FROM user WHERE id = '$Mission->Ninja'");
                $Nin = mysql_fetch_object($query);
                $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                $query = mysql_query($sql);
                $lastPoster = mysql_fetch_object($query);

                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }


                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                    }
                    echo "</td>";
                    echo "</tr>";
                    $Tageher += $Tage;
                    $ZahlEEs += 1;
                }
            }
            echo "</table>";
            echo "<br><br>";
            echo "<b>Waffenentwicklungen:</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Waffenvorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '2' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '0' ORDER BY lastact";
            $query2 = mysql_query($sql2);
            while ($Mission = mysql_fetch_object($query2)) {
                $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);
                $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                $query = mysql_query($sql);
                $lastPoster = mysql_fetch_object($query);

                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }

                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                    }
                    echo "</td>";
                    echo "</tr>";
                    $Tageher += $Tage;
                    $ZahlEEs += 1;
                }
            }
            echo "</table>";
            echo "<br><br>";

            echo "<b>Sonstige Entwicklung:</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Vorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '3' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '0' ORDER BY lastact";
            $query2 = mysql_query($sql2);
            while ($Mission = mysql_fetch_object($query2)) {
                $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);
                $sql = "SELECT u.`name` as namelast FROM `X_Posts` x LEFT JOIN `user` u ON x.`Von` = u.`id` WHERE Topic = '$Mission->id' ORDER BY x.`id` DESC LIMIT 0,1";
                $query = mysql_query($sql);
                $lastPoster = mysql_fetch_object($query);

                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }

                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n) - $lastPoster->namelast";
                    }
                    echo "</td>";
                    echo "</tr>";
                    $Tageher += $Tage;
                    $ZahlEEs += 1;
                }
            }
            echo "</table>";

            $Tagmittel = $Tageher / $ZahlEEs;
            $Tagmittel = round($Tagmittel);

            echo "Es sind $ZahlEEs Eigenentwicklungen im Schnitt seit $Tagmittel Tagen fertig.";

            if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                echo "<br><br>";
                echo "<b><a href=\"javascript:shows('Entfernte1');\">Entfernte Entwicklung:</a></b><br>";
                echo "<div id='Entfernte1' style='display:none;'><table border='0' width='100%'>";
                echo "<tr>";
                echo "<td width='20%'><b>Art</b></td>";
                echo "<td width='15%'><b>Ninja</b></td>";
                echo "<td width='40%'><b>Vorschlag</b></td>";
                echo "<td width='15%'><b>Zustand</b></td>";
                echo "<td width='10%'><b>Bearbeiten</b></td>";
                echo "</tr>";
                $query2 = mysql_query("SELECT * FROM X_Jutsueintrag WHERE Zustand > '0' AND Entwickende = '1' AND Eingetragen = '0' ORDER BY id DESC");
                while ($Mission = mysql_fetch_object($query2)) {
                    $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                    $query = mysql_query($sql);
                    $Nin = mysql_fetch_object($query);
                    echo "<tr>";
                    echo "<td>";
                    if ($Mission->Art == 1) {
                        echo "Jutsuvorschlag";
                    }
                    if ($Mission->Art == 2) {
                        echo "Waffenvorschlag";
                    }
                    if ($Mission->Art == 3) {
                        echo "Sonstiger Vorschlag";
                    }

                    echo "</td>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic</td>";
                    echo "<td>";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    echo "Betrachten";
                    echo "</a></td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }
            echo "</div>";

            echo "<div id='Eingetragenes' style='display:none'>";
            echo "<b>Jutsuentwicklungen</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Jutsuvorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $query2 = mysql_query("SELECT * FROM X_Jutsueintrag WHERE Art = '1' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '1' ORDER BY lastact");
            while ($Mission = mysql_fetch_object($query2)) {
                $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);
                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }

                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n)";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
            echo "<br><br>";
            echo "<b>Waffenentwicklungen:</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Waffenvorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '2' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '1' ORDER BY lastact";
            $query2 = mysql_query($sql2);
            while ($Mission = mysql_fetch_object($query2)) {
                $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);

                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }

                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n)";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
            echo "<br><br>";

            echo "<b>Sonstige Entwicklung:</b><br>";
            echo "<table border='0' width='100%'>";
            echo "<tr>";
            echo "<td width='15%'><b>Ninja</b></td>";
            echo "<td width='40%'><b>Vorschlag</b></td>";
            echo "<td width='15%'><b>Zugeteilt</b></td>";
            echo "<td width='10%'><b>Bearbeiten</b></td>";
            echo "<td width='20%'><b>Letzte Bearbeitung</b></td>";
            echo "</tr>";
            $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Art = '3' AND Zustand = '0' AND Entwickende = '1' AND Eingetragen = '1' ORDER BY lastact";
            $query2 = mysql_query($sql2);
            while ($Mission = mysql_fetch_object($query2)) {
                $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);

                if (($Nin->id > 1 && $old != 1) || ($Nin->id < 1 && $old == 1)) {
                    echo "<tr>";
                    echo "<td><a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }

                    if ($Mission->ZweiteMeinung > 0 and $Mission->ZweiteMeinung != $dorfs2->id) {
                        echo "<br><font color='#cc0033'><b>Zweite Meinung erwünscht!</b></font>";
                    }
                    if ($Mission->Anderungswunsch > 0) {
                        echo "<br><font color='#cc0033'><b>Änderungswunsch</b></font>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($Mission->Adminwilldie != "") {
                        $ROFL = explode(",", $Mission->Adminwilldie);
                        $Zahl = 0;
                        while ($ROFL[$Zahl] != "") {
                            if ($Zahl > 0) {
                                echo ", ";
                            }
                            $sql = "SELECT id, name FROM user WHERE id = '$ROFL[$Zahl]'";
                            $query = mysql_query($sql);
                            $Nin2 = mysql_fetch_object($query);
                            echo "$Nin2->name";
                            $Zahl += 1;
                        }
                    } else {
                        echo "Niemand";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "<font color='#cc0033'>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "<font color='#330000'>";
                    }
                    echo "Betrachten";
                    if ($Mission->lastadmin <= $Mission->lastuser) {
                        echo "</font>";
                    } elseif ($Mission->Adminlast != $dorfs->id) {
                        echo "</font>";
                    }
                    echo "</a></td>";
                    echo "<td>";
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1 and $Mission->Anderungswunsch == 0) {
                        echo "-";
                    } else {
                        $Zeit = time() - $Mission->lastact;
                        $Stunden = 0;
                        $Tage = 0;
                        while ($Zeit >= 86400) {
                            $Zeit -= 86400;
                            $Tage += 1;
                        }
                        while ($Zeit >= 3600) {
                            $Zeit -= 3600;
                            $Stunden += 1;
                        }
                        echo "$Tage Tag(e), $Stunden Stunde(n)";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
            if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4 or $dorfs->CoAdmin == 3) {
                echo "<br><br>";
                echo "<b><a href=\"javascript:shows('Entfernte2');\">Entfernte Entwicklung:</a></b><br>";
                echo "<div id='Entfernte2' style='display:none;'><table border='0' width='100%'>";
                echo "<tr>";
                echo "<td width='20%'><b>Art</b></td>";
                echo "<td width='15%'><b>Ninja</b></td>";
                echo "<td width='40%'><b>Vorschlag</b></td>";
                echo "<td width='15%'><b>Zustand</b></td>";
                echo "<td width='10%'><b>Bearbeiten</b></td>";
                echo "</tr>";
                $sql2 = "SELECT * FROM X_Jutsueintrag WHERE Zustand > '0' AND Entwickende = '1' AND Eingetragen = '1' ORDER BY id DESC";
                $query2 = mysql_query($sql2);
                while ($Mission = mysql_fetch_object($query2)) {
                    $sql = "SELECT id, name FROM user WHERE id = '$Mission->Ninja'";
                    $query = mysql_query($sql);
                    $Nin = mysql_fetch_object($query);
                    echo "<tr>";
                    echo "<td>";
                    if ($Mission->Art == 1) {
                        echo "Jutsuvorschlag";
                    } elseif ($Mission->Art == 2) {
                        echo "Waffenvorschlag";
                    } elseif ($Mission->Art == 3) {
                        echo "Sonstiger Vorschlag";
                    }

                    echo "</td>";
                    echo "<td><a href='/userpopup.php?usernam=$Nin->name'>$Nin->name</a></td>";
                    echo "<td>$Mission->Topic</td>";
                    echo "<td>";
                    if ($Mission->Zustand > 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#660000'>Abgelehnt</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 0) {
                        echo " - <b><font color='#006600'>Akzeptiert</font></b>";
                    }
                    if ($Mission->Zustand == 0 and $Mission->Entwickende == 1 and $Mission->Eingetragen == 1) {
                        echo " - <b><font color='#006600'>Eingetragen</font></b>";
                    }
                    echo "</td>";
                    echo "<td><a href='?Jutsubetracht=$Mission->id'>";
                    echo "Betrachten";
                    echo "</a></td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }
                echo "</div>";
            }
        }
    }
}
echo "</td></tr></table>";

get_footer();
