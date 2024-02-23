<?php

declare(strict_types=1);

final class FightingOperation
{
    /**
     * @param int $MaliPerson Die id des Spielers, Tiers oder der Puppe
     * @param string $ArtRust Handelt es sich um: einen Spieler = ""
     * ein Inuzuka Tier = "Tier"
     * einen NPC = "NPC"
     * Eine Marionette = "Marionette"
     * Einen Zwilling = "Zwilling"
     */
    public static function malus(int $MaliPerson, string $ArtRust): array
    {
        $mali = [];

        if ($ArtRust == "") {
            $sql2 = "SELECT * FROM Item WHERE Von = '$MaliPerson' AND Angelegt != '' ORDER BY Item";
        } elseif ($ArtRust == "Tier") {
            $sql2 = "SELECT * FROM Item WHERE Angelegt LIKE 'Inu$MaliPerson %' ORDER BY Item";
        } elseif ($ArtRust == "Zwilling") {
            $sql2 = "SELECT * FROM Item WHERE Angelegt LIKE 'Sou%' AND Von = '$MaliPerson' ORDER BY Item";
        } else {
            $sql2 = "SELECT id, Item FROM Item WHERE Angelegt LIKE 'NPCDERSUPERMAN' ORDER BY Item";
        }
        $items = mysql_query($sql2);
        while ($Item = mysql_fetch_object($items)) {
            $Takeit = 0;

            if ($ArtRust == "") {
                if (str_contains((string)$Item->Angelegt, "Inu")) {
                    $Takeit = 1;
                }
                if (str_contains((string)$Item->Angelegt, "Sou")) {
                    $Takeit = 1;
                }
                if (str_contains((string)$Item->Angelegt, "Item:")) {
                    $Takeit = 1;
                }
                if (str_contains((string)$Item->Angelegt, "Puppe")) {
                    $Takeit = 1;
                }
            }

            if ($Takeit == 0) {
                $querys = mysql_query("SELECT Rüstung, Mali FROM Itemsk WHERE Name = '$Item->Item' LIMIT 1");
                $Itemk = mysql_fetch_object($querys);
                if ($Itemk->Mali != "") {
                    $Zahl = 0;
                    $Teiler = explode("&", (string)$Itemk->Mali);
                    while ($Teiler[$Zahl] != "") {
                        $Teile = explode("%", $Teiler[$Zahl]);
                        $Wert = $Teile[0];
                        $Hoehe = $Teile[1];

                        if (!isset($mali[$Wert])) {
                            $mali[$Wert] = 0;
                        }

                        $mali[$Wert] += (float)$Hoehe;

                        $Zahl += 1;
                    }
                }
            }
        }

        return $mali;
    }
}
