<?php

$q = $_GET["q"];
$sql = "SELECT `name` FROM `user` WHERE `name` LIKE '$q%' AND `zeigen` = '' ORDER BY `name` DESC;";
$result2 = mysql_query($sql) or die("Invalid query");
$treffer = '';

while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
    $treffer = $treffer . "<a href='userpopup.php?usernam=" . $row["name"] . "'>" . $row["name"] . "</a><br>";
}

if ($treffer == '') {
    $antwort = "kein Treffer";
} else {
    $antwort = $treffer;
}

echo $antwort;
