<?php

include(__DIR__ . "/../Menus/layout1.inc");

$object = nrpg_get_current_user();

// TODO: google spreadsheet migrate in this project

if ($object->admin >= "2" || $dorfs2->Spielleiter == 1) {
    echo "<b><u>Kampfrichtercenter</u></b><br><br>";

    echo "<table border='0' width='75%'>";
    echo "<tr>
		<td colspan='50%'><a href='Kampfeintrag.php'>Kampf eintragen</a></td>
		<td colspan='50%'><a href='https://docs.google.com/spreadsheets/d/1HQZrPvEEhhfknJiZQIMrlPzaxOBYs0A_ClQmWsOKVNw/edit#gid=630562496'>Berechnungen</a></td>
		</tr>";
    echo "<tr>
		<td colspan='50%'><a href='Kampfkarte.php?Neu=1'>Kampf-Karte erstellen</a></td>
		</tr>";
    echo "</table>";

    echo "<br><br><b>Deine aktuellen Kampf-Karten:</b> <sup>[<a target='_blank' href='http://www.youtube.com/watch?v=4wpR3szYSr4'>Tutorial</a>]</sup><br><br>";

    if ($Karte > 0) {
        $sql1 = "SELECT id, Spieler FROM Kampf_Karte WHERE Kampfrichter = '$dorfs->id' AND id = '$Karte' ORDER BY id";
        $query1 = mysql_query($sql1);
        $Kampfrichterkram = mysql_fetch_object($query1);
        if ($Kampfrichterkram->id > 0) {
            $Leute = "";
            $split = explode("&&&%&%", $Kampfrichterkram->Spieler);
            $Splitter = 0;
            while ($split[$Splitter] != "") {
                if ($split[$Splitter] != $weg) {
                    $Leute = "$Leute" . "$split[$Splitter]&&&%&%";
                }

                $Splitter += 1;
            }
            $up = "UPDATE Kampf_Karte SET Spieler = '$Leute' WHERE id = '$Kampfrichterkram->id'";
            $up = mysql_query($up);
        }
    }

    if ($Kartezu > 0) {
        $sql1 = "SELECT id, Spieler FROM Kampf_Karte WHERE Kampfrichter = '$dorfs->id' AND id = '$Kartezu' ORDER BY id";
        $query1 = mysql_query($sql1);
        $Kampfrichterkram = mysql_fetch_object($query1);
        if ($Kampfrichterkram->id > 0) {
            $sql = "SELECT id, name FROM user WHERE name = '$theText'";
            $query = mysql_query($sql);
            $user = mysql_fetch_object($query);

            if ($user->id > 0) {
                $Leute = "$Kampfrichterkram->Spieler" . "$user->id&&&%&%";
                $up = "UPDATE Kampf_Karte SET Spieler = '$Leute' WHERE id = '$Kampfrichterkram->id'";
                $up = mysql_query($up);
            }
        }
    }

    if ($Loeschkarte > 0) {
        $sql1 = "SELECT id, Spieler FROM Kampf_Karte WHERE Kampfrichter = '$dorfs->id' AND id = '$Loeschkarte'";
        $query1 = mysql_query($sql1);
        $Kampfrichterkram = mysql_fetch_object($query1);
        if ($Kampfrichterkram->id > 0) {
            echo "<b>Kampf-Karte wirklich löschen?</b><br>
				<a href='?Loeschkarteja=$Kampfrichterkram->id'>Ja, löschen!</a><br><br>";
        }
    }

    if ($Loeschkarteja > 0) {
        $sql1 = "SELECT id, Spieler FROM Kampf_Karte WHERE Kampfrichter = '$dorfs->id' AND id = '$Loeschkarteja'";
        $query1 = mysql_query($sql1);
        $Kampfrichterkram = mysql_fetch_object($query1);
        if ($Kampfrichterkram->id > 0) {
            $del = "DELETE FROM Kampf_Karte WHERE id = '$Kampfrichterkram->id'";
            $del = mysql_query($del);
            $del = "DELETE FROM Kampf_Karte_Anzeigen WHERE Karte = '$Kampfrichterkram->id'";
            $del = mysql_query($del);
        }
    }

    echo "<table border='0' width='90%'>";
    echo "<tr>";
    echo "<td width='40%'><b>Kämpfer</b></td>";
    echo "<td width='40%'><b>Karte</b></td>";
    echo "<td width='20%'><b>Löschen</b></td>";
    echo "</tr>";
    $sql1 = "SELECT id, Spieler FROM Kampf_Karte WHERE Kampfrichter = '$dorfs->id' ORDER BY id";
    $query1 = mysql_query($sql1);
    while ($Kampfrichterkram = mysql_fetch_object($query1)) {
        echo "<tr>";
        echo "<td>";

        $split = explode("&&&%&%", $Kampfrichterkram->Spieler);
        $Splitter = 0;
        while ($split[$Splitter] != "") {
            if ($Splitter != 0) {
                echo ", ";
            }
            $sql = "SELECT id, name FROM user WHERE id = '$split[$Splitter]'";
            $query = mysql_query($sql);
            $user = mysql_fetch_object($query);
            echo "<a href='?Karte=$Kampfrichterkram->id&weg=$split[$Splitter]'>$user->name</a>";

            $Splitter += 1;
        }
        echo "<body onload=\"document.getElementById('theText').focus(); createAutoComplete();\">";
        echo "<br><form method='POST' action='?Kartezu=$Kampfrichterkram->id&Neuer=1'>
			<table border='0'>
			<tr>
			<td>Name:</td>
			<td>
			 <input name=theText id=\"theText\" type=text autocomplete=off onkeyup=\"editForm(this.value)\">
																			<div id=\"livesearch\"></div></td>
			</tr>
			</table>
			<input type='submit' value='Zufügen'></form>";

        echo "</td>";
        echo "<td><a href='Kampfscript/Kartenscript/ScriptKR.php?Kampfkarte=$Kampfrichterkram->id'>Zur Karte</a></td>";
        echo "<td><a href='?Loeschkarte=$Kampfrichterkram->id'>Löschen</a></td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Sie haben keine Rechte diese Seite zu betreten!";
}

get_footer();
