<?php

// Dokument welches ein neues Einheiten Forum über Ajax erstellt

if (empty($_POST['name']) || empty($_POST['mod'])) {
    echo "Error while creating the forum..";
} else {
    $name = mysql_real_escape_string($_POST['name']);
    $einheitName = "Einheit:" . $name;
    $mod = mysql_real_escape_string($_POST['mod']);
    $select_kage = "SELECT id FROM user WHERE name = '" . $mod . "'";
    $select_kage = mysql_query($select_kage) or die(mysql_error($conn));
    $mod1 = mysql_fetch_object($select_kage);
    $mod = $mod1->id;
    if (!isset($mod1->id) || $mod1->id === false) {
        $select_sql = "SELECT User FROM NPC WHERE NPC = '" . $mod . "'";
        $select_query = mysql_query($select_sql) or die("Error0: " . mysql_error($conn));
        $result = mysql_fetch_object($select_query);
        $mod = $result->User;
    }
    $einheitMod = "|" . $mod . "|";

    $create_sql = "INSERT INTO `Forum_Foren` (`Name` , `Beschreibung` , `Kategorie` , `Forum` , `Rang` , `Zugangtabelle` , `ZugangSpalte` , `ZugangWert` , `Showifhidden` , `Themen` , `Beiträge` , `Lastposttime` , `Postsgelten`, `Moderation` ) VALUES ('$einheitName', '', '4', '', '1', 'Adminlog', 'Was', 'Krarakek', '1', '0', '0', '', '', '$einheitMod')";
    $create_query = mysql_query($create_sql) or die(mysql_error($conn));

    $select_sql = "SELECT id, Name FROM Forum_Foren WHERE Name = '" . $einheitName . "'";
    $select_query = mysql_query($select_sql) or die(mysql_error($conn));
    $result = mysql_fetch_object($select_query);
    $forum_id = $result->id;

    $select_sql = "SELECT id FROM Einheiten WHERE Name = '" . $name . "'";
    $select_query = mysql_query($select_sql);
    $result = mysql_fetch_object($select_query);
    $einheit_id = $result->id;

    $array = [0 => $einheit_id, 1 => $forum_id, 2 => "Das Forum f&uuml;r die Einheit " . $name . " wurde erfolgreich erstellt!"];
    echo json_encode($array);
}
