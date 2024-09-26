<?php

include(__DIR__ . "/../Menus/layout1.inc");

echo '<h1>F&auml;higkeiten&uuml;bersicht:</h1>';
echo '<table id="faehs" class="tablesorter">
<thead>
<tr>
    <th>Name</th>
    <th>Gegenangriff</th>
    <th>Mehrfachschlag</th>
    <th>Max</th>
    <th>Alles erfüllt</th>
</tr>
</thead>
<tbody>';
$sql = "SELECT *,`jk`.`id` AS id FROM `Jutsuk` jk
LEFT JOIN `Fähigkeiten` f ON jk.`id` = f.`id`
WHERE `Gegenangriff` >=1 OR `Mehrfachschlag` >=1";
$query = mysql_query($sql);
$tps = new tpKosten();
while ($faeh = mysql_fetch_array($query)) {
    $sql = "SELECT * FROM `user` WHERE `id` = " . $faeh['id'];
    $query2 = mysql_query($sql);
    $user = mysql_fetch_object($query2);
    $tps->thisIsOkMax($faeh, $user);
    $maxOk = $tps->getOkMax();
    $maxOk = $maxOk['Taijutsu'];
    $okTai = [1 => 4, 2 => 6, 3 => 8, 4 => 10];
    if ($okTai[$faeh['Mehrfachschlag']] > $maxOk || $okTai[$faeh['Gegenangriff']] > $maxOk) {
        $ok = 'Nein';
    } else {
        $ok = 'Ja';
    }
    echo '<tr><td>' . $user->name . '</td>' . '<td>' . $faeh['Gegenangriff'] . '(' . $okTai[$faeh['Gegenangriff']] . ')</td>' .
        '<td>' . $faeh['Mehrfachschlag'] . '(' . $okTai[$faeh['Mehrfachschlag']] . ')</td>' . '<td>' . $maxOk . '</td>' . '<td>' . $ok . '</td>';

    echo '</tr>';
}
echo '</tbody></table>';

get_footer();
