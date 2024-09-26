<?php

include(__DIR__ . "/../Menus/layout1.inc");

$c_loged = $_COOKIE["c_loged"];
$abfrage2 = "SELECT * FROM user WHERE id = '$c_loged'";
$ergebnis2 = mysql_query($abfrage2);
$object = mysql_fetch_object($ergebnis2);
if ($object->Clan == "Ningyosenshu Clan") {
    $sql = "SELECT * FROM Puppen WHERE id = '$Puppe'";
    $query = mysql_query($sql);
    $object2 = mysql_fetch_object($query);
    if ($Nr == 1) {
        $Panzer = "Panzerung1";
        $panz = $object2->Panzerung1;
        if ($panz == "Holz") {
            $minus = 20;
        }
        if ($panz == "Eisen") {
            $minus = 50;
        }
        if ($panz == "Stahl") {
            $minus = 75;
        }
        if ($panz == "Titan") {
            $minus = 100;
        }
    }
    if ($Nr == 2) {
        $Panzer = "Panzerung2";
        $panz = $object2->Panzerung2;
        if ($panz == "Holz") {
            $minus = 20;
        }
        if ($panz == "Eisen") {
            $minus = 50;
        }
        if ($panz == "Stahl") {
            $minus = 75;
        }
        if ($panz == "Titan") {
            $minus = 100;
        }
    }
    if ($Nr == 3) {
        $Panzer = "Panzerung3";
        $panz = $object2->Panzerung3;
        if ($panz == "Holz") {
            $minus = 20;
        }
        if ($panz == "Eisen") {
            $minus = 50;
        }
        if ($panz == "Stahl") {
            $minus = 75;
        }
        if ($panz == "Titan") {
            $minus = 100;
        }
    }
    if ($Nr == 4) {
        $Panzer = "Panzerung4";
        $panz = $object2->Panzerung4;
        if ($panz == "Holz") {
            $minus = 20;
        }
        if ($panz == "Eisen") {
            $minus = 50;
        }
        if ($panz == "Stahl") {
            $minus = 75;
        }
        if ($panz == "Titan") {
            $minus = 100;
        }
    }
    if ($Nr == 5) {
        $Panzer = "Panzerung5";
        $panz = $object2->Panzerung5;
        if ($panz == "Holz") {
            $minus = 20;
        }
        if ($panz == "Eisen") {
            $minus = 50;
        }
        if ($panz == "Stahl") {
            $minus = 75;
        }
        if ($panz == "Titan") {
            $minus = 100;
        }
    }
    $maxlp = $object2->MLP;
    $maxlp -= $minus;
    $lp = $object2->LP;
    $lp -= $minus;
    if ($lp < 1) {
        $lp = 0;
    }
    $aendern = "UPDATE Puppen SET $Panzer = '' WHERE id = '$Puppe'";
    $update = mysql_query($aendern) or die("Fehler beim verschrotten der Panzerung! 1");
    $aendern = "UPDATE Puppen SET MLP = '$maxlp' WHERE id = '$Puppe'";
    $update = mysql_query($aendern) or die("Fehler beim verschrotten der Panzerung! 2");
    $aendern = "UPDATE Puppen SET LP = '$lp' WHERE id = '$Puppe'";
    $update = mysql_query($aendern) or die("Fehler beim verschrotten der Panzerung! 3");
    echo "Panzerung verschrottet!<br><a href='Skills.php'>Zur&uuml;ck</a>";
}

get_footer();
