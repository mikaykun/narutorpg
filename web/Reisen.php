<?php

include(__DIR__ . "/../Menus/layout1.inc");

include(__DIR__ . "/../layouts/Overview/OverviewLand.php");
include(__DIR__ . "/../layouts/Overview/OverviewReisen.php");

echo "<tr>
<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='6'><br>";
echo "<b><u>Reisen</u></b><br><br>";

if ($dorfs2->Rang != "") {
    $datetime = new DateTime();
    $datetime->setTime(0, 0);
    $datetime->modify('-5 days');
    $timelastmax = $datetime->getTimestamp();

    $sql = "SELECT id, LetzteReise FROM Reiseerlaubnisse WHERE LetzteReise > '$timelastmax' AND Ninja = '$dorfs2->id' ORDER BY LetzteReise DESC";
    $query = mysql_query($sql);
    $Letzte = mysql_fetch_object($query);
    if (is_object($Letzte) && $Letzte->id > 0) {
        $Letzte = floor((time() - $Letzte->LetzteReise) / (3600 * 24));
        echo "Deine letzte Reise ist erst $Letzte Tage her. Du darfst deinen Standort nur alle 5 Tage wechseln.<br><br>";
    } elseif ($Reisen == 1) {
        if ($Zielort == 0) {
            if ($dorfs2->Standort != "$dorfs2->Heimatdorf" . "gakure") {
                $up = "UPDATE user SET Standort = '$dorfs2->Heimatdorf" . "gakure' WHERE id = '$dorfs->id'";
                mysql_query($up);
                $timelastmax = time() - date("G") * 3600 - date("i") * 60 - date("s");
                $up = "UPDATE Reiseerlaubnisse SET LetzteReise = '$timelastmax' WHERE Ninja = '$dorfs2->id'";
                mysql_query($up);
                echo "Du befindest dich nun am Ort $dorfs2->Heimatdorf" . "gakure!<br><br>";
            }
        } else {
            $E_sql = "SELECT * FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND LF1 = '1' AND LF2 = '1' AND id = '$Zielort' AND Bis != '2'";
            $E_query = mysql_query($E_sql);
            $Erlaubnis = mysql_fetch_object($E_query);
            $sql = "SELECT id, Name FROM Reise_Orte WHERE id = '$Erlaubnis->Nach'";
            $query = mysql_query($sql);
            $Ort = mysql_fetch_object($query);
            if ($Erlaubnis->id > 0 and $Ort->id > 0 and $dorfs2->Standort != $Ort->Name) {
                $up = "UPDATE user SET Standort = '$Ort->Name' WHERE id = '$dorfs->id'";
                mysql_query($up);
                $timelastmax = time() - date("G") * 3600 - date("i") * 60 - date("s");
                $up = "UPDATE Reiseerlaubnisse SET LetzteReise = '$timelastmax' WHERE Ninja = '$dorfs2->id'";
                mysql_query($up);

                if ($Erlaubnis->Bis > 0) {
                    if ($Erlaubnis->Bis == 1) {
                        $up = "UPDATE Reiseerlaubnisse SET LF1 = '2', LF2 = '2', Bis = '2', Kommentar = 'Genutzt' WHERE Ninja = '$dorfs2->id'";
                        mysql_query($up);
                    } else {
                        $up = "UPDATE Reiseerlaubnisse SET Bis = Bis-1 WHERE Ninja = '$dorfs2->id'";
                        mysql_query($up);
                    }
                }

                echo "Du befindest dich nun am Ort $Ort->Name!<br><br>";
            }
        }
    } else {
        echo "<form method='POST' action='?Reisen=1'>";
        echo "Du kannst an folgende Orte reisen: <select name='Zielort'>";

        if ($dorfs2->Standort != "$dorfs2->Heimatdorf" . "gakure" and $dorfs2->Rang != "Missing-Nin") {
            echo "<option value='0'>$dorfs2->Heimatdorf" . "gakure";
        }

        $E_sql = "SELECT * FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND LF1 = '1' AND LF2 = '1' AND Bis != '2'";
        $E_query = mysql_query($E_sql);
        while ($Erlaubnis = mysql_fetch_object($E_query)) {
            $sql = "SELECT Name FROM Reise_Orte WHERE id = '$Erlaubnis->Nach'";
            $query = mysql_query($sql);
            $Ort = mysql_fetch_object($query);
            if ($Ort->Name != $dorfs2->Standort) {
                echo "<option value='$Erlaubnis->id'>$Ort->Name";
            }
        }

        echo "</select><br>
				<i>Du wirst sofort an den neuen Standort versetzt und kannst diesen dann für <b>5 Tage</b> nicht verlassen. Beachte außerdem, dass du die Reisezeit
				zwischen den Orten im RPG beachtest.</i><br>
				<input type='submit' value='Den Standort wechseln'>";
        echo "</form>";
    }

    echo "<b>Deine Reiseerlaubnisse:</b>";

    if ($AntragReise > 0) {
        $E_sql = "SELECT * FROM Reise_Orte WHERE Zugang LIKE '%|$dorfs2->Heimatdorf|%' AND Name != '$dorfs2->Heimatdorf" . "gakure' AND id = '$ZielortAntrag' ORDER BY Name";
        $E_query = mysql_query($E_sql);
        $Ort = mysql_fetch_object($E_query);
        $sql = "SELECT id FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND Nach = '$Ort->id' AND Bis != '2'";
        $query = mysql_query($sql);
        $Erlaubnis = mysql_fetch_object($query);
        if ($Erlaubnis->id < 1 and $Ort->id > 0) {
            if ($Ort->LF2 != "") {
                $LF2 = 0;
            } else {
                $LF2 = 1;
            }
            if ($Ort->Land == $dorfs2->Heimatdorf) {
                $Ort->Land = "";
                $LF2 = 1;
            }
            if ($Erlaubniszahl < 0 or $Erlaubniszahl > 1) {
                $Erlaubniszahl = 1;
            }
            $ins = "INSERT INTO Reiseerlaubnisse (Ninja, Nach, LF2, Land1, Land2, Kommentar, Bis) VALUES ('$dorfs2->id', '$Ort->id', '$LF2', '$dorfs2->Heimatdorf', '$Ort->Land', '$KommentarReise', '$Erlaubniszahl')";
            $ins = mysql_query($ins);
        } else {
            echo "<br><br>Du kannst keine Reiseerlaubnis an diesen Ort beantragen.<br><br>";
        }
    }

    if ($delEr) {
        $sql = "SELECT id, LF1, LF2 FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND id = '$delEr' AND Bis != '2'";
        $query = mysql_query($sql);
        $Erlaubnis = mysql_fetch_object($query);
        if ($Erlaubnis->LF1 == 0 or $Erlaubnis->LF2 == 0) {
            $del = "DELETE FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND id = '$Erlaubnis->id'";
            $del = mysql_query($del);
        }
    }

    echo "<table border='0' width='90%'>
		<tr>
		<td width='28%' background='/layouts/Uebergang/Oben.png'><b>Zielort</b></td>
		<td width='8%' background='/layouts/Uebergang/Oben.png' align='center'><b>LF 1</b></td>
		<td width='8%' background='/layouts/Uebergang/Oben.png' align='center'><b>LF 2</b></td>
		<td width='48%' background='/layouts/Uebergang/Oben.png'><b>Deine Anmerkung</b></td>
		<td width='8%' background='/layouts/Uebergang/Oben.png'></td>
		</tr>";
    $Zahl = 0;
    $E_sql = "SELECT * FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND Bis != '2'";
    $E_query = mysql_query($E_sql);
    while ($Erlaubnis = mysql_fetch_object($E_query)) {
        $sql = "SELECT Name FROM Reise_Orte WHERE id = '$Erlaubnis->Nach'";
        $query = mysql_query($sql);
        $Ort = mysql_fetch_object($query);
        if ($Zahl == 0) {
            echo "<tr>";
        }
        echo "
			<td><b>$Ort->Name</b>";
        if ($Erlaubnis->Bis == 1) {
            echo " (einmalig)";
        }
        echo "</td>";
        if ($Erlaubnis->LF1 == 1) {
            echo "<td align='center' bgcolor='#339900'><font color='#006600'>&#10004;</font></td>";
        } elseif ($Erlaubnis->LF1 == 2) {
            echo "<td align='center' align='center' bgcolor='#FF0000'><font color='#660000'>&#10008;</font></td>";
        } else {
            echo "<td align='center' bgcolor='silver'><font color='gray'><b>?</b></font></td>";
        }
        if ($Erlaubnis->LF2 == 1) {
            echo "<td align='center' bgcolor='#339900'><font color='#006600'>&#10004;</font></td>";
        } elseif ($Erlaubnis->LF2 == 2) {
            echo "<td align='center' align='center' bgcolor='#FF0000'><font color='#660000'>&#10008;</font></td>";
        } else {
            echo "<td align='center' bgcolor='silver'><font color='gray'><b>?</b></font></td>";
        }
        echo "<td>";
        $Post_Text = $Erlaubnis->Kommentar;
        include(__DIR__ . "/../Includes/Forum_replacements.php");
        $Erlaubnis->Kommentar = $Post_Text;
        $Erlaubnis->Kommentar = nl2br($Erlaubnis->Kommentar);
        echo "$Erlaubnis->Kommentar";
        echo "</td>";
        echo "<td align='center'>";
        if ($Erlaubnis->LF1 == 0 or $Erlaubnis->LF2 == 0) {
            echo "<a href='?delEr=$Erlaubnis->id'>Löschen</a>";
        } elseif ($Erlaubnis->LF1 == 1 and $Erlaubnis->LF2 == 1) {
            echo "<font color='#006600'><b>Erlaubt</b></font>";
        } else {
            echo "<font color='#660000'><b>Abgelehnt</b></font>";
        }
        echo "</td>";

        echo "</tr>";
    }
    echo "</table><br>";
    echo "<b>Reiseerlaubnis beantragen:</b><br><br>
		<form method='POST' action='?AntragReise=1'>
		<select name='Erlaubniszahl'>
		<option value='1' selected>Einmalige
		<option value='0'>Permanente
		</select> Reiseerlaubnis nach <select name='ZielortAntrag'>";
    $E_sql = "SELECT * FROM Reise_Orte WHERE Zugang LIKE '%|$dorfs2->Heimatdorf|%' AND Name != '$dorfs2->Heimatdorf" . "gakure' ORDER BY Name";
    $E_query = mysql_query($E_sql);
    while ($Ort = mysql_fetch_object($E_query)) {
        $sql = "SELECT id FROM Reiseerlaubnisse WHERE Ninja = '$dorfs->id' AND Nach = '$Ort->id' AND Bis != '2'";
        $query = mysql_query($sql);
        $Erlaubnis = mysql_fetch_object($query);
        if ($Erlaubnis->id < 1) {
            echo "<option value='$Ort->id'>$Ort->Name";
        }
    }

    echo "</select>

		<input type='submit' value='beantragen'><br><b>Anmerkung</b><br>
		<textarea name='KommentarReise' cols='70' rows='5'></textarea></form><br><br>";

    echo "<b>Übersicht über die verschiedenen Reisemöglichkeiten</b><br><br>";

    echo "<IFRAME name=Karte id=Karte src='Kampfscript/Weltkarte/SKarte.php' frameBorder=1 width=750 height=465></IFRAME>";
    echo "<IFRAME name=Info id=Info src='Kampfscript/Weltkarte/SInfo.php' frameBorder=1 width=750 height=300></IFRAME>";
} else {
    echo "Du darfst mit deinem Rang nicht reisen.";
}

echo "</td></tr></table>";

get_footer();
