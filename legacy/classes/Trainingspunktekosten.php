<?php

class tpKosten
{
    private $jutsuTPKosten;
    private $sumUnder;
    private $jutsuOk;
    private $okMax;
    private $noS;
    private $sStuff = 0;
    private $not = array('Genjutsu', 'Taijutsu');

    //Anzahl s stuff noch zählen
    public function howMuchS($User, $u_Jutsu, $u_Fähigkeit)
    {
        $tpUnderS = $this->allStuffUnderS($User, $u_Jutsu, $u_Fähigkeit);
        if($tpUnderS > 800) {
            $tpUnderS2 = $tpUnderS - 800;
            $numS = floor(($tpUnderS2 / 10) + 9);
        } else {
            $numS = floor($tpUnderS / 100);
        }
        $numS -= $this->sStuff;
        $sStuff = $this->sStuff;
        return array($numS,$tpUnderS,$sStuff);
    }

    public function allStuffUnderS($User, $u_Jutsu, $u_Fähigkeit)
    {
        $this->noS = 1;
        foreach($u_Jutsu as $name => $step) {
            if($step > 0) {
                $sql = "SELECT * FROM Jutsu WHERE `Name` = '$name'";
                $query = mysql_query($sql);
                $Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
                if($Jutsu['Ninjutsu'] > 8 || $Jutsu['Taijutsu'] > 8 || $Jutsu['Genjutsu'] > 8) {
                    $u_Jutsu[$name] = 0;
                    $this->sStuff += 1;
                }
            }
        }
        $tp = $this->tpBackGesamt($User, $u_Jutsu, $u_Fähigkeit);
        $this->noS = 0;
        return $tp;
    }

    /* Kommentare vor Funktionen erklären deren Rückgabewerte und
     * Eingabe-Parameter
     *
     * function howMuchIsThisJutsu
     * $name is String Name der Jutsu (Form z.b.KatonabbaNoabbaJutsu
     * (wie in der DB))
     * $Vorganger Boolean Nur 1 setzen, wenn ihr nicht wollt, dass der Vorgänger
     * in die Kosten einbezogen wird
     * $u_Jutsu array, alle Jutsus des Users in der Form $key = Name,
     * $value = Stufe
     * $Nachfolger boolean, ob Nachfolger beachtet werden soll. Nur sinnvoll,
     * wenn dieser nicht sowieso geprüft wird, z.b. weil alle Jutsus eines Users
     * geprüft werden, sonst werden ggf doppelt TP erstattet 1 setzen,
     * wenn beachtet werden soll
     * returns (unsigned)int $TP-Kosten der Jutsu
     */
    public function howMuchIsThisItem($iid, $vorg, $uid, $nachf)
    {
        $sql = "SELECT `TP`,`Niveau` FROM `Itemsk` WHERE `id` = '$iid'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_array($query, MYSQL_ASSOC);
        $tp = $Item['TP'];
        $tpV = 0;
        if($vorg === 0) {
            $vorgQuer        = "SELECT `TP` FROM `itemVorg` iV LEFT JOIN `itemFaeh` iFa ON iV.`vId` = iFa.`iId` LEFT JOIN `Itemsk` iK ON iV.`vId` = iK.`id` WHERE iV.`iId` = '" . $iid . "' AND iFa.`uId` = '" . $uid . "'";
            $vorgQuer        = mysql_query($vorgQuer);
            while($vorg = mysql_fetch_array($vorgQuer)) {
                if($tpV >= $tp) {
                    break;
                }
                if($vorg['TP'] > $tpV) {
                    $tpV = $vorg['TP'];
                }
            }
        }
        if($nachf === 0) {
            $nachQuer        = "SELECT `TP` FROM `itemVorg` iV LEFT JOIN `itemFaeh` iFa ON iV.`iId` = iFa.`iId` LEFT JOIN `Itemsk` iK ON iV.`iId` = iK.`id` WHERE iV.`vId` = '" . $iid . "' AND iFa.`uId` = '" . $uid . "'";
            $nachQuer        = mysql_query($nachQuer);
            while($nachf = mysql_fetch_array($nachQuer)) {
                if($tpV >= $tp) {
                    break;
                }
                if($nachf['TP'] > $tpV) {
                    $tpV = $nachf['TP'];
                }
            }
        }
        $tp = (($tp > $tpV) ? ($tp - $tpV) : 0);
        if($this->noS == 1 && $Item['Niveau'] == 5 && $tp > 0) {
            $this->sStuff += 1;
            return 0;
        }
        return $tp;
    }

