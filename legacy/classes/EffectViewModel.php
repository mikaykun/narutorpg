<?php

final class EffectViewModel
{
    public function GetEffectsByGroup(Group $group): array
    {
        $effectsSelect = "SELECT * FROM eeeffect LEFT JOIN eeGroupEffectRelation ON eeeffect.ID = eeGroupEffectRelation.effectID WHERE eeGroupEffectRelation.groupID =" . mysql_real_escape_string($group->Id);
        $effectsResult = mysql_query($effectsSelect);

        $effects = [];

        while ($singleEffect = mysql_fetch_array($effectsResult)) {
            $effect = new Effect();
            $effect->SetValuesWithGroup($singleEffect, $group);
            $effects[] = $effect;
        }

        return $effects;
    }

    /**
     * @return array<Effect>
     */
    public function GetEffectsByJutsu(Jutsu $jutsu): array
    {
        $effectsSelect = "SELECT * FROM eeEffectsJutsu efj INNER JOIN eeeffect eee ON `efj`.`eId` = `eee`.`id` WHERE efj.jId = '" . $jutsu->getId() . "' ORDER BY efj.connectionGroup";
        $effectsResult = mysql_query($effectsSelect);
        $effects = [];

        while ($singleEffect = mysql_fetch_array($effectsResult)) {
            $effect = new Effect();
            $effect->SetValues($singleEffect);
            $effects[] = $effect;
        }
        return $effects;
    }

    public function GetEffectsByItem($item): array
    {
        $effectsSelect = "select * from eeEffectsItem efi LEFT JOIN eeeffect eee ON `efi`.`eId` = `eee`.`id` where efi.iId = '" . $item->Id . "' ORDER BY efi.connectionGroup";
        $effectsResult = mysql_query($effectsSelect);
        $effects = [];

        while ($singleEffect = mysql_fetch_array($effectsResult)) {
            $effect = new Effect();
            $effect->SetValues($singleEffect);
            $effects[] = $effect;
        }
        return $effects;
    }

    public function SaveOrUpdateEffect(Effect $effect)
    {
        $baukastenLog = new LoggingTool();
        if ($effect->Id == 0) {
            $effectQuery = "insert into eeeffect (name,description,costs,rank,maxVal,isPublic,userId,isAdvantage,isUpToDate,freeAction,kindOfCosts,affectAll)" .
                " values('" . mysql_real_escape_string($effect->Name) . "','" . $effect->Description . "'," . mysql_real_escape_string($effect->Costs) . "," . mysql_real_escape_string($effect->Rank) . "," . mysql_real_escape_string($effect->maxVal) . ',' .
                ($effect->IsPublic == "true" ? 1 : 0) . "," . mysql_real_escape_string($effect->UserId) . "," . ($effect->IsAdvantage == "true" ? 1 : 0) . "," . ($effect->IsUpToDate == "true" ? 1 : 0) . "," . ($effect->freeAction == "true" ? 1 : 0) . "," . mysql_real_escape_string($effect->kindOfCosts) . "," . ($effect->affectAll == "true" ? 1 : 0) . ")";
        } else {
            $effectQuery = "update eeeffect set name = '" . mysql_real_escape_string($effect->Name) . "'," .
                " description = '" . $effect->Description . "'," .
                " costs = " . mysql_real_escape_string($effect->Costs) . "," .
                " rank = " . mysql_real_escape_string($effect->Rank) . "," .
                " maxVal = " . mysql_real_escape_string($effect->maxVal) . "," .
                " isPublic = " . ($effect->IsPublic == "true" ? 1 : 0) . "," .
                " userId = " . mysql_real_escape_string($effect->UserId) . "," .
                " isAdvantage = " . ($effect->IsAdvantage == "true" ? 1 : 0) . "," .
                " isUpToDate = " . ($effect->IsUpToDate == "true" ? 1 : 0) . "," .
                " affectAll = " . ($effect->affectAll == "true" ? 1 : 0) . "," .
                " freeAction = " . ($effect->freeAction == "true" ? 1 : 0) . "," .
                " kindOfCosts = " . mysql_real_escape_string($effect->kindOfCosts) .
                " where id = " . mysql_real_escape_string($effect->Id);
        }

        $effectResult = mysql_query($effectQuery);

        if ($effectResult) {
            if ($effect->Id == 0) {
                $effect->Id = mysql_insert_id();
                $baukastenLog->defineLogEntry("Baukasten", "Hinzufügen des Effekts " . $effect->Name . "," . $effect->GroupId);
                $baukastenLog->logUpload();
                $effectGroupQuery = "insert into eeGroupEffectRelation (effectID, groupID) values (" . $effect->Id . "," . mysql_real_escape_string($effect->GroupId) . ")";
                mysql_query($effectGroupQuery);
            }

            return $this->group;
        }
    }

    //expects array($kindOfCostsId=>array($costs))
    private function getConnectedEffectsCosts(array $costsArray): float|int
    {
        $costs = 0;
        foreach ($costsArray[1] as $plus) {
            $costs += $plus;
        }
        $multi = 0;
        foreach ($costsArray[2] as $mult) {
            $multi += $mult;
        }
        if ($multi != 0) {
            $multi /= 100;
            $costs *= $multi;
        }
        if (!empty($costsArray[3])) {
            while (($max = max($costsArray[3])) !== false) {
                $key = array_search($max, $costsArray[3]);
                unset($costsArray[3][$key]);
                $costs *= $max / 100;

                if ($costsArray[3] === []) {
                    break;
                }
            }
        }

        return $costs;
    }

    public function costsEcho(array $groupCosts, $connGroup): float|int
    {
        $costs = $this->getConnectedEffectsCosts($groupCosts);
        if ($connGroup != 0) {
            echo "<tr><td class='lastCol'></td>
					<td class='lastCol'></td>
					<td class='lastCol' align='center'>$costs</td>
					<td class='lastCol'></td>
					<td class='lastCol'></td></tr>";
        }
        return $costs;
    }

    public function GetEffectById($effectId, $userId, $admin = 1): ?Effect
    {
        if ($admin == 1) {
            $effectSelect = "select * from eeeffect where id = " . mysql_real_escape_string($effectId);
        } else {
            $effectSelect = "select * from eeeffect where id = " . mysql_real_escape_string($effectId) . " and userId = " . mysql_real_escape_string($userId);
        }
        $effectResult = mysql_query($effectSelect);

        if (mysql_num_rows($effectResult) == 0) {
            return null;
        }

        $effect = new Effect();
        $effect->SetValues(mysql_fetch_array($effectResult));
        return $effect;
    }
}
