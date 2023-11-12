<?php

final class otherHelpfulTools
{
    public function getKindOfCosts(): array
    {
        $kindCostsSelect = "select * from eeKindOfCosts";
        $kindCostsResult = mysql_query($kindCostsSelect);
        $kindCosts = [];

        while ($singleKindCost = mysql_fetch_array($kindCostsResult)) {
            $kindCosts[$singleKindCost['id']] = $singleKindCost['kind'];
        }
        return $kindCosts;
    }
}
