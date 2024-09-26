<?php

//TPUP erweitern, sodass auch rang oder clantp zurückgegeben werden können, dort auch auf ausgegebene clantp etc achten
class DoItWithFaehJutsusItems
{
    private $tpKostenBack;
    private $whatBack;
    private $money;
    private $cp;

    public function itemTPBack($iId, $owner, $grund, $iName)
    {
        $tp = $this->tpKostenBack->howMuchIsThisItem($iId, 0, $owner, 0);
        if($this->cp == 1) {
            $cpReturn = $this->cpBack($tp, $uId);
            $grund = 'Erstattung von ' . $tp . ' TP und ' . $cpReturn . ' Centerpunkte auf Grund von Abzug von ' . $iName . ' (' . $grund . ')';
        } else {
            $grund = 'Erstattung von ' . $tp . ' TP auf Grund von Abzug von ' . $iName . ' (' . $grund . ')';
        }
        $training = $owner . " " . $iName . " 1";
        $this->tpKostenBack->tpUp($owner, $tp, $grund, 0, '', $training, $this->whatBack);
        return $tp;
    }

    public function moneyBack($costs, $number, $uId): void
    {
        if($this->money == 1) {
            $total = $costs * $number;
            $aendern = 'UPDATE `user` SET `Geld` = `Geld`+' . $total . ' WHERE `id` = \'' . $uId . '\'';
            echo $aendern;
            mysql_query($aendern) or die("Fehler beim Gutschreiben des Gelds");
        }
    }
    public function cpBack($costs, $uId)
    {
        if($this->cp == 1) {
            $ninRang = 'SELECT `id`,`Niveau` FROM `user` WHERE `id` = \'' . $uId . '\'';
            $ninRang = mysql_query($ninRang);
            $ninRang = mysql_fetch_array($ninRang);
            $cpReturn = 0;

            switch($ninRang['Niveau']) {
                case '2':
                    $cpReturn = $costs / 12;
                    $cpReturn = round($cpReturn, 1);
                    break;
                case '3':
                    $cpReturn = $costs / 16;
                    $cpReturn = round($cpReturn, 1);
                    break;
                case '4':
                    $cpReturn = $costs / 20;
                    $cpReturn = round($cpReturn, 1);
                    break;
                default:
                    $cpReturn = 0;
                    break;
            }

            $aendern = 'UPDATE `user` SET `CenterPunkte` = `CenterPunkte`+' . $cpReturn . ' WHERE `id` = \'' . $uId . '\'';
            mysql_query($aendern) or die("Fehler beim Gutschreiben der CP");
            return $cpReturn;
        }
    }

    public function itemFaehAway($uId, $iId): void
    {
        $aendern = 'DELETE FROM `itemFaeh` WHERE `uId` = \'' . $uId . '\' AND `iId` = \'' . $iId . '\'';
        //echo $aendern;
        mysql_query($aendern) or die("Fehler beim L&ouml;schen der Itemf&auml;higkeit");
    }

    public function allOfThisItemsGone($iName, $grund, $infos, $one = 0)
    {
        $iId = $infos['id'];
        $zusatz = ($one > 0) ? ' AND `Von` = \'' . $one . '\'' : '';
        $werKanns = 'SELECT `Von`,SUM(`Menge`) as `mengeGesamt` FROM `Item` WHERE `Item` = \'' . $iName .
            '\'' . $zusatz . ' GROUP BY `Von`';
        $werKanns = mysql_query($werKanns);
        while($itemOwner = mysql_fetch_array($werKanns)) {
            $this->doItemAway(
                $iId,
                $itemOwner['Von'],
                $grund,
                $iName,
                $infos['Kosten'],
                $itemOwner['mengeGesamt']
            );
        }
        return true;
    }

    public function doItemAway($iId, $owner, $grund, $iName, $costs, $menge)
    {
        $tp = $this->itemTPBack($iId, $owner, $grund, $iName);
        $this->moneyBack($costs, $menge, $owner);
        $this->itemFaehAway($owner, $iId);
        $aendern = 'DELETE FROM `Item` WHERE `Item` = \'' . $iName .
            '\' AND `Von` = \'' . $owner . '\'';
        $update = mysql_query($aendern) or die("Fehler beim L&ouml;schen der Items 2");
        return $tp;
    }

