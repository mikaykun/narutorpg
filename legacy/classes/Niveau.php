<?php

final class Niveau
{
    private float $missionsPunkte = 0.0;

    public function getNiveau(int $ninja, $lf): array
    {
        //aktuelles Niveau
        $aktNiveau = "SELECT `Niveau`,`NiveauMinus` FROM user WHERE `id`= '$ninja'";
        $aktNiveau = mysql_query($aktNiveau);
        $aktNiveau = mysql_fetch_object($aktNiveau);
        $NiveauMinus = $aktNiveau->NiveauMinus;
        $aktNiveau = $aktNiveau->Niveau;
        //Missionspunkte aus der DB auslesen
        $dRangPlus = "SELECT COUNT(`missiId`) AS `dRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` = '8' || `bewertung` = '6' || `bewertung` = '4') && `missiRang` LIKE 'D-Rang'";
        $dRangPlus = mysql_query($dRangPlus);
        $dRangPlus = mysql_fetch_object($dRangPlus);
        $dRangPlus = $dRangPlus->dRangPlus;
        $dRangMinus = "SELECT COUNT(`missiId`)  AS `dRangMinus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` != '8' && `bewertung` != '6' && `bewertung` != '4')&& `missiRang` LIKE 'D-Rang'";
        $dRangMinus = mysql_query($dRangMinus);
        $dRangMinus = mysql_fetch_object($dRangMinus);
        $dRangMinus = $dRangMinus->dRangMinus;
        $cRangPlus = "SELECT COUNT(`missiId`) AS `cRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` = '8' || `bewertung` = '6' || `bewertung` = '4') && `missiRang` LIKE 'C-Rang'";
        $cRangPlus = mysql_query($cRangPlus);
        $cRangPlus = mysql_fetch_object($cRangPlus);
        $cRangPlus = $cRangPlus->cRangPlus;
        $cRangMinus = "SELECT COUNT(`missiId`)  AS `cRangMinus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` != '8' && `bewertung` != '6' && `bewertung` != '4')&& `missiRang` LIKE 'C-Rang'";
        $cRangMinus = mysql_query($cRangMinus);
        $cRangMinus = mysql_fetch_object($cRangMinus);
        $cRangMinus = $cRangMinus->cRangMinus;
        $bRangPlus = "SELECT COUNT(`missiId`) AS `bRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` = '8' || `bewertung` = '6' || `bewertung` = '4') && `missiRang` LIKE 'B-Rang'";
        $bRangPlus = mysql_query($bRangPlus);
        $bRangPlus = mysql_fetch_object($bRangPlus);
        $bRangPlus = $bRangPlus->bRangPlus;
        $bRangMinus = "SELECT COUNT(`missiId`)  AS `bRangMinus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` != '8' && `bewertung` != '6' && `bewertung` != '4') && `missiRang` LIKE 'B-Rang'";
        $bRangMinus = mysql_query($bRangMinus);
        $bRangMinus = mysql_fetch_object($bRangMinus);
        $bRangMinus = $bRangMinus->bRangMinus;
        $aRangPlus = "SELECT COUNT(`missiId`)  AS `aRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` = '8' || `bewertung` = '6' || `bewertung` = '4') && `missiRang` LIKE 'A-Rang'";
        $aRangPlus = mysql_query($aRangPlus);
        $aRangPlus = mysql_fetch_object($aRangPlus);
        $aRangPlus = $aRangPlus->aRangPlus;
        $aRangMinus = "SELECT COUNT(`missiId`)  AS `aRangMinus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` != '8' && `bewertung` != '6' && `bewertung` != '4') && `missiRang` LIKE 'A-Rang'";
        $aRangMinus = mysql_query($aRangMinus);
        $aRangMinus = mysql_fetch_object($aRangMinus);
        $aRangMinus = $aRangMinus->aRangMinus;
        $sRangPlus = "SELECT COUNT(`missiId`) AS `sRangPlus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` = '8' || `bewertung` = '6' || `bewertung` = '4') && `missiRang` LIKE 'S-Rang'";
        $sRangPlus = mysql_query($sRangPlus);
        $sRangPlus = mysql_fetch_object($sRangPlus);
        $sRangPlus = $sRangPlus->sRangPlus;
        $sRangMinus = "SELECT COUNT(`missiId`) AS `sRangMinus` FROM MissionBewertung WHERE `Id`= $ninja && (`bewertung` != '8' && `bewertung` != '6' && `bewertung` != '4')&& `missiRang` LIKE 'S-Rang'";
        $sRangMinus = mysql_query($sRangMinus);
        $sRangMinus = mysql_fetch_object($sRangMinus);
        $sRangMinus = $sRangMinus->sRangMinus;
        $niveau = $aktNiveau;
        //Missinspunkte berechnen
        if ($aktNiveau < 4) {
            $dRangWert = ($dRangPlus - $dRangMinus) * 0.25;
            $dRangWert = ($dRangWert > 1) ? 1 : $dRangWert;
            $cRangWert = ($cRangPlus - $cRangMinus) * 0.5;
            $cRangWert = ($cRangWert > 2) ? 2 : $cRangWert;
            $bRangWert = $bRangPlus - $bRangMinus;
            $bRangWert = ($bRangWert > 11) ? 11 : $bRangWert;
            $aRangWert = ($aRangPlus - $aRangMinus) * 2;
            $sRangWert = ($sRangPlus - $sRangMinus) * 2.5;
            $missionsWert = $dRangWert + $cRangWert + $bRangWert + $aRangWert + $sRangWert;
            $lfP = [75 => -1, 100 => 0, 125 => 1, 150 => 2, 200 => 3];
            $missionsWert += $lfP[$lf];
            $missionsWert -= $NiveauMinus;
            $this->missionsPunkte = $missionsWert;
            if ($missionsWert >= 8) {
                if ($aktNiveau < 3) {
                    $niveau = 3;
                } elseif ($missionsWert >= 20) {
                    $niveau = 4;
                }
            } else {
                $niveau = $aktNiveau;

                //$niveau = $missionsWert >= 10?$aktNiveau < 3?3:$missionsWert >= 20?4:3:$aktNiveau;
            }
        }
        return [$niveau, $aktNiveau];
    }

