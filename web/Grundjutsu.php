<?php

include(__DIR__ . "/../Menus/layout1.inc");
include(__DIR__ . "/../layouts/Overview/Overview1.php");
include(__DIR__ . "/../layouts/Overview/OverviewNinja.php");

echo "<tr>
<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'><br>";

$query = mysql_query("SELECT * FROM Jutsuk WHERE id = {$dorfs->id}") or die("Fehler beim wählen der Jutsu");
$object = mysql_fetch_object($query);

$rofl = mysql_query("SELECT * FROM Fähigkeiten WHERE id = '$dorfs2->id'");
$u_faehs = mysql_fetch_array($rofl);
$query = mysql_query("SELECT * FROM Jutsuk WHERE id = '$dorfs2->id'");
$u_Jutsu = mysql_fetch_assoc($query);

$tps = new tpKosten();
echo $tps->thisIsOkGUI($u_Jutsu, $dorfs2);

$jutsutyp = [
    'Taijutsu' => [0, 'Taijutsu\' OR Jutsutyp = \'Sonstiges'],
    'Ninjutsu' => [1, 'Ninjutsu\' OR Jutsutyp = \'Siegel'],
    'Genjutsu' => [0, 'Genjutsu'],
];

foreach ($jutsutyp as $jutsuart => $jutsuArtEigenschaften) {
    echo "<br><b><u>$jutsuart</u></b><br><br>";
    echo '<table id="' . $jutsuart . '" class="tablesorter">
<thead>
<tr>
    <th>Name</th>
    <th>Rang</th>
    <th>Clan/Bluterbe</th>
    <th>Chakrakosten</th>
    <th>Rundenkosten</th>';
    if ($jutsuart == 'Ninjutsu') {
        echo '<th>Element</th>';
        echo '<th>Ausdauerkosten</th>';
    } elseif ($jutsuart == 'Taijutsu') {
        echo '<th>Ausdauerkosten</th>';
    } elseif ($jutsuart == 'Genjutsu') {
        echo '<th>Jutsustufe</th>';
    }
    echo '<th>Wirkung</th>
</tr>
</thead>
<tbody>';
    $sql = "SELECT id, Name, Beschreibung, Geschwindigkeit, Schaden, Element, Clan, ChakraFB, Chakra2FB, AusdauerFB, Infodazu, Jutsutyp, Taijutsu, Ninjutsu, Genjutsu FROM Jutsu WHERE Jutsutyp = '" . $jutsuArtEigenschaften[1] . "' ORDER BY Name";
    $result2 = mysql_query($sql) or die("Invalid query");
    while ($Jutsu = mysql_fetch_object($result2)) {
        $Jutsun = $Jutsu->Name;
        $Jutsuk = $object->$Jutsun ?? 0;
        if ($Jutsuk > 0) {
            $Jutsurang2 = 0;
            if ($Jutsu->Taijutsu > $Jutsurang2) {
                $Jutsurang2 = $Jutsu->Taijutsu;
            }
            if ($Jutsu->Ninjutsu > $Jutsurang2) {
                $Jutsurang2 = $Jutsu->Ninjutsu;
            }
            if ($Jutsu->Genjutsu > $Jutsurang2) {
                $Jutsurang2 = $Jutsu->Genjutsu;
            }
            if ($Jutsurang2 <= 1) {
                $Jutsurang = "E-Rang";
            } elseif ($Jutsurang2 <= 2) {
                $Jutsurang = "D-Rang";
            } elseif ($Jutsurang2 <= 4) {
                $Jutsurang = "C-Rang";
            } elseif ($Jutsurang2 <= 6) {
                $Jutsurang = "B-Rang";
            } elseif ($Jutsurang2 <= 8) {
                $Jutsurang = "A-Rang";
            } elseif ($Jutsurang2 <= 10) {
                $Jutsurang = "S-Rang";
            }

            if ($Jutsu->Jutsutyp == "Siegel") {
                // Set Element to Siegel so it'll display Siegel instead of Element and set the correct color as defined in the array below
                $Jutsu->Element = "Siegel";
            }

            $elementColors = [
                "Keins" => "",
                "Siegel" => "9933CC",
                "Donner" => "F4FA58",
                "Wasser" => "0000FF",
                "Feuer" => "FF0000",
                "Erde" => "CC3300",
                "Wind" => "008000",
                "Holz" => "993300",
                "Eis" => "81F7F3",
                "Sand" => "FFCC00",
                "Futton" => "BFFF00",
                "Ranton" => "F781D8",
                "Youton" => "FF6600",
            ];
            $Jutsusn = str_replace("abba", " ", $Jutsun);
            echo "<tr><td>$Jutsusn</td><td>$Jutsurang</td><td>$Jutsu->Clan</td>";
            echo "<td>";
            if ($Jutsu->ChakraFB > 0) {
                $Kosten = $Jutsu->ChakraFB;
                if ($Jutsu->Element == $dorfs2->Element1 || $Jutsu->Element == $dorfs2->Element2) {
                    if ($dorfs2->Clan == "Hyouton Bluterbe" or $dorfs2->Clan == "Mokuton Bluterbe" or $dorfs2->Clan == "Ranton Bluterbe" or $dorfs2->Clan == "Futton Bluterbe" or $dorfs2->Clan == "Youton Bluterbe") {
                        $Kosten *= 0.95;
                    }
                }
                $Kosten = round((int)$Kosten);
                echo $Kosten;
            }
            echo '</td><td>';
            if ($Jutsu->Chakra2FB > 0) {
                $Kosten = $Jutsu->Chakra2FB;
                if ($Jutsu->Element == $dorfs2->Element1 || $Jutsu->Element == $dorfs2->Element2) {
                    if ($dorfs2->Clan == "Hyouton Bluterbe" or $dorfs2->Clan == "Mokuton Bluterbe" or $dorfs2->Clan == "Ranton Bluterbe" or $dorfs2->Clan == "Futton Bluterbe" or $dorfs2->Clan == "Youton Bluterbe") {
                        $Kosten *= 0.95;
                    }
                }
                $Kosten = round((int)$Kosten);
                echo $Kosten;
            }
            echo '</td>';
            if ($jutsuart == 'Ninjutsu') {
                echo "<td><font style='color:" . $elementColors[$Jutsu->Element] . ";'><b>$Jutsu->Element</b></font></td>";
            }
            if ($jutsuart == 'Ninjutsu' || $jutsuart == 'Taijutsu') {
                echo '<td>';
                if ($Jutsu->AusdauerFB > 0) {
                    $Kosten = $Jutsu->AusdauerFB;
                    echo $Kosten;
                }
                echo '</td>';
            }
            if ($jutsuart == 'Genjutsu') {
                echo '<td>' . $Jutsurang2 . '</td>';
            }
            $Schaden = $Jutsu->Schaden;
            $Schaden = nl2br($Schaden);
            $Schaden = preg_replace("/\r|\n/s", "", $Schaden);
            $Schaden = str_replace("'", "\'", $Schaden);
            $Schaden = str_replace('"', '\"', $Schaden);
            echo "</td><td><div id='Wirkung$Jutsun'><a href='javascript:ZeigWirk$Jutsun();'><font color='black'>Wirkung anzeigen</font></a> - <a href='Jutsu.php?watchjutsu=$Jutsu->id' target='_blank'><font color='black'>Ausführlich</font></a></div>";

            $PWKRKRAM = filter_input(INPUT_GET, 'PWKRKRAM', FILTER_SANITIZE_SPECIAL_CHARS);
            echo "<SCRIPT>
				function ZeigWirk$Jutsun()
				{
					Wirkung$Jutsun.innerHTML = \"<a href='javascript:WegWirk$Jutsun();'><font color='black'></u>Wirkung ausblenden</font></a> - <a href='Jutsu.php?watchjutsu=$Jutsu->id&PWyougotta=$PWKRKRAM' target='_blank'><font color='black'>Ausführlich</font></a><br>$Schaden\";
				}

				function WegWirk$Jutsun()
				{
					Wirkung$Jutsun.innerHTML = \"<a href='javascript:ZeigWirk$Jutsun();'><font color='black'></u>Wirkung anzeigen</font></a> - <a href='Jutsu.php?watchjutsu=$Jutsu->id&PWyougotta=$PWKRKRAM' target='_blank'><font color='black'>Ausführlich</font></a>\";
				}
                </SCRIPT></td></tr>";
        }
    }
    echo '</tbody></table>';
}

get_footer();