    public function allOfThisJutsusGone($jName, $grund)
    {
        $werKanns = 'SELECT `id` FROM `Jutsuk` WHERE `' . $jName . '` = \'1\'';
        $werKanns = mysql_query($werKanns);
        while($jutsu = mysql_fetch_array($werKanns)) {
            $this->oneOfThisJutsuGone($jName, $jutsu['id'], $grund);
            //echo $jutsu['id'].'<br>';
        }
        return true;
    }

    public function oneOfThisJutsuGone($jName, $uId, $grund)
    {
        $namejutsu = str_replace(" ", "abba", $jName);
        $jutsu = 'SELECT * FROM `Jutsuk` WHERE `id` = \'' . $uId . '\' AND `' . $jName . '`>= \'1\'';
        $jutsu = mysql_query($jutsu);
        if ($jutsu = mysql_fetch_array($jutsu)) {
            $tp = $this->tpKostenBack->howMuchIsThisJutsu($jName, 0, $jutsu, 1);
            if ($this->cp == 1) {
                $cpReturn = $this->cpBack($tp, $uId);
                $grund = 'Erstattung von ' . $tp . ' ' . $this->whatBack . ' und ' . $cpReturn . ' Centerpunkte auf Grund von Abzug von ' . $namejutsu . ' (' . $grund . ')';
            } else {
                $grund = 'Erstattung von ' . $tp . ' ' . $this->whatBack . ' auf Grund von Abzug von ' . $namejutsu . ' (' . $grund . ')';
            }
            $training = $jutsu['id'] . " " . $namejutsu . " 1";
            $this->tpKostenBack->tpUp($jutsu['id'], $tp, $grund, 0, '', $training, $this->whatBack);
            $aendern = 'UPDATE `Jutsuk` SET `' . $jName . '` = \'0\' WHERE `id` = \'' .
                $jutsu['id'] . '\'';
            mysql_query($aendern) or die("Fehler beim L&ouml;schen der Jutsus 2");
        } else {
            echo 'Der User besitzt diese Technik nicht!';
        }
        return $tp;
    }

    public function allOfThisFaehsGone($fName, $grund)
    {
        $werKanns = 'SELECT `' . $fName . '`,`id` FROM `Fähigkeiten` WHERE `' . $fName . '` >= \'1\'';
        $werKanns = mysql_query($werKanns);
        while($faeh = mysql_fetch_array($werKanns)) {
            $this->oneOfThisFaehGone($fName, $faeh['id'], $grund);
            //echo $faeh['id'].'<br>';
        }
        return true;
    }

    public function oneOfThisFaehGone($fName, $uId, $grund)
    {
        $faeh = 'SELECT `' . $fName . '`,`id` FROM `Fähigkeiten` WHERE `id` = \'' . $uId . '\' AND `' . $fName . '`>= \'1\'';
        $faeh = mysql_query($faeh);
        if($faeh = mysql_fetch_array($faeh)) {
            $tp = $this->tpKostenBack->howMuchRAllStepsOfThisFeah($fName, $faeh[$fName]);
            if($this->cp == 1) {
                $cpReturn = $this->cpBack($tp, $uId);
                $grund = 'Erstattung von ' . $tp . ' ' . $this->whatBack . ' und ' . $cpReturn . ' Centerpunkte auf Grund von Abzug von ' . $fName . ' (' . $grund . ')';
            } else {
                $grund = 'Erstattung von ' . $tp . ' ' . $this->whatBack . ' auf Grund von Abzug von ' . $fName . ' (' . $grund . ')';
            }
            $training = $faeh['id'] . " " . $fName . " 1";
            $this->tpKostenBack->tpUp($faeh['id'], $tp, $grund, 0, '', $training, $this->whatBack);
            $aendern = 'UPDATE `Fähigkeiten` SET `' . $fName . '` = \'0\' WHERE `id` = \'' .
                $faeh['id'] . '\'';
            mysql_query($aendern) or die("Fehler beim L&ouml;schen der Fähigkeit 2");
        } else {
            echo 'Der User besitzt diese Technik nicht!';
        }
        return $tp;
    }

