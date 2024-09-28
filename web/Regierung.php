<?php

include(__DIR__ . "/../Menus/layout1.inc");

include(__DIR__ . "/../layouts/Overview/OverviewLand.php");
include(__DIR__ . "/../layouts/Overview/OverviewLand3.php");

echo "<tr>
<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='6'><br>";

if ($dorfs2->id > 0) {
    $Land = "";
    $Landfuhr = "";


    $c_loged = $_COOKIE["c_loged"];
    $abfrage2 = "SELECT * FROM user WHERE id LIKE '$c_loged'";
    $ergebnis2 = mysql_query($abfrage2);
    $object = mysql_fetch_object($ergebnis2);
    $land = $object->Heimatdorf;
    if ($Einanderesland != "") {
        $land = $Einanderesland;
    } else {
        $land = $dorfs2->Heimatdorf;
    }
    $abfrage = "SELECT * FROM Regierung WHERE Land LIKE '$land'";
    $ergebnis = mysql_query($abfrage);
    $object2 = mysql_fetch_object($ergebnis);

    echo "<b>Regierung von $land</b>";
    echo "<br><br><b>";
    if ($land == "Konoha") {
        echo "Hokage: ";
    } elseif ($land == "Suna") {
        echo "Kazekage: ";
    } elseif ($land == "Iwa") {
        echo "Tsuchikage: ";
    } elseif ($land == "Kumo") {
        echo "Raikage: ";
    } else {
        echo "Oberhaupt: ";
    }
    echo "</b>";
    echo $object2->Kage;
    echo "<br><b>Assistenten:</b><br>";
    if ($object2->Helfer1 > 0) {
        $sql = "SELECT name FROM user WHERE id = '$object2->Helfer1'";
        $query = mysql_query($sql);
        $name = mysql_fetch_object($query);
        echo "<a href='userpopup.php?usernam=$name->name'>$name->name</a><br>";
    }
    if ($object2->Helfer2 > 0) {
        $sql = "SELECT name FROM user WHERE id = '$object2->Helfer2'";
        $query = mysql_query($sql);
        $name = mysql_fetch_object($query);
        echo "<a href='userpopup.php?usernam=$name->name'>$name->name</a><br>";
    }
    if ($object2->Helfer3 > 0) {
        $sql = "SELECT name FROM user WHERE id = '$object2->Helfer3'";
        $query = mysql_query($sql);
        $name = mysql_fetch_object($query);
        echo "<a href='userpopup.php?usernam=$name->name'>$name->name</a><br>";
    }
    if ($object2->Helfer4 > 0) {
        $sql = "SELECT name FROM user WHERE id = '$object2->Helfer4'";
        $query = mysql_query($sql);
        $name = mysql_fetch_object($query);
        echo "<a href='userpopup.php?usernam=$name->name'>$name->name</a><br>";
    }
    $abfrage = "SELECT * FROM Landdaten WHERE Land LIKE '$land'";
    $ergebnis = mysql_query($abfrage);
    $object3 = mysql_fetch_object($ergebnis);

    echo "<br><b>Verbotene Jutsu</b><br><br>";
    $Aufteilung = str_replace("||", "%", $object3->VerboteneJutsu);
    $Aufteilung = str_replace("|", "", $Aufteilung);

    $split = explode("%", $Aufteilung);
    $Zahl = 0;
    while ($split[$Zahl] != "") {
        $Name = str_replace("abba", " ", $split[$Zahl]);
        if ($Zahl > 0) {
            echo ", ";
        }
        echo "$Name";
        $Zahl += 1;
    }
}
echo "</td></tr></table>";

get_footer();
