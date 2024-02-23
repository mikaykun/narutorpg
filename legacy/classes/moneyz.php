<?php

final class moneyz
{
    public function howMuchIsThisItem($iName)
    {
        $sql = "SELECT `Kosten` FROM `Itemsk` WHERE `Name` = '$iName'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_array($query, MYSQL_ASSOC);
        return $Item['Kosten'];
    }

    public function howMuchRAllItems($uid): float|int
    {
        $itemCosts = 0;
        $items = "SELECT `Item`,`Menge` FROM `Item` WHERE `Von` = '" . $uid . "'";
        $items = mysql_query($items);
        while ($item = mysql_fetch_array($items)) {
            $itemCosts += $this->howMuchIsThisItem($item['Item']) * $item['Menge'];
        }
        return $itemCosts;
    }

    public function gotByMissisRP($User): float|int
    {
        $missisRP = [
            'd' => 5_000,
            'c' => 10_000,
            'b' => 20_000,
            'a' => 30_000,
            's' => 40_000,
        ];
        $gesamt = $User->DRP * $missisRP['d'] + $User->CRP * $missisRP['c'] +
            $User->BRP * $missisRP['b'] + $User->ARP * $missisRP['a'] +
            $User->SRP * $missisRP['s'];
        return $gesamt;
    }

    public function gotByMissisNoRP($User): float|int
    {
        $missisNoRP = ['d' => 500, 'c' => 1000, 'b' => 2000];
        $gesamt = ($User->D - $User->DRP) * $missisNoRP['d'] +
            ($User->C - $User->CRP) * $missisNoRP['c'] +
            ($User->B - $User->BRP) * $missisNoRP['b'];
        return $gesamt;
    }

    public function gotByMissis($User): float|int
    {
        $gesamt = $this->gotByMissisRP($User) + $this->gotByMissisNoRP($User);
        return $gesamt;
    }

    public function gotByLevel($User): int
    {
        $niveauMoney = ($User->Niveau > 0) ? 20_000 : 0;
        $niveauMoney += ($User->Niveau > 1) ? 30_000 : 0;
        return $niveauMoney;
    }


    public function gotByPresent($User)
    {
        return $User->GeldPlus;
    }

    public function userOwnsMoney($User)
    {
        return $User->Geld;
    }

    public function moneyInEstate($User)
    {
        $allMoneyInEstate = $User->Geld + $this->howMuchRAllItems($User->id);
        return $allMoneyInEstate;
    }

    public function allMoneyGotWithoutAP($User)
    {
        $allMoneyGotWithoutAP = $this->gotByMissis($User) + $this->gotByLevel($User) + $User->GeldPlus;
        return $allMoneyGotWithoutAP;
    }

    public function moneyzByAP($User)
    {
        $moneyByAP = $this->moneyInEstate($User) - $this->allMoneyGotWithoutAP($User);
        return $moneyByAP;
    }

    public function apInvestedInMoney($User): float|int
    {
        $ap = $this->moneyzByAP($User) / 5000;
        return $ap;
    }
}
