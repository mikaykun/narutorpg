<?php

include __DIR__ . "/../Menus/layout1.inc";

if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 3 || $dorfs->CoAdmin == 4) {
    if ($neu) {
        $Date = date("d.m.Y <br> H:i");
        if ($_POST['Neuerung'] != "") {
            $time = time();
            mysql_query("INSERT INTO Neuerungen (Text, Datum, time) VALUES ('$_POST[Neuerung]', '$Date', '$time')") or die("Neuerung konnte nicht eingetragen werden!");
            echo "Neuerung eingetragen!<br><br>";
        }
    } elseif ($edit) {
        if (isset($id) && $_POST['Neuerung'] != "") {
            mysql_query("UPDATE Neuerungen SET Text = '$_POST[Neuerung]' WHERE id = $id") or die("Neuerung konnte nicht geändert werden!");
            echo "Neuerung geändert!<br><br>";
        }
    } elseif ($delete) {
        if (isset($id)) {
            mysql_query("DELETE FROM Neuerungen WHERE id = $id") or die("Neuerung konnte nicht gelöscht werden!");
            echo "Neuerung gelöscht!<br><br>";
        }
    }
}

if (($dorfs->admin >= 3 or $dorfs->CoAdmin == 3 || $dorfs->CoAdmin == 4) && isset($editMode) && isset($id)) {
    $sql = "SELECT Text FROM Neuerungen WHERE id = $id";
    $query = mysql_query($sql);
    $object = mysql_fetch_object($query);
    echo "<br><br><form method='post' action='Neuerungen.php?edit=1&id=$id'>
			<textarea rows='7' name='Neuerung' cols='90'>$object->Text</textarea><br>
			<input type='submit' value='Neuerung ändern'></form>";
    echo "<form onsubmit='return confirm(\"Wollen Sie wirklich diese Neuerung löschen?\");' method='post' action='Neuerungen.php?delete=1&id=$id'>
			<input type='submit' value='Neuerung löschen'></form>";
} else {
    echo "<b>Letzte Neuerungen:</b><br><br>";
    if ($Seiteschau < 1) {
        $Seiteschau = 1;
    }
    $Start = ($Seiteschau - 1) * 30;

    $sql = "SELECT COUNT(*) FROM Neuerungen";
    $query = mysql_query($sql);
    $row = mysql_fetch_row($query);
    $Zahl = $row[0];
    $Seiten = $Zahl / 50;
    $Seiten = ceil($Seiten);
    echo "<table border='0' width='100%'>";
    echo "<tr>
	<td colspan='3'>
	<table border='0' width='100%'>
	<tr>
	<td width='30%' align='center'>";
    $Newpage = $Seiteschau - 1;
    if ($Seiteschau > 1) {
        echo "<a href='?Seiteschau=$Newpage'><---</a>";
    }
    echo "</td>
	<td width='40%' align='center'>";
    $Zahle = 0;
    while ($Zahle < $Seiten) {
        $Zahle += 1;
        if ($Zahle > 1) {
            echo ", ";
        }
        if ($Zahle == $Seiteschau) {
            echo "<b>[$Zahle]</b>";
        } else {
            echo "<a href='?Seiteschau=$Zahle'>$Zahle</a>";
        }
    }
    echo "</td>
	<td width='30%' align='center'>";
    $Newpage = $Seiteschau + 1;
    if ($Seiteschau < $Seiten) {
        echo "<a href='?Seiteschau=$Newpage'>---></a>";
    }

    echo "</td>
	</tr>
	</table>
	</td>
	</tr></table>";

    $time = time();
    $up = "UPDATE user SET Neuerungen = '$time' WHERE id = '$c_loged'";
    $up = mysql_query($up);

    echo "<table border='0' width='99%'>";
    $sql = "SELECT * FROM Neuerungen ORDER BY id DESC LIMIT $Start, 30";
    $query = mysql_query($sql);
    while ($row = mysql_fetch_array($query)) {
        echo "<tr>";
        echo "<td background='/layouts/Uebergang/Oben.png'>";
        echo "<b>Änderung am " . $row["Datum"] . " Uhr</b> ";
        if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 3 || $dorfs->CoAdmin == 4) {
            echo "<a href='?editMode=true&id=" . $row['id'] . "'>[Edit]</a>";
        }
        echo "</td>";
        echo "</tr></tr><td background='/layouts/Uebergang/Untergrund.png'>";
        $Post_Text = convert_bbcode_basic($row['Text']);
        $Post_Text = convert_bbcode_spoiler($Post_Text);
        $Post_Text = convert_bbcode_fonts($Post_Text);
        $Post_Text = convert_bbcode_quote($Post_Text);
        $Post_Text = nl2br($Post_Text);
        echo $Post_Text;
        echo "</td>";
        echo "</tr>
			<tr><td>&nbsp;</td></tr>";
    }
    echo "</table>";

    if ($Seiteschau < 1) {
        $Seiteschau = 1;
    }
    $Start = ($Seiteschau - 1) * 30;

    $sql = "SELECT COUNT(*) FROM Neuerungen";
    $query = mysql_query($sql);
    $row = mysql_fetch_row($query);
    $Zahl = $row[0];
    $Seiten = $Zahl / 50;
    $Seiten = ceil($Seiten);
    echo "<table border='0' width='100%'>";
    echo "<tr>
	<td colspan='3'>
	<table border='0' width='100%'>
	<tr>
	<td width='30%' align='center'>";
    $Newpage = $Seiteschau - 1;
    if ($Seiteschau > 1) {
        echo "<a href='?Seiteschau=$Newpage'><---</a>";
    }
    echo "</td>
	<td width='40%' align='center'>";
    $Zahle = 0;
    while ($Zahle < $Seiten) {
        $Zahle += 1;
        if ($Zahle > 1) {
            echo ", ";
        }
        if ($Zahle == $Seiteschau) {
            echo "<b>[$Zahle]</b>";
        } else {
            echo "<a href='?Seiteschau=$Zahle'>$Zahle</a>";
        }
    }
    echo "</td><td width='30%' align='center'>";
    $Newpage = $Seiteschau + 1;
    if ($Seiteschau < $Seiten) {
        echo "<a href='?Seiteschau=$Newpage'>---></a>";
    }

    echo "</td>
	</tr>
	</table>
	</td>
	</tr></table>";

    if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 3 || $dorfs->CoAdmin == 4) {
        echo "<br><br><form method='post' action='Neuerungen.php?neu=1'>
			<textarea rows='7' name='Neuerung' cols='90'></textarea><br>
			<input type='submit' value='Neuerung eintragen'></form>";
    }
}

get_footer();
