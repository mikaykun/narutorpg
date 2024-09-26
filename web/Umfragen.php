<?php

include(__DIR__ . "/../Menus/layout1.inc");

$abfrage2 = "SELECT id, admin FROM userdaten WHERE id LIKE '$c_loged'";
$ergebnis2 = mysql_query($abfrage2);
$object = mysql_fetch_object($ergebnis2);

if ($_GET['deleteumfrage']) {
    if ($dorfs->admin >= 3) {
        $del = "DELETE FROM Umfragen WHERE id = '$_GET[deleteumfrage]'";
        $del = mysql_query($del);
        $del = "DELETE FROM UmfragenA WHERE Umfrage = '$_GET[deleteumfrage]'";
        $del = mysql_query($del);
        echo "Umfrage gelöscht!<br><a href='Umfragen.php'>Zurück</a>";
    }
} elseif ($_GET['end']) {
    if ($dorfs->admin >= 3) {
        $ed = "UPDATE Umfragen SET Dran = 0 WHERE id = '$_GET[end]'";
        $ed = mysql_query($ed);
        echo "Umfrage beendet!<br><a href='Umfragen.php'>Zurück</a>";
    }
} elseif ($_GET['Answer']) {
    $sqls = "SELECT * FROM UmfragenA WHERE User = '$c_loged' AND Umfrage = '$_GET[Answer]'";
    $querys = mysql_query($sqls);
    $rows = mysql_fetch_array($querys);
    if ($rows["id"] < 1) {
        $sqls = "SELECT * FROM Umfragen WHERE id = '$_GET[Answer]' AND Land = ''";
        $querys = mysql_query($sqls);
        $rowsa = mysql_fetch_array($querys);

        $Okay = 0;
        if ($rowsa['Abrang'] > 0 and $dorfs2->Rang != "Akademist" and $dorfs2->Rang != "") {
            $Okay = 1;
        } elseif ($rowsa['Abrang'] == 0) {
            $Okay = 1;
        }
        $answering = 1;
        while ($answering < 21) {
            if (isset($_POST[$answering]) && $_POST[$answering] == 1) {
                $ins = "INSERT INTO UmfragenA (User, Umfrage, Antwort) VALUES ('$object->id', '$_GET[Answer]', '$answering')";
                $ins = mysql_query($ins) or die("Fehler beim Abstimmen!");
            }
            $answering++;
        }
        echo "Erfolgreich abgestimmt!<br><a href='Umfragen.php'>Zurück</a>";
    }
} elseif ($_GET['Frage']) {
    $abfrage2 = "SELECT * FROM Umfragen WHERE id LIKE '$_GET[Frage]' AND Land = ''";
    $ergebnis2 = mysql_query($abfrage2);
    $Umfrage = mysql_fetch_object($ergebnis2);
    $Okay = 0;
    if ($Umfrage->Abrang > 0 and $dorfs2->Rang != "Akademist" and $dorfs2->Rang != "") {
        $Okay = 1;
    } elseif ($Umfrage->Abrang == 0) {
        $Okay = 1;
    }
    if ($Umfrage->Dran == 0) {
        $gemacht = 1;
    }

    if ($Okay == 1) {
        echo "$Umfrage->Frage<br><br>";
        $sqls = "SELECT * FROM UmfragenA WHERE User = '$c_loged'";
        $querys = mysql_query($sqls);
        while ($rows = mysql_fetch_array($querys)) {
            if ($rows["Umfrage"] == $Umfrage->id) {
                $gemacht = 1;
            }
        }
        if ($object->id < 1) {
            $gemacht = 1;
        }

        if ($gemacht == 1) {
            $Menge = 0;
            $id = $_GET['Frage'];
            echo "<a href='Umfragen.php?Frage=$id&showDorf=1'>Auswertung nach Dorf</a> || <a href='Umfragen.php?Frage=$id&showRang=1'>Auswertung nach Rang</a> || <a href='Umfragen.php?Frage=$id&showActiv=1'>Auswertung nach Aktivit&auml;t</a> || <a href='Umfragen.php?Frage=$id'>Ohne Filter</a>";
            $sql = "SELECT COUNT(`id`) AS antwGesamt FROM UmfragenA WHERE Umfrage = $_GET[Frage]";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            $Menge = $row['antwGesamt'];
            $Menge /= 100;
            if ($showDorf || $showRang || $showActiv) {
                if ($showDorf) {
                    $krit = 'Heimatdorf';
                } elseif ($showRang) {
                    $krit = 'Rang';
                } elseif ($showActiv) {
                    $krit = 'Inaktivitaet';
                }
                echo "<table border='0' width='100%'><tr><td>Antwort:</td><td>Votes:</td><td>$krit</td></tr>";
                $Antworten = 0;
                $sql = 'SELECT count(u.`id`) as zahlAntwort,um.`Antwort`,u.`' . $krit . '` FROM `UmfragenA` um LEFT JOIN `user` u ON um.`User` = u.`id` WHERE `Umfrage` = \'' . $_GET['Frage'] . '\' GROUP BY `' . $krit . '`,`Antwort`';
                $query = mysql_query($sql);
                while ($row = mysql_fetch_array($query)) {
                    $antwort = 'Antwort' . $row['Antwort'];
                    $Antworten = $row['zahlAntwort'];
                    $Prozent = $Antworten;
                    $Prozent /= $Menge;
                    $Prozent = round($Prozent, 1);
                    echo "<tr><td>" . $Umfrage->$antwort . "</td><td>$Antworten ($Prozent%)</td><td>" . $row[$krit] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<table border='0' width='100%'><tr><td>Antwort:</td><td>Votes:</td></tr>";
                $answer = 1;

                while ($answer < 20) {
                    $antwort = 'Antwort' . $answer;
                    if (($Umfrage->$antwort) != '') {
                        $Antworten = 0;
                        $sql = "SELECT COUNT(`id`) as zahlAntwort FROM UmfragenA WHERE Umfrage = $_GET[Frage] AND `Antwort` = '$answer'";
                        $query = mysql_query($sql);
                        $row = mysql_fetch_array($query);
                        $Antworten = $row['zahlAntwort'];
                        $Prozent = $Antworten;
                        $Prozent /= $Menge;
                        $Prozent = round($Prozent, 1);
                        echo "<tr><td>" . $Umfrage->$antwort . "</td><td>$Antworten ($Prozent%)</td></tr>";
                    }
                    $answer++;
                }
                echo "</table>";
            }
        } else {
            echo "<form method='POST' action='Umfragen.php?Answer=$_GET[Frage]'><table border='0' width='100%'>";
            $answer = 1;
            while ($answer < 20) {
                $antwort = 'Antwort' . $answer;
                if ($Umfrage->$antwort != "") {
                    echo '<tr><td><input type=\'checkbox\' value=\'1\' name=\'' . $answer . '\'></td><td>' . $Umfrage->$antwort . '</td></tr>';
                }
                $answer++;
            }
            echo "</table><input type='submit' value='Abstimmen'></form>";
        }
    }
} elseif ($_GET['Machen']) {
    if ($object->admin >= 3) {
        $ins = "INSERT INTO Umfragen (Frage, Dran, Dauer, Antwort1, Antwort2, Antwort3, Antwort4, Antwort5, Antwort6, Antwort7, Antwort8, Antwort9, Antwort10, Antwort11, Antwort12, Antwort13, Antwort14, Antwort15, Antwort16, Antwort17, Antwort18, Antwort19, Antwort20) VALUES ('$_POST[Umfrage]', '1', '$_POST[Dauer]', '$_POST[Antwort1]', '$_POST[Antwort2]', '$_POST[Antwort3]', '$_POST[Antwort4]', '$_POST[Antwort5]', '$_POST[Antwort6]', '$_POST[Antwort7]', '$_POST[Antwort8]', '$_POST[Antwort9]', '$_POST[Antwort10]', '$_POST[Antwort11]', '$_POST[Antwort12]', '$_POST[Antwort13]', '$_POST[Antwort14]', '$_POST[Antwort15]', '$_POST[Antwort16]', '$_POST[Antwort17]', '$_POST[Antwort18]', '$_POST[Antwort19]', '$_POST[Antwort20]')";
        mysql_query($ins) or die("Fehler beim erstellen der Umfrage!");
        echo "Umfrage erfolgreich erstellt!<br><a href='Umfragen.php'>Zurück</a>";
    }
} elseif ($_GET['Mach']) {
    if ($object->admin >= 3) {
        echo "<form method='POST' action='Umfragen.php?Machen=1'>
        <input type='hidden' name='Machen' value='1'>
        Umfrage: <input type='text' name='Umfrage' size='50'><br>";
        $answer = 1;
        while ($answer < 20) {
            echo "Antwortmöglichkeit $answer: <input type='text' name='Antwort$answer' size='50'><br>";
            $answer++;
        }
        echo "Dauer der Umfrage: <input type='text' value='-1' name='Dauer'> Tage<br>
        <input type='submit' value='Erstellen'></form>";
    }
} else {
    if ($object->admin >= 3) {
        echo "<a href='Umfragen.php?Mach=1'>Umfrage erstellen</a><br><br>";
    }
    echo "<b>Derzeitige Umfragen:</b><br><br><table border='0' width='100%'>";

    $current_time = time();
    $select_registerDate = "SELECT reg_date FROM userdaten WHERE id = '" . $dorfs->id . "'";
    $select_registerDate = mysql_query($select_registerDate) or die(mysql_error($conn));
    $result = mysql_fetch_object($select_registerDate);
    $registerDate = (int)$result->reg_date;

    if (($registerDate + (30 * 24 * 60 * 60)) <= $current_time && $dorfs->is_mult === 0) {
        $sql = "SELECT * FROM Umfragen WHERE Dran = '1' AND Land = '' ORDER BY id DESC";
        $query = mysql_query($sql);
        while ($row = mysql_fetch_array($query)) {
            $dabei = 0;
            $Umfrage = $row["Frage"];
            $id = $row["id"];
            echo "<tr><td>";
            echo "<a href='Umfragen.php?Frage=$id'>$Umfrage</a></td>";
            $sqls = "SELECT * FROM UmfragenA WHERE User = '$c_loged'";
            $querys = mysql_query($sqls);
            while ($rows = mysql_fetch_array($querys)) {
                if ($rows["Umfrage"] == $row["id"]) {
                    $dabei = 1;
                }
            }
            echo "<td style=\"border-bottom:1px solid black;\">";
            if ($object->id < 1) {
                echo "-";
            } elseif ($dabei == 1) {
                echo "Bereits teilgenommen!";
            } else {
                echo "Noch nicht teilgenommen!";
            }
            if ($object->admin >= 3) {
                echo "<br><a href='Umfragen.php?deleteumfrage=$id'>Umfrage löschen</a><br><a href='Umfragen.php?end=$id'>Umfrage beenden</a><br>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "Du musst mindestens einen Monat registriert sein und darfst kein nicht genehmigter Multi sein, um an Umfragen teilnehmen zu können.<br /><br />";
    }

    echo "</table>";
    echo "<b>Vergangene Umfragen:</b><br><br><table border='0' width='100%'>";
    $sql = "SELECT * FROM Umfragen WHERE Land = '' AND Dran != '1' ORDER BY id DESC";
    $query = mysql_query($sql);
    while ($row = mysql_fetch_array($query)) {
        if ($row["Dran"] != 1) {
            $dabei = 0;
            $Umfrage = $row["Frage"];
            $id = $row["id"];
            echo "<tr>";
            echo "<td  style=\"border-bottom:1px solid black;\"><a href='Umfragen.php?Frage=$id'>$Umfrage</a></td>";
            $sqls = "SELECT * FROM UmfragenA WHERE User = '$c_loged'";
            $querys = mysql_query($sqls);
            while ($rows = mysql_fetch_array($querys)) {
                if ($rows["Umfrage"] == $row["id"]) {
                    $dabei = 1;
                }
            }
            echo "<td>";
            if ($object->admin >= 3) {
                echo "<br><a href='Umfragen.php?deleteumfrage=$id'>Umfrage löschen</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

get_footer();