    public function tpUp($uId, $tp, $grund, $NPC, $land, $training, $spalte)
    {
        $Dateingert = date("d.m.Y");
        $aendern = 'UPDATE `user` SET `' . $spalte . '` =
		`' . $spalte . '`+' . $tp . ' WHERE `id` = \'' . $uId . '\'';
        //echo $aendern;
        $update = mysql_query($aendern) or die("Fehler beim
			Gutschreiben der TP");
        $insert = "INSERT INTO NPCSystem (NPC, Text, Datum, Land,
			Training, Ninkriegt, Passiertemit, TP)
			VALUES ('$NPC', '$grund', '$Dateingert', '$land',
			'$training', '$uId','1', '1')";
        $insert = mysql_query($insert) or die("Fehler beim Eintragen ins NPC-System");
        return true;
    }

    public function kindOfJutsu($Jutsu)
    {
        if ($Jutsu['Taijutsu'] > $Jutsu['Ninjutsu'] and $Jutsu['Taijutsu'] >
                $Jutsu['Genjutsu']) {
            $Art = $Jutsu['Taijutsu'];
            $type = 'Taijutsu';
        } elseif ($Jutsu['Taijutsu'] < $Jutsu['Ninjutsu'] and $Jutsu['Ninjutsu'] >
                $Jutsu['Genjutsu']) {
            $Art = $Jutsu['Ninjutsu'];
            $type = 'Ninjutsu';
        } else {
            $Art = $Jutsu['Genjutsu'];
            $type = 'Genjutsu';
        }
        return array($type,$Art);
    }

    public function howMuchIsThisJutsu($name, $Vorganger, $u_Jutsu, $Nachfolger, $returnMoreVals = 0)
    {
        $TP = 0;
        $sql = "SELECT * FROM Jutsu WHERE `Name` = '$name'";
        $query = mysql_query($sql);
        $Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
        $typeKind = $this->kindOfJutsu($Jutsu);
        $type = $typeKind[0];
        $Art = $typeKind[1];
        $TP = ($Art <= 1) ? 0 :
            (($Art <= 2) ? 4 :
             (($Art <= 4) ? 6 :
              (($Art <= 6) ? 12 :
               (($Art <= 8) ? 20 :
                (($Art <= 10) ? 30 : 30)))));
        if($Jutsu['Name'] == 'ChikatsuabbaSaiseiabbanoabbaJutsu') {
            $TP = 0;
        }
        $gates = array("Kaimon","Kyuumon","Seimon","Shoumon","Tomon",
                "Keimon","Kyoumon","Shimon");
        $TP = in_array($name, $gates) ? 10 : $TP;
        $Mindestkosten = ceil($TP / 3);
        if($Nachfolger == 1) {
            $sql2 = "SELECT `Name` FROM Jutsu WHERE `Jutsuvorgänger` = '$name'";
            $query2 = mysql_query($sql2);
            $Nachfolgerda = 0;
            while(($Nachfolger = mysql_fetch_array($query2, MYSQL_ASSOC)) && ($Nachfolgerda == 0)) {
                if(($u_Jutsu[$Nachfolger['Name']] > 0) &&
                        isset($u_Jutsu[$Nachfolger['Name']])) {
                    $Punkteweg = $this->howMuchIsThisJutsu(
                        $Nachfolger['Name'],
                        1,
                        $u_Jutsu,
                        0
                    );
                    $TP = ($Punkteweg == $TP) ? $Mindestkosten :
                        (($Punkteweg  > $TP) ? 0 : $TP - $Punkteweg);
                    $Nachfolgerda = 1;
                }
            }
        }

        $Vorgname = $Jutsu['Jutsuvorgänger'];
        if ($Vorgname != "" && $Vorganger == 0 && isset($u_Jutsu[$Vorgname]) && $u_Jutsu[$Vorgname] > 0
            && $Nachfolgerda == 0) {
            $Punkteweg = $this->howMuchIsThisJutsu($Vorgname, 1, $u_Jutsu, 0);
            $Punkteneu = $TP - $Punkteweg;
            $TP = ($Punkteneu < $Mindestkosten) ? $Mindestkosten : $Punkteneu;
        }
        if($returnMoreVals == 1) {
            return array($TP, $Art, $type,$Jutsu['Element']);
        }
        return $TP;
    }

