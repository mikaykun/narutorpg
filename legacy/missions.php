<?php

/**
 * Prüft, ob der Benutzer bereits alle Missionen gesehen hat
 *
 * @param int $userID
 * @return bool
 */
function hasSeenMissions(int $userID): bool
{
    $select = mysql_query("SELECT mission_read FROM userdaten WHERE id = '$userID'");
    $row = mysql_fetch_assoc($select);

    return (bool)$row['mission_read'];
}
