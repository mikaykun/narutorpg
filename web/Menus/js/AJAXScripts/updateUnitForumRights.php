<?php

// Updatet die Forenzugänge des Einheitenforums auf die aktuellen Einheitenmitglieder

function splitStringIntoArray($string): array
{
    $mitglieder_raw_1 = str_replace("||", ",", $string);
    $mitglieder_raw_2 = str_replace("|", "", $mitglieder_raw_1);
    return explode(",", $mitglieder_raw_2);
}

if (empty($_POST['einheit_id']) || empty($_POST['forum_id'])) {
    echo "Error while updating the rights for the forum..";
} else {
    $einheit_id = $_POST['einheit_id'];
    $forum_id = $_POST['forum_id'];

    $select_sql = "SELECT Mitglieder FROM Einheiten WHERE id = '" . $einheit_id . "'";
    $select_query = mysql_query($select_sql);
    if (mysql_num_rows($select_query) == 0) {
        echo 'Die Einheit hat keine Mitglieder';
    } else {
        $mitglieder_raw = mysql_fetch_object($select_query);
        $mitglieder_raw = $mitglieder_raw->Mitglieder;

        $mitglieder_array = splitStringIntoArray($mitglieder_raw);

        $n = 0;
        $mitglieder = "";
        while ($mitglieder_array[$n] != "") {
            $position = strpos((string) $mitglieder_array[$n], "NPC:");
            if ($position === false) {
                $mitglied = $mitglieder_array[$n];
            } else {
                $mitglieder_array[$n] = str_replace("NPC:", "", $mitglieder_array[$n]);
                $select_sql = "SELECT User FROM NPC WHERE NPC = '" . $mitglieder_array[$n] . "'";
                $select_query = mysql_query($select_sql) or die("Error0: " . mysql_error($conn));
                $result = mysql_fetch_object($select_query);

                $mitglied = $result->User;
            }
            $select_sql = "SELECT ForumErlaubnis FROM user WHERE id = '" . $mitglied . '\'';
            $select_query = mysql_query($select_sql) or die("Error1:" . mysql_error($conn));
            $zugang = mysql_fetch_array($select_query);
            $zugang_array = splitStringIntoArray($zugang[0]);

            foreach ($zugang_array as $value) {
                if ($value != $forum_id) { //Prüfen, ob $value gleich $forum_id ist
                    if ($value == end($zugang_array)) { //Nun gucken, ob $value das letzte Element des Arrays ist
                        $zugang = trim((string) $zugang[0]) . "|" . $forum_id . "|"; //Wenn ja: Update
                        $update_sql = "UPDATE user SET ForumErlaubnis = '" . $zugang . "' WHERE id = '" . $mitglied . "'";
                        $update_query = mysql_query($update_sql) or die("Error2: " . mysql_error($conn));
                    }
                } //Wenn $value == $forum_id, dann break
                else {
                    break;
                }
            }
            $n++;
        }
        echo "Die Zug&auml;nge des Einheitenforums wurde auf die aktuelle Mitgliederliste geupdatet";
    }
}