    /* function howMuchRAllJutsus
     * $u_Jutsu array, alle Jutsus des Users in der Form $key = Name,
     * $value = Stufe
     * returns (unsigned)int $TP-Gesamt-Kosten aller Jutsus dieses Users
     * (bzw alle die rein gegeben wurden)
     */

    public function howMuchRAllJutsus($u_Jutsu)
    {
        $tpBack = 0;
        foreach($u_Jutsu as $key => $value) {
            if($value > 0 && $key != 'id') {
                $tpBack += $this->howMuchIsThisJutsu($key, 0, $u_Jutsu, 0);
            }
        }
        return $tpBack;
    }

    //berechnet wieviele TP für welche Jutsustufe bereits ausgegeben
    //wurden (Mischelementkosten werden beachtet)

    public function whichJutsusOk($u_Jutsu)
    {
        $steps = array(0 => 0,2 => 0,4 => 0,6 => 0,8 => 0);
        $kinds = array('Genjutsu' => $steps,'Taijutsu' => $steps,'Ninjutsu' => $steps);
        foreach($u_Jutsu as $key => $value) {
            if($value > 0 && $key != 'id') {
                $tpInfos = $this->howMuchIsThisJutsu($key, 0, $u_Jutsu, 0, 1);
                if($tpInfos[1] % 2 != 0) {
                    $tpInfos[1] += 1;
                }
                $kinds[$tpInfos[2]][$tpInfos[1]] += $tpInfos[0];
            }
        }
        $this->jutsuTPKosten = $kinds;
        //print_r($this->jutsuTPKosten);
        /*howMuchIsThisJutsu($key,0,$u_Jutsu,0,$returnMoreVals = 0)
        array($TP, $Stufe, $type,$Jutsu['Element'])
        $jutsu_tp = ;
        $tp = ;*/
        return true;
    }

    //berechnet die Summe, die für die Stufe darunter gesamt ausgegeben wurden

    public function sumTPUnder()
    {
        $this->sumUnder = $this->jutsuTPKosten;
        foreach($this->jutsuTPKosten as $ele => $step) {
            foreach($step as $minstep => $tp) {
                foreach($step as $actStep => $tp2) {
                    if($minstep < $actStep) {
                        $this->sumUnder[$ele][$actStep] += $tp;
                    }
                }
            }
        }
        //print_r($this->sumUnder);
        return true;
    }

    //ausgabefunktion von welche stufen ok einfügen

    ///dis nch vernünftig machen + überall die verknüpfungen (muss rein ob spezifische Jutsu ok)
    public function thisJutsuOk($jutsu, $user, $seal)
    {	//Element, Siegel prüfen, Clan
        $sealNeeded = array(2 => 1,4 => 1,6 => 2,8 => 3,10 => 4);
        $elements = array($user->Element1,$user->Element2,
        $user->Element3,$user->Element4,$user->Element5,$user->Element6,
        'Keins');
        $stepSeal = (($jutsu->Ninjutsu > $jutsu->Genjutsu) ?
            (($jutsu->Ninjutsu % 2 == 0) ? $jutsu->Ninjutsu : ($jutsu->Ninjutsu + 1)) :
            (($jutsu->Genjutsu % 2 == 0) ? $jutsu->Genjutsu : ($jutsu->Genjutsu + 1)));
        if(($jutsu->Clan != $user->Clan && $jutsu->Clan != '')
            || ($jutsu->Taijutsu > $this->okMax['Taijutsu'])
            || ($jutsu->Ninjutsu > $this->okMax['Ninjutsu'])
            || ($jutsu->Genjutsu > $this->okMax['Genjutsu'])
            || (!in_array($jutsu->Element, $elements))
            || (($jutsu->Jutsutyp == 'Siegel') && ($sealNeeded[$stepSeal] > $seal))) {
            return false;
        }
        return true;
    }

