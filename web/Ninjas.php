<?php

include(__DIR__ . "/../Menus/layout1.inc");

$sql_select_mod = "SELECT `Mod` AS GameMod FROM userdaten WHERE id = '" . $_COOKIE['c_loged'] . "'";
$query_select_mod = mysql_query($sql_select_mod);
$mod = mysql_fetch_object($query_select_mod);

if ($RangNinja == "Gruppe") {
    echo "Es gibt folgende Ninjagruppen:";
    echo "<form method='POST' action='userpopup.php'><center>Gruppe suchen: <input type='text' name='Gruppezeigen'> <input type='submit' value='Suchen'></center></form>";
} else {
    echo "Den Rang \"<b>$RangNinja</b>\" belegen folgende Personen:";
    echo "<body onload=\"document.getElementById('theText').focus();\">";
    ?>
    <form id="usernameSearch" method='POST' action='userpopup.php'>
        <center>
            <table border='0'>
                <tr>
                    <td>Ninja suchen (ganzer Name):</td>
                    <td>
                        <div style="position:relative;overflow:visible">
                            <input name="theText" id="theText" type="text" autocomplete="off" onkeyup="editForm(this.value)">
                            <div id="livesearch"></div>
                        </div>
                    </td>
                    <td><input type="submit" value="Suchen"></td>
                </tr>
            </table>
        </center>
    </form>
    <?php
}

if ($RangNinja == "Jounin") {
    echo "<center><b>Spezial-Jounin sind mit einem (<i>S</i>) hinter dem Namen gekennzeichnet!</b></center>";
}

