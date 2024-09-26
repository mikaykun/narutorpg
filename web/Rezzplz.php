<?php

include(__DIR__ . "/../Menus/layout1.inc");
$werteEnd = new tageZuWerte();
$tageback = $dorfs2->Tageback;
$spalte = 'Tageback';
$sql = "SELECT * FROM Besonderheiten WHERE id = '$dorfs2->id'";
$query = mysql_query($sql);
$u_Besonderheiten = mysql_fetch_object($query);
//echo 'Lass dein Niveau auf das Niveau deines alten Charakters setzen, bevor du deine Trainingstage verteilst.<br>';
$werteEnd->tageVerteil($dorfs2, $u_Besonderheiten, $chakra, $ausdauer, $geschw, $vert, $str, $tageback, $spalte, $sure, $verteil, 1, $geldT, $_POST);