    public function getOkMax()
    {
        return $this->okMax;
    }

    public function thisItemOk($niveau, $art)
    {	//Element, Siegel prüfen, Clan
        $needed = $niveau * 2;
        if($needed == 0) {
            $needed = 2;
        }
        if($this->okMax[$art] < $needed) {
            return false;
        }
        return true;
    }

    //Was sind jeweils die maximalen Stufen die ok sind?

    public function thisIsOkMax($u_Jutsu, $user)
    {
        $this->jutsuTPOk($u_Jutsu, $user);
        $this->okMax = array();
        //print_r($this->jutsuOk);
        foreach($this->jutsuOk as $ele => $steps) {
            $stepMax = 0;
            foreach($steps as $step => $ok) {
                if($step >= $stepMax && $ok == 1) {
                    $this->okMax[$ele] = $step;
                }
            }
        }
        //print_r($this->okMax);
        return true;
        // Return array was alles ok ist (array(element=>Stufe))
        //return $this->jutsuOk;
    }

    public function thisIsOkGUI($u_Jutsu, $user)
    {
        $this->thisIsOkMax($u_Jutsu, $user);
        $stupidTable = 'Jutsus erlernbar bis Stufe:<br>
		<table id="whatAmIAllowedTo"> <thead><tr>';
        $stupidTableHead = '';
        $stupidTableBody = '';
        $stupidTableTP = '';
        foreach ($this->okMax as $ele => $step) {
            $tp = (($step == 10) ? '-' : $this->sumUnder[$ele][$step]);
            $stupidTableHead .= '<th>' . $ele . '</th>';
            $stupidTableBody .= '<td>' . $step . '</td>';
            $stupidTableTP .= '<td>' . $tp . '</td>';
        }
        $stupidTable .= '<tr><td>Element</td>' . $stupidTableHead . '</tr>' .
                        '<tr><td>Bis Stufe</td>' . $stupidTableBody . '</tr>' .
                        '<tr><td>F&uuml;r nächste Stufe ausgegeben</td>' . $stupidTableTP . '</tr>' .
                        '</thead></table>';
        return $stupidTable;
    }

    public function itemOk($item, $art)
    {
        //Iteniveau ItemskNiveau (int)
        //art `itemKategorie` itemID jutsuKategorie

        //return true/false
    }

    public function allJutsuOk($clan)
    {
        $nonEles = array('Ninjutsu' => 'Siegel','Ninjutsu','Taijutsu');
        foreach ($this->okMax as $ele => $step) {
        }
    }
    //welche Ränge sind ok?
    public function jutsuTPOk($u_Jutsu, $user)
    {
        //diese noch starten
        $this->whichJutsusOk($u_Jutsu);
        $this->sumTPUnder();
        $tpOk = array(0 => 0,2 => 0,4 => 4,6 => 22,8 => 50,10 => 100);
        // Clan wird hier NICHT überprüft => muss dann einzeln bei der Jutsu getan werden
        foreach($this->sumUnder as $ele => $step) {
            $Jkind = $ele;
            foreach($tpOk as $stepi => $tp) {
                $step2 = $stepi - 2;
                //Stufe darunter muss ok sein, wenn mind c-rang
                if((($stepi > 2) && ($this->jutsuOk[$ele][$stepi - 2] == 0))
                 || (($stepi > 6) && ($user->Niveau < 3))
                 || (($stepi > 8) && ($user->Niveau < 4))
                 || ($user->$Jkind < $stepi)) {
                    $this->jutsuOk[$ele][$stepi] = 0;
                } else {
                    $this->jutsuOk[$ele][$stepi] = (($tp <= $this->sumUnder[$ele][$step2]) ? 1 : 0);
                }

            }

        }
    }

