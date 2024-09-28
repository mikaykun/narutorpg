<?php

include(__DIR__ . "/../Menus/layout1.inc");

if ($MitNPC > 0) {
    $sql = "SELECT land FROM Teams WHERE Leiter = '$c_loged' AND id = '$MitNPC'";
    $query = mysql_query($sql);
    $GRP = mysql_fetch_object($query);
    $Landhier = $GRP->land;
} else {
    $Landhier = $dorfs2->Heimatdorf;
}

echo "<b>News</b><br><a href='?'>Zurück</a><br>";
if ($Start == "") {
    $Start = 1;
}
$Starter = ($Start - 1) * 10;
$sql1 = "SELECT * FROM Landnews WHERE Land = '$Landhier' ORDER BY id DESC LIMIT $Starter, 10";
$query1 = mysql_query($sql1);
while ($News = mysql_fetch_object($query1)) {
    echo "<table border='0' width='700' cellpadding='0' cellspacing='0' background='/layouts/Uebergang/Untergrund.png'>";
    echo "<tr><td background='$dorfs->Farbeaussen'>";
    echo "<table border='0' width='250' align='left' cellpadding='0' cellspacing='0'>";
    $sql = "SELECT name FROM user WHERE id = '$News->Verfasser'";
    $query = mysql_query($sql);
    $Verfasser = mysql_fetch_object($query);
    $News->Titel = htmlentities($News->Titel);
    $News->Titel = nl2br($News->Titel);
    $News->Text = htmlentities($News->Text);
    $News->Text = nl2br($News->Text);
    echo "<tr><td width='0%'><b>Verfasser:</b></td><td width='100%'><a href='userpopup.php?usernam=$Verfasser->name'>$Verfasser->name</a></td></tr>";
    echo "<tr><td width='0%'><b>Datum:</b></td><td width='100%'>$News->Datum</td></tr>";
    echo "<tr><td width='0%'><b>Titel:</b></td><td width='100%'>$News->Titel</td></tr>";
    echo "</table>";
    echo "$News->Text</td></tr>";
    echo "<tr><td align='center'><a href='?editne=$News->id'>editieren</a> - <a href='?loene=$News->id'>löschen</a></td></tr>";
    echo "</table><br><br>";
}
$sql = "SELECT COUNT(*) FROM Landnews WHERE Land = '$Land->Land'";
$query = mysql_query($sql);
$Count = mysql_fetch_row($query);
$Zahl = $Count[0];
$Seiten = $Zahl / 10;
$Seiten = ceil($Seiten);
$Zahl = 1;
while ($Zahl <= $Seiten) {
    if ($Zahl != 1) {
        echo ", ";
    }
    $Starte = $Zahl;
    if ($Zahl == $Start) {
        echo "<b>[ $Zahl ]</b>";
    } else {
        echo "<a href='?Landnews=1&Start=$Starte'>$Zahl</a>";
    }
    $Zahl += 1;
}

get_footer();
