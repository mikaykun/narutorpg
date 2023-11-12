<?php

class user
{
    public function mp($id)
    {
        $startTime =  time() - (6 * 31 * 24 * 60 * 60);
        $sql = "SELECT COUNT(`id`) AS idNum FROM `Missionen` WHERE `Ninja` LIKE '%$id%' AND `Abschlusszeit` > '$startTime'";
        $query = mysql_query($sql);
        $idC = mysql_fetch_array($query, MYSQL_ASSOC);
        $idNum = 6 - $idC['idNum'];
        return $idNum;
    }
}