if ($RangNinja == "Gruppe") {
    echo "<center><table width='80%'  border='0'><br>";
    $Zahl = 0;
    while ($Zahl != 7) {
        $Zahl += 1;
        if ($Zahl == 1 or $Zahl == 4 or $Zahl == 7) {
            echo "<tr>";
        }
        echo "<td width='33%' valign='top'><ul>\n";
        if ($Zahl == 1) {
            $Land = "Konoha";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Konoha.bmp' border='0'>";
            $Anfuehrer = "Hokage";
        } elseif ($Zahl == 2) {
            $Land = "Suna";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Suna.bmp' border='0'>";
            $Anfuehrer = "Kazekage";
        } elseif ($Zahl == 3) {
            $Land = "Kumo";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Kumo.bmp' border='0'>";
            $Anfuehrer = "Raikage";
        } elseif ($Zahl == 4) {
            $Land = "Iwa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Iwa.bmp' border='0'>";
            $Anfuehrer = "Tsuchikage";
        } elseif ($Zahl == 5) {
            $Land = "Ame";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Ame.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 6) {
            $Land = "Taki";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Taki.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 7) {
            $Land = "Kusa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Kusa.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        }
        echo "</a>";
        echo "<style>#{$Land} { display:none; }</style>";
        echo "<div id='$Land'>";

        $sql = "SELECT Name FROM Teams WHERE Land = '$Land' ORDER BY Nummer";
        $result2 = mysql_query($sql) or die("Invalid query");
        while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            echo "<li>\n";
            echo "<A href='userpopup.php?Gruppezeigen=" . $row["Name"] . "'>";
            echo htmlentities($row["Name"]);
            echo "</a>\n";

            echo "</li>";
        }
        echo "</ul>\n</div></td>";
        if ($Zahl == 3 or $Zahl == 6 or $Zahl == 9) {
            echo "</tr>";
        }
    }
    echo "</table>";
} else {
    echo "<center><table width='80%'  border='0'><br>";
    $Zahl = 0;
    while ($Zahl != 9) {
        $Zahl += 1;
        if ($Zahl == 1 or $Zahl == 4 or $Zahl == 7) {
            echo "<tr>";
        }
        echo "<td width='33%' valign='top'><ul>\n";
        echo "";
        if ($Zahl == 1) {
            $Land = "Konoha";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Konoha.svg' border='0'>";
            $Anfuehrer = "Hokage";
        } elseif ($Zahl == 2) {
            $Land = "Suna";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Suna.bmp' border='0'>";
            $Anfuehrer = "Kazekage";
        } elseif ($Zahl == 3) {
            $Land = "Kumo";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Kumo.bmp' border='0'>";
            $Anfuehrer = "Raikage";
        } elseif ($Zahl == 4) {
            $Land = "Iwa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Iwa.bmp' border='0'>";
            $Anfuehrer = "Tsuchikage";
        } elseif ($Zahl == 5) {
            $Land = "Ame";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Ame.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 6) {
            $Land = "Taki";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Taki.svg' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 7) {
            $Land = "Kusa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Kusa.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 8) {
            $Land = $dorfs2->Standort;
            echo "<a href=\"javascript:show('S$Land');\">Ninja in $Land";
        } elseif ($Zahl == 9) {
            $Land = 'Missing';
            echo "<a href=\"javascript:show('S$Land');\">Missing Nins";
        }
        echo "</a>";
        if ($Zahl < 8) {
            echo "<style>#{$Land} { display:none; }</style>";

            $sql = "SELECT Kage FROM Regierung WHERE Land = '$Land'";
            $query = mysql_query($sql);
            $Regierung = mysql_fetch_object($query);
            echo "<div id='$Land'>";
            $such = "AND Heimatdorf = '$Land'";
        } else {
            echo "<style>#S{$Land} { display:none; }</style>";
            echo "<div id='S$Land'>";
            if ($Zahl < 9) {
                $such = "AND Standort = '$Land'";
            } else {
                $RangNinja = 'Missing-Nin';
                $such = "AND Niveau = '" . $rw . "'";
            }
        }

        $sql = "SELECT id, name, Spezial, Rangwert, Niveau, Angehoer, Standort, Rang, Heimatdorf FROM user WHERE Rang = '$RangNinja' $such AND zeigen = '' ORDER BY name";
        $result2 = mysql_query($sql) or die("Invalid query");
        while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            $multiQ = "SELECT `uId2` FROM `multi` WHERE (multOk = '0' OR `multOk` = '2') AND `uId1` = '" . $row['id'] . "' AND `Counter` > '1' AND `uId2` < `uId1`";
            $multiQ = mysql_query($multiQ);
            if ($multii = mysql_fetch_row($multiQ)) {
                $row['is_mult'] = 1;
            } else {
                $row['is_mult'] = 0;
            }

            $multiQ = "SELECT `uId1` FROM `multi` WHERE (multOk = '0' OR `multOk` = '2') AND `uId2` = '" . $row['id'] . "' AND `Counter` > '1' AND `uId1` < `uId2`";
            $multiQ = mysql_query($multiQ);
            if ($multii = mysql_fetch_row($multiQ)) {
                $row['is_mult'] = 1;
            }
            $rw = $row["Rangwert"];
            $sql = "SELECT Charerstellt FROM userdaten WHERE id = '" . $row['id'] . "'";
            $result3 = mysql_query($sql) or die("Invalid query");
            $usertime = mysql_fetch_array($result3, MYSQL_ASSOC);
            echo "<li>\n";
            echo "<A href='userpopup.php?usernam=" . $row["name"] . "'>";
            if ($row["Heimatdorf"] . "gakure" != $row["Standort"]) {
                echo "<i>";
            }
            if ($row["Niveau"] > $row["Rangwert"]) {
                echo "<b>";
            }
            echo htmlentities($row["name"]);
            $time = time() - (int)$usertime["Charerstellt"];
            if ($time < 60 * 60 * 24 * 14) {
                echo " <font color=red>(Neu)</font>";
            }
            if ($row['is_mult'] == 1) {
                echo " <font color=red>(Multi)</font>";
            }
            if ($row["Niveau"] > $row["Rangwert"]) {
                echo "</b>";
            }
            if ($row["Heimatdorf"] . "gakure" != $row["Standort"]) {
                echo "</i>";
            }
            echo "</a>\n";
            if ($row["Spezial"] == 1 and $RangNinja == "Jounin") {
                echo " (<i>S</i>)";
            }
            if ($Regierung->Kage == $row['name'] && $Zahl != 10) {
                echo " (<b>$Anfuehrer</b>)";
            }

            echo "</li>";
        }
        echo "</ul>\n</div></td>";

        if ($Zahl == 3 or $Zahl == 6 or $Zahl == 9) {
            echo "</tr>";
        }
    }
    echo "</table>";
}

get_footer();