    /* function  howMuchIsThisFaeh
     * $Rang (unsigned)int Stufe, deren Kosten ausgegeben werden sollen
     * $Faeh array, benötigt Kosten1 bis Kosten5 der Fähigkeit
     * returns (unsigned)int TP-Kosten dieser Fähigkeitstufe
     */
    public function howMuchIsThisFaeh($Rang, $Faeh)
    {
        $TP = 0;
        $TP = ($Rang == 1) ? $Faeh['Kosten1'] :
            (($Rang == 2) ? $Faeh['Kosten2'] :
             (($Rang == 3) ? $Faeh['Kosten3'] :
              (($Rang == 4) ? $Faeh['Kosten4'] : $Faeh['Kosten5'])));
        return $TP;
    }

    /**
     * $key string, Tabellenname der Fähigkeit
     * $value (unsigned) int höchste Stufe der Fähigkeit, die einbezogen werden
     * soll
     * $Niveau (unsigned)int Niveau des Spielers, für den die TP-Kosten
     * berechnet werden sollen
     * RETURNS (unsigned)int Gesamt-TPkosten der Fähigkeit bis zur angegebenen
     * Stufe (z.b. bis Stufe 3, also 1,2 und 3)
     */
    public function howMuchRAllStepsOfThisFeah($key, $value)
    {
        $tpBekommen = 0;
        $sql = "SELECT * FROM Informationen_Faehs WHERE `Tabellenname` = '$key'";
        $query = mysql_query($sql);
        $Faeh = mysql_fetch_array($query, MYSQL_ASSOC);
        $Stufe = $value;
        while($Stufe > 0) {
            $tpBekommen += $this->howMuchIsThisFaeh($Stufe, $Faeh);
            $Stufe --;
        }
        return $tpBekommen;
    }

    /* function  howMuchRAllFaeh
     * $u_Fähigkeit array $key = Name der Fähigkeit, $value = Stufe
     * $Niveau (unsigned)int Niveau des Spielers, für den die TP-Kosten
     * berechnet werden sollen
     * RETURNS (unsigned)int Gesamt-TPkosten aller angegebenen Fähigkeiten bis
     * zu den jeweilign Stufen
     */
    public function howMuchRAllFaeh($u_Fähigkeit)
    {
        $tpBack = 0;
        $noFaeh = array('impBya','impSha','Test','Kyushu','Sharingan','Byakugan','id');
        foreach($u_Fähigkeit as $key => $value) {
            if($value > 0 && !in_array($key, $noFaeh)) {
                if($this->noS == 1 && $value > 3) {
                    $this->sStuff += 1;
                    $value = 3;
                }
                $tpBack += $this->howMuchRAllStepsOfThisFeah($key, $value);
            }
        }
        return $tpBack;
    }

    public function howMuchRAllItems($uid)
    {
        $tp = 0;
        $items = "SELECT `iId` FROM `itemFaeh` WHERE `uId` = '" . $uid . "'";
        $items = mysql_query($items);
        while ($item = mysql_fetch_array($items)) {
            $tp += $this->howMuchIsThisItem($item['iId'], 0, $uid, 1);
        }
        return $tp;
    }

    public function howMuchRAllEEJutsus($User, $u_Jutsu)
    {
        $tp = 0;
        foreach($u_Jutsu as $name => $val) {
            $sql = "SELECT `Eigenejutsu` FROM Jutsu WHERE `Name` = '$name'";
            $query = mysql_query($sql);
            $Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
            if($Jutsu['Eigenejutsu'] != 1) {
                unset($u_Jutsu[$name]);
            }
        }
        $tp += $this->howMuchRAllJutsus($u_Jutsu);
        return $tp;
    }

    public function howMuchRAllEEItems($uid)
    {
        $tp = 0;
        $items = "SELECT `iId` FROM `itemFaeh` fa LEFT JOIN `Itemsk` ik ON fa.`iId` = ik.`id` WHERE `uId` = '" . $uid . "' AND (`Useronly` != '' OR `Perso` != '' OR `Land` != '')";
        $items = mysql_query($items);
        while ($item = mysql_fetch_array($items)) {
            $tp += $this->howMuchIsThisItem($item['iId'], 0, $uid, 1);
        }
        return $tp;
    }

    public function howMuchRAllEEs($User, $u_Jutsu)
    {
        $tpEE = 0;
        $tpEE += $this->howMuchRAllEEJutsus($User, $u_Jutsu);
        $tpEE += $this->howMuchRAllEEItems($User->id);
        $tpEE += $User->EETPPlus;
        return $tpEE;
    }

