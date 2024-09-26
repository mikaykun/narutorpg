<?php

$sql = "SELECT `id`,`name`,`Lern` FROM user";
$query = mysql_query($sql);
while ($user = mysql_fetch_object($query)) {
    $myNiveau = new Niveau();
    $niveau = $myNiveau->getNiveau($user->id, $user->Lern);
    $punkte = $myNiveau->getPunkte();
    $name = $user->name;
    if ($niveau[1] == 2 && $punkte > 5 || $niveau[1] == 3 && $punkte > 15 || $punkte < 0) {
        $col = ($punkte < 0) ? 'red' : 'green';
        echo '<font color="' . $col . '">' . $name . " hat derzeit das Niveau " . $niveau[1] . ". Er m&uuml;sste das Niveau " . $niveau[0] . " haben und er hat " . $punkte . " Punkte.</font><br>";
    }
}
