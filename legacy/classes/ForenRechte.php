<?php

final class forenrechte
{
    //$weg = 0: Rechte werden zugefügt, $weg = 1: Rechte werden genommen
    public function rechteUpRekursiv($fid, $uid, $weg, $rec)
    {
        if ($rec == 1) {
            $sql = "SELECT id FROM Forum_Foren WHERE Forum = '$fid'";
            $query = mysql_query($sql) or die('Forenrechte Fehler 0');
            while ($ufid = mysql_fetch_object($query)) {
                $this->rechteUpRekursiv($ufid->id, $uid, $weg, 1);
            }
        }
        if ($weg == 0) {
            $up = "UPDATE Forum_Foren SET `Moderation` = CONCAT(`Moderation`,'|$uid|') WHERE `Moderation` NOT LIKE '%|" . $uid . "|%' AND `id` = '$fid'";
            $up = mysql_query($up) or die('Forenrechte Fehler 1');
        } else {
            $up = "UPDATE Forum_Foren SET `Moderation` = REPLACE(`Moderation`,'|$uid|', '') WHERE `Moderation` LIKE '%$uid%' AND `id` = '$fid'";
            $up = mysql_query($up) or die('Forenrechte Fehler 2');
        }
        return true;
    }

    public function zugangUpRekursiv($fid, $uid, $weg, $rec)
    {
        if ($rec == 1) {
            $sql = "SELECT id FROM Forum_Foren WHERE Forum = '$fid'";
            $query = mysql_query($sql) or die('Forenrechte Fehler 3');
            while ($ufid = mysql_fetch_object($query)) {
                $this->rechteUpRekursiv($ufid->id, $uid, $weg, 1);
            }
        }
        if ($weg == 0) {
            $up = "UPDATE user SET `ForumErlaubnis` = CONCAT(`ForumErlaubnis`,'|$fid|') WHERE id = '$uid'";
            $up = mysql_query($up) or die('Forenrechte Fehler 4');
        } else {
            $up = "UPDATE user SET `ForumErlaubnis` = REPLACE(`ForumErlaubnis`,'|$fid|', '') WHERE `ForumErlaubnis` LIKE '%$fid%' AND `id` = '$uid'";
            $up = mysql_query($up) or die('Forenrechte Fehler 5');
        }
        return true;
    }
}