    /**
     * $u_Fähigkeit array $key = Name, $value=Stufe
     * $u_Jutsu array $key = Name, $value=Stufe
     * $User object, ->Niveau wird benötigt
     * RETURNS (unsigned)int Gesamt-TPkosten aller angegebenen
     * Fähigkeiten/Jutsu.
     */
    public function tpBackGesamt($User, $u_Jutsu, $u_Fähigkeit, $dorfTP = 0)
    {
        $tpBack = 0;
        $tpBack += $this->howMuchRAllJutsus($u_Jutsu);
        $tpBack += $this->howMuchRAllFaeh($u_Fähigkeit);
        $tpBack += $this->howMuchRAllItems($User->id);
        $tpBack += $this->implantate($u_Fähigkeit);
        /*	echo $User->name.'JUtsus'.$this->howMuchRAllJutsus($u_Jutsu).
                'Faeh'.$this->howMuchRAllFaeh($u_Fähigkeit).
                'Items'.$this->howMuchRAllItems($User->id);*/
        $tpBack -= ($User->PunkteTPMax / 3) * ($User->maxNiveau - 1) - $User->PunkteTP;
        $sql = "SELECT `Sand` FROM Besonderheiten WHERE `id` = '$User->id'";
        $query = mysql_query($sql);
        $Besonderheiten = mysql_fetch_object($query);
        if($dorfTP === 1) {
            $tpBack -= $User->DorfTP;
        }
        if ($User->Clan != '' || $Besonderheiten->Sand != 0) {
            $tpBack -= $this->ClanTPGesamt($User, $Besonderheiten) - $User->ClanTP;
            /*echo 'Clan'.$tpBack;*/
        }
        /*echo '<br>';*/

        if($User->ntWertSelected == 1) {
            $tpBack += 10;
        }
        if($User->Lern > 100) {
            $IQTP = array(75 => 0,100 => 0,125 => 20,150 => 30,200 => 60);
            $tpBack -= $IQTP[$User->Lern];
            $tpBack += $User->tplf;
        }

        return $tpBack;
    }

    public function implantate($u_Faehigkeit)
    {
        $implaCosts = 0;
        $implas = array('impBya' => 30, 'impSha' => 30);
        foreach ($implas as $row => $costs) {
            if ($u_Faehigkeit[$row] > 0) {
                $implaCosts += $costs;
                if ($this->noS == 1) {
                    $this->sStuff += 1;
                    return 0;
                }
            }
        }
        return $implaCosts;
    }

    public function ClanTPNiveauClan($User, int $niveau, $Besonderheiten): int
    {
        $Clan = ($Besonderheiten->Sand != 0) ? 'Sand' : $User->Clan;
        return $this->ClanTPNiveau($Clan, $niveau);
    }

    private function ClanTPNiveau(string $Clan, int $niveau): int
    {
        $niveau -= 1;
        $clanTP = array(
            'Akimichi Clan' => array(0, 20, 20, 20),
            'Nara Familie' => array(0, 15, 15, 15),
            'Spinnenbluterbe' => array(0, 15, 15, 15),
            'Yamanaka Familie' => array(0, 10, 10, 10),
            'Mokuton Bluterbe' => array(0, 12, 20, 30),
            'Youton Bluterbe' => array(0, 7, 20, 20),
            'Hyouton Bluterbe' => array(0, 7, 20, 20),
            'Ranton Bluterbe' => array(0, 7, 20, 20),
            'Futton Bluterbe' => array(0, 7, 20, 20),
            'Sand' => array(0, 20, 20, 20)
        );
        if (isset($clanTP[$Clan])) {
            return $clanTP[$Clan][$niveau];
        }
        return 0;
    }

    public function ClanTPGesamt($User, $Besonderheiten): int
    {
        $maxClanTP = 0;
        $niveau = 0;
        while ($niveau < $User->Niveau) {
            $niveau++;
            $maxClanTP += $this->ClanTPNiveauClan($User, $niveau, $Besonderheiten);
        }
        return $maxClanTP;
    }
}