    /*
    ** $art expects kind of what to delete ("item","faeh" or "Jutsu")
    ** $haveToKill expects what exactly to kill (e.g. "Stirnband")
    ** $who has to be id of the user or 0. If 0 ALL user will loose
    ** this Stuff
    */
    public function startIt($art, $haveToKill, $who, $params = 0, $grund = 0, $whatBack = 0, $sure = 0, $cp = 0, $money = 0)
    {
        if ($money == 1) {
            $this->money = $money;
        }
        if ($cp == 1) {
            $this->cp = $cp;
        }
        //echo 'yay, it started!';
        if ($sure == 0) {
            $this->uSure($art, $haveToKill, $who, $params);
        } else {
            $tp = $this->ySure($art, $haveToKill, $whatBack, $grund, $who, $sure);
            return [$tp, $whatBack];
        }
        return;
    }

    public function uSure($art, $haveToKill, $who, $params)
    {
        echo 'Bist du dir sicher, dass du ' . $haveToKill;
        if ($who > 0) {
            echo ' bei dem User mit der ID ' . $who;
        } else {
            echo 'bei allen Usern';
        }
        echo ' entfernen m&oumlchtest?<br>';
        echo '<form action="?' . $params . '" method="POST">
					<input type=\'text\' name=\'grund\' placeholder=\'Grund\'><br>
					Gutschreiben auf:
					<select name="whatBack">
						<option value="Trainingspunkte">TP</option>
						<option value="ClanTP">Clan-TP</option>
						<option value="RangTP">Rang-TP</option>
						<option value="tplf">Lernfähigkeits-TP</option>
					</select>
					<br>';
        if($art == 0) {
            echo 'Geld erstatten? <input type=\'checkbox\' name=\'money\' value=\'1\'><br>';
        }
        echo 'CP erstatten? <input type=\'checkbox\' name=\'cp\' value=\'1\'><br>';
        echo '<input type=\'hidden\' name=\'sure\' value=\'1\'>
					<input type=\'hidden\' name=\'haveToKill\' value=\'' . $haveToKill . '\'>
					<input type=\'hidden\' name=\'art\' value=\'' . $art . '\'>
					<input type=\'hidden\' name=\'who\' value=\'' . $who . '\'>
					<input type=\'submit\' value=\'Austragen\'>
                </form>';
        return;
        //echo 'u did it2'.$art.$haveToKill.$whatBack.$grund.$who.$sure;
    }
    // $sure has to be set 0 if nothing should be changed else 1
    public function ySure($art, $haveToKill, $whatBack, $grund, $who, $sure = 0)
    {
        $this->whatBack = $whatBack;
        $this->tpKostenBack = new tpKosten();
        if($sure != 1) {
            return;
        }
        if(!in_array($whatBack, ['Trainingspunkte','ClanTP',
            'RangTP','tplf'])) {
            return;
        }
        //echo 'u did it'.$art.$haveToKill.$whatBack.$grund.$who.$sure;

        if($who <= 0) {
            //echo 'Me IZ EVERYBODY';
            if($art == 0) {
                $tp = $this->allOfThisItemsGone($haveToKill, $grund, $this->getItemInfos($haveToKill));
            } elseif($art == 1) {
                $tp = $this->allOfThisJutsusGone($haveToKill, $grund);
            } elseif($art == 2) {
                $tp = $this->allOfThisFaehsGone($haveToKill, $grund);
            }
        } else {
            if ($art == 0) {
                //echo 'ITZ JUTSUTIME';
                //$tp = $this->allOfThisItemsGone($haveToKill,$grund,$this->getItemInfos($haveToKill),$who);
            } elseif ($art == 1) {
                //echo 'ITZ JUTSUTIME';
                $tp = $this->oneOfThisJutsuGone($haveToKill, $who, $grund);
            } elseif ($art == 2) {
                //echo 'FAEHS PLZ!';
                $tp = $this->oneOfThisFaehGone($haveToKill, $who, $grund);
            }
        }
        return $tp;
    }

    private function getItemInfos($iName)
    {
        $itemInfos = 'SELECT `id`,`Kosten` FROM `Itemsk` WHERE `Name` = \'' . $iName . '\'';
        $itemInfos = mysql_query($itemInfos);
        $itemInfos = mysql_fetch_array($itemInfos);
        return $itemInfos;
    }
}