    public function getPunkte(): float
    {
        return $this->missionsPunkte;
    }

    public function setNiveau(int $ninja, $niveau): string
    {
        $aktNiveau = "SELECT `Niveau`,`niveauSperre`,`Clan`,`PunkteTPMax` FROM user WHERE `id`= $ninja";
        $aktNiveau = mysql_query($aktNiveau);
        $aktNiveau = mysql_fetch_object($aktNiveau);
        $besos = "SELECT `Sand` FROM Besonderheiten WHERE `id`= $ninja";
        $besos = mysql_query($besos);
        $besos = mysql_fetch_object($besos);
        $ninjaNiveau = $aktNiveau->Niveau;
        $niveauSperre = $aktNiveau->niveauSperre;
        $tps = new tpKosten();
        $ClanTP = $tps->ClanTPNiveauClan($aktNiveau, $niveau, $besos);
        $PunkteTP = ($aktNiveau->PunkteTPMax / 3) * ($niveau - 1);
        if (($niveauSperre == 1 && $niveau > $ninjaNiveau) || ($niveauSperre == 0 && $niveau < $ninjaNiveau)) {
            return "Fehler beim Updaten des Niveaus.";
        }
        $up = "UPDATE user SET `Niveau` = '$niveau' WHERE id = '$ninja'";
        mysql_query($up);
        if ($niveau == 2) {
            $up = "UPDATE user SET `Geld` = `Geld`+30000 WHERE id = '$ninja' AND `maxNiveau` < '$niveau' ";
            mysql_query($up);
        }
        $up = "UPDATE user SET `rangTp` = `rangTp`+50,
			`maxNiveau`='$niveau', `ClanTP` = `ClanTP`+$ClanTP,
			`PunkteTP` = `PunkteTP`+$PunkteTP WHERE id = '$ninja' AND
			`maxNiveau` < '$niveau' ";
        echo $up;
        mysql_query($up);
        return "Das Niveau wurde erfolgreich angepasst.";
    }

    public function sperrNiveau(int $ninja, int $sperre): string
    {
        $up = "UPDATE user SET `niveauSperre` = '$sperre' WHERE id = '$ninja'";
        mysql_query($up);
        return "Das Niveau wurde erfolgreich gesperrt/entsperrt.";
    }
}
