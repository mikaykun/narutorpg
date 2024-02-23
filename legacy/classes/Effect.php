<?php

final class Effect
{
    public int $Id = 0;
    public $Name;
    public $Description;
    public $Costs;
    public $Rank;
    public $maxVal;
    public $IsPublic;
    public $Group;
    public $GroupId;
    public $UserId;
    public $IsAdvantage;
    public $IsUpToDate;
    public $freeAction;
    public $kindOfCosts;
    public $connectionGroup;
    public $affectAll;

    public function showBorders($max): array
    {
        $borderTop = [1,4,6,12,20];
        $maxBorder = 30;
        foreach ($borderTop as $step => $border) {
            $borderTop[$step] = ($border / $maxBorder) * $max;
        }

        return $borderTop;
    }

    public function rankEcho($max): string
    {
        $values = $this->showBorders($max);
        $ranks = ['E','D','C','B','A'];
        $myEcho = 'Maximalwerte: ';
        foreach ($ranks as $step => $rank) {
            $myEcho .= $rank . ': ' . floor($values[$step]) . ', ';
        }
        $myEcho .= 'S: ' . $max;
        return $myEcho;
    }

    public function SetValuesWithGroup(array $get, $group): void
    {
        $this->Id = $get["id"];
        $this->Name = $get["name"];
        $this->Description = htmlspecialchars_decode($this->UrlFix($get["description"]));
        $this->Costs = $get["costs"];
        $this->Rank = $get["rank"];
        $this->maxVal = $get['maxVal'];
        $this->IsPublic = $get["isPublic"];
        $this->Group = $group;
        $this->GroupId = $get["groupId"];
        $this->UserId = $get["userId"];
        $this->freeAction = $get["freeAction"];
        $this->kindOfCosts = $get["kindOfCosts"];
        $this->IsAdvantage = $get["isAdvantage"];
        $this->IsUpToDate = $get["isUpToDate"];
        $this->connectionGroup = $get["connectionGroup"];
        $this->affectAll = $get["affectAll"];
    }

    public function SetValues(array $get): void
    {
        $this->Id = $get["id"];
        $this->Name = $get["name"];
        $this->Description = htmlspecialchars_decode($this->UrlFix((string)$get["description"]));
        $this->Costs = $get["costs"];
        $this->Rank = $get["rank"];
        $this->maxVal = $get['maxVal'];
        $this->IsPublic = $get["isPublic"];
        $this->GroupId = $get["groupId"];
        $this->UserId = $get["userId"];
        $this->freeAction = $get["freeAction"];
        $this->kindOfCosts = $get["kindOfCosts"];
        $this->IsAdvantage = $get["isAdvantage"];
        $this->IsUpToDate = $get["isUpToDate"];
        $this->connectionGroup = $get["connectionGroup"];
        $this->affectAll = $get["affectAll"];
    }

    public function GetUserByEffect()
    {
        $userItemsSelect = "select * from eeEffectsItem efi
			LEFT JOIN `itemFaeh` i ON `efi`.`iId` = `i`.`iId`
			LEFT JOIN `user` u ON `i`.`uId` = u.`id` where efi.eId = '" . $this->Id . "'";
        $userItemsResult = mysql_query($userItemsSelect);
        $user = [];

        while ($userItem = mysql_fetch_array($userItemsResult)) {
            $user[$userItem['id']] = $userItem['name'];
        }
        $jutsus = $this->GetJutsuByEffect($effect);
        foreach ($jutsus as $jutsu) {
            $userJutsuSelect = "select * from Jutsuk jk
				LEFT JOIN `user` u ON `jk`.`id` = `u`.`id` where `jk`.`" . $jutsu['Name'] . "` >= '1'";
            $userJutsuResult = mysql_query($userJutsuSelect);
            while ($userJutsu = mysql_fetch_array($userJutsuResult)) {
                $user[$userJutsu['id']] = $userJutsu['name'];
            }
        }

        return $user;
    }

    public function GetItemByEffect()
    {
        $itemsSelect = "select * from eeEffectsItem efi LEFT JOIN Itemsk i ON `efi`.`iId` = `i`.`id` where efi.eId = '" . $this->Id . "'";
        $itemsResult = mysql_query($itemsSelect);
        $items = [];

        while ($item = mysql_fetch_array($itemsResult)) {
            $items[] = $item;
        }
        return $items;
    }

    public function GetJutsuByEffect()
    {
        $jutsuSelect = "select * from eeEffectsJutsu efi LEFT JOIN Jutsu j ON `efi`.`jId` = `j`.`id` where efi.eId = '" . $this->Id . "'";
        $jutsuResult = mysql_query($jutsuSelect);
        $jutsus = [];

        while ($jutsu = mysql_fetch_array($jutsuResult)) {
            $jutsu['clearName'] = str_replace('abba', ' ', $jutsu['Name']);
            $jutsus[] = $jutsu;
        }
        return $jutsus;
    }

    public function GetObligatoryEffects()
    {
        $obESelect = "select * from `eeObligatoryEffects` eeOb LEFT JOIN `eeeffect` eeef ON `eeOb`.`dEId` = `eeef`.`id` where eeef.eId = '" . $this->Id . "'";
        $obEResult = mysql_query($obESelect);
        $obEs = [];

        while ($obE = mysql_fetch_array($obEResult)) {
            $obEs[] = $obE;
        }
        return $obEs;
    }

    public function UrlFix(string $description): string
    {
        $description = str_replace("[url]", "<a href='", $description);
        $description = str_replace("[/url]", "'>Link</a>", $description);
        return $description;
    }
}
