<?php

final class Bekanntheit
{
    private $missionsPunkte = '';
    private $missionsPunkteAlt = '';

    public function updateBekanntheitMission($ninja, $missiRang)
    {
        //aktuelle Bekanntheit
        $aktBek = "SELECT `Bekanntheit` FROM user WHERE `id`= '$ninja'";
        $aktBek = mysql_query($aktBek);
        $aktBek = mysql_fetch_object($aktBek);
        $aktBek = $aktBek->Bekanntheit;
        $missionsWertAlt = $aktBek->Bekanntheit;
        //Missionspunkte aus der DB auslesen
        $bRangPlus = "SELECT COUNT(`missiId`) AS `bRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && `Bekanntheit` = '1' && `missiRang` LIKE 'B-Rang'";
        $bRangPlus = mysql_query($bRangPlus);
        $bRangPlus = mysql_fetch_object($bRangPlus);
        $bRangPlus = $bRangPlus->bRangPlus;
        $aRangPlus = "SELECT COUNT(`missiId`)  AS `aRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && `Bekanntheit` = '1' && `missiRang` LIKE 'A-Rang'";
        $aRangPlus = mysql_query($aRangPlus);
        $aRangPlus = mysql_fetch_object($aRangPlus);
        $aRangPlus = $aRangPlus->aRangPlus;
        $sRangPlus = "SELECT COUNT(`missiId`) AS `sRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && `Bekanntheit` = '1' && `missiRang` LIKE 'S-Rang'";
        $sRangPlus = mysql_query($sRangPlus);
        $sRangPlus = mysql_fetch_object($sRangPlus);
        $sRangPlus = $sRangPlus->sRangPlus;
        $missionsWert = 0;
        //Missinspunkte berechnen
        if ($missionsWertAlt < 100) {
            $bRangAlt = $missiRang == "B-Rang" ? $bRangPlus - 1 : $bRangPlus;
            $aRangAlt = $missiRang == "A-Rang" ? $aRangPlus - 1 : $aRangPlus;
            $sRangAlt = $missiRang == "S-Rang" ? $sRangPlus - 1 : $sRangPlus;
            $bRangAlt = $bRangAlt > 10 ? 10 : $bRangAlt;
            $aRangAlt = $aRangAlt > 10 ? 10 : $aRangAlt;
            $missionsWertAlt = $bRangAlt + $aRangAlt * 2 + $sRangAlt * 5;
            $this->missionsPunkteAlt = $missionsWertAlt;
            $missionsWertAlt = $missionsWertAlt < 100 ? $missionsWertAlt : 100;
            // echo $missionsWertAlt;
        }

        if ($missionsWertAlt < 100) {
            $bRangPlus = $bRangPlus > 10 ? 10 : $bRangPlus;
            $aRangPlus = $aRangPlus > 10 ? 10 : $aRangPlus;
            $missionsWert = $bRangPlus + $aRangPlus * 2 + $sRangPlus * 5;
            $this->missionsPunkte = $missionsWert;
            $missionsWert = $missionsWert < 100 ? $missionsWert : 100;
            // echo $missionsWert;
        }
        $Bek = $aktBek - $missionsWertAlt + $missionsWert;
        $Bek = $Bek < 100 ? $Bek : 100;
        if ($missionsWert > $missionsWertAlt) {
            $up = "UPDATE user SET `Bekanntheit` = '$Bek' WHERE id = '$ninja'";
            mysql_query($up);
            echo "Die Bekanntheit wurde erfolgreich angepasst.";
        }
        //echo $Bek;
        return $Bek;
    }

    public function getPunkte()
    {
        return $this->missionsPunkte;
    }

    public function getPunkteAlt()
    {
        return $this->missionsPunkteAlt;
    }

    public function setBekanntheitRang($ninja, $rang, $spezialAlt, $spezialNeu): string
    {
        $rang = (($rang > 3) ? 3 : $rang);
        if ($spezialNeu != 1 && $spezialAlt != 1 && $rang == 3) {
            $rang = 4;
        }
        $rangArray = [0, 20, 10, 10, 20];
        $bekanntheit = $rangArray[$rang];
        $up = "UPDATE user SET `Bekanntheit` = `Bekanntheit`+$bekanntheit WHERE id = '$ninja'";
        mysql_query($up);
        return "Die Bekanntheit wurde erfolgreich angepasst.";
    }

    public function setBekanntheit($ninja, $bekanntheit): string
    {
        mysql_query("UPDATE user SET `Bekanntheit` = '$bekanntheit' WHERE id = '$ninja'");
        return "Die Bekanntheit wurde erfolgreich angepasst.";
    }
}
