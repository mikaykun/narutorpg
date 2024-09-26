<?php

include(__DIR__ . "/../Menus/layout1.inc");

include(__DIR__ . "/../layouts/Overview/OverviewLand.php");
include(__DIR__ . "/../layouts/Overview/OverviewLand3.php");

$Einanderesland = htmlentities($Einanderesland);
$Einanderesland = str_replace("'", "\"", $Einanderesland);

echo "<tr><td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='6'><br>";

if ($Einanderesland != "") {
    $Landanzeige = $Einanderesland;
} else {
    $Landanzeige = $dorfs2->Heimatdorf;
}

echo "<b>$Landanzeige" . "gakure</b><br><br>";
echo "Auf dieser Seite findet ihr jegliche relevanten Informationen, die jedem Ninja aus eurem Dorf geläufig sein sollten, bzw. ihr erhaltet Anhaltspunkte, über welchen Wissensstand man als Ninja grundsätzlich verfügt und in der Öffentlichkeit vertreten sind.<br><br>";
echo "<a href='Regierung.php";

if ($Einanderesland != "") {
    echo "?Einanderesland=" . $dorfs2->Heimatdorf;
}

echo "'>Verbotene Jutsu und Dorfführung</a><br>";
echo "<a href='https://wiki.narutorpg.de/index.php?title=";

if ($Einanderesland != "") {
    echo "$Landanzeige";
}

echo "gakure'>Informationen über das Dorf</a><br>";
echo "</td></tr></table>";

get_footer();
