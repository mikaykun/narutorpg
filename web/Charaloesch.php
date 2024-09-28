<?php

include(__DIR__ . "/../Menus/layout1.inc");

include(__DIR__ . "/../layouts/Overview/Overview1.php");
include(__DIR__ . "/../layouts/Overview/OverviewDaten.php");

echo "<tr>
<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'><br>";

if ($delete) {
    $loesch = htmlentities($loesch);
    $pwlosch = htmlentities($pwlosch);
    $pwlosch = md5($pwlosch);

    if ($loesch == "LOESCHEN") {
        $abfrage2 = "SELECT * FROM userdaten WHERE id LIKE '$c_loged'";
        $ergebnis2 = mysql_query($abfrage2);
        $object = mysql_fetch_object($ergebnis2);

        if ($pwlosch == $object->pw) {
            $up = "UPDATE userdaten SET Timetoloesch = '6' WHERE id = '$c_loged'";
            $up = mysql_query($up);
            $date = date("d.m.Y, H:i");
            $IP = $_SERVER['REMOTE_ADDR'];
            $log = "User <b>$dorfs2->name</b> (IP = $IP) hat sich am $date für Löschungen eingetragen!<br>
					Mit dieser IP waren zu diesem Zeitpunkt folgende Personen online:";
            $eintrag = "INSERT INTO log (log) VALUES ('$log<br><br>')";
            $eintrag = mysql_query($eintrag) or die("Der Log konnte nicht erstellt werden!");
            echo "Dein Account ist nun für die Löschung eingeschrieben. Er wird in 5 Tagen gelöscht werden!";
        } else {
            echo "Das ist nicht das richtige Passwort!";
        }
    } else {
        echo "Sie m&uuml;ssen LOESCHEN in das obere Feld eingeben!";
    }
} else {
    echo "Wollen sie ihren Account wirklich l&ouml;schen?<br>
			<form action='Charaloesch.php?delete=1'>
			Hier LOESCHEN ein: <input name='loesch' type='text' id='loesch'><br>
			Hier das Passwort eingeben: <input name='pwlosch' type='password' id='pw'>
			<br><input type='submit' name='Submit' value='Account l&ouml;schen'>
			<input type='hidden' name='delete' value='1'>
			</form>";
}

echo "</td></tr></table>";

get_footer();
