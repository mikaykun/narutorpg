<?php

final class gefahrenPotential
{
    private function aRang($User, $u_Jutsu, $u_Fähigkeiten): array|int
    {
        if ($User->Gefahrenpotential == 3) {
            $faeh3 = 0;
            $Punkte = $this->missionPunkte($User->ARP, $User->BRP, $User->SRP);
            foreach ($u_Fähigkeiten as $key => $Faeh) {
                if ($key != 'id') {
                    if ($Faeh < 2) {
                        unset($u_Fähigkeiten[$key]);
                    }
                    if ($Faeh >= 3) {
                        $faeh3++;
                    }
                }
            }
            foreach ($u_Jutsu as $key => $jutsu) {
                if ($key != 'id') {
                    if ($jutsu < 1) {
                        unset($u_Jutsu[$key]);
                    } else {
                        $sql = "SELECT `Taijutsu`,`Ninjutsu`,`Genjutsu` FROM Jutsu WHERE name = '$key'";
                        $query = mysql_query($sql);
                        $a_Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
                        if (gettype($a_Jutsu) == 'array') {
                            $Stufe = max($a_Jutsu);
                            if ($Stufe < 5) {
                                unset($u_Jutsu[$key]);
                            } elseif ($Stufe > 6) {
                                $faeh3++;
                            }
                        }
                    }
                }
            }
            $tps = new tpKosten();
            $TPausgegeben = $tps->tpBackGesamt($User, $u_Jutsu, $u_Fähigkeiten);
            $Punkte += $TPausgegeben / 10;
            $Punkte += $User->plusGP;
            $aufstieg = 1;
            if ($faeh3 < 15 || $Punkte < 75 || ($User->ARP + $User->SRP) < 3) {
                $aufstieg = 0;
            }
            return [$Punkte, $aufstieg];
        }
        return 0;
    }

    private function sRang($User, $u_Jutsu, $u_Fähigkeiten): array|int
    {
        if ($User->Gefahrenpotential == 3 || $User->Gefahrenpotential == 4) {
            $faeh3 = 0;
            $faeh4 = 0;
            $Punkte = $this->missionPunkte($User->ARP, 0, $User->SRP);
            foreach ($u_Fähigkeiten as $key => $Faeh) {
                if ($key != 'id') {
                    if ($Faeh < 3) {
                        unset($u_Fähigkeiten[$key]);
                    }
                    if ($Faeh == 3) {
                        $faeh3++;
                    }
                    if ($Faeh >= 4) {
                        $faeh4++;
                    }
                }
            }
            foreach ($u_Jutsu as $key => $jutsu) {
                if ($key != 'id') {
                    if ($jutsu < 1) {
                        unset($u_Jutsu[$key]);
                    } else {
                        $sql = "SELECT `Taijutsu`,`Ninjutsu`,`Genjutsu` FROM Jutsu WHERE name = '$key'";
                        $query = mysql_query($sql);
                        $a_Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
                        if (gettype($a_Jutsu) == 'array') {
                            $Stufe = max($a_Jutsu);
                            if ($Stufe < 7) {
                                unset($u_Jutsu[$key]);
                            } elseif ($Stufe > 8) {
                                $faeh4++;
                            }
                        }
                    }
                }
            }
            $tps = new tpKosten();
            $TPausgegeben = $tps->tpBackGesamt($User, $u_Jutsu, $u_Fähigkeiten);
            $Punkte += $TPausgegeben / 10;
            $Punkte += $User->plusGP;
            $aufstieg = 1;
            if (($faeh3 + $faeh4 * 2) < 15 || $faeh4 < 3 || $Punkte < 75 || $User->SRP < 3) {
                $aufstieg = 0;
            }
            return [$Punkte, $aufstieg];
        }
        return 0;
    }

    public function GPErhoeh($User, $u_Jutsu, $u_Fähigkeiten, $erhoeh): array
    {
        $newGp = 0;
        if (gettype($Punkte = $this->aRang($User, $u_Jutsu, $u_Fähigkeiten)) == 'array') {
            $newGp = 4;
        } elseif (gettype($Punkte = $this->sRang($User, $u_Jutsu, $u_Fähigkeiten)) == 'array') {
            //gehthier nicht rein!
            //echo gettype($Punkte = $this->sRang($User,$u_Jutsu,$u_Fähigkeiten));
            //echo $this->gpAnfrageOben($User->id);
            //echo $Punkte[1];
            $newGp = 5;
        }
        if ((!$this->gpAnfrageOben($User->id)) && $newGp > 0 && $Punkte[1] != 0) {
            if ($this->gpAnfrag($User, $newGp, $Punkte[0])) {
                echo 'GP-Anfrage gestellt.';
            }
        }
        return [$newGp, $Punkte[0]];
    }

    public function gpAnfrag($User, $newGp, $Punkte)
    {
        $Anmerkung = $Punkte . ' Punkte';
        $Zugriffe = "|RPCo|";
        $time = time();
        $Ausarbeitung = "Bitte prüft, ob das Gefahrenpotential dieses Users erhöht werden kann. Gefahrenpotential $newGp : $Anmerkung";
        $ins = "INSERT INTO Anfragen (Ninja, Zugriffe, Titel, Ausarbeitung, lastact, Zustand, Art)
					VALUES
					('|$User->id|', '|RPCo|', 'Gefahrenpotential', '$Ausarbeitung', '$time', '0', '1')";
        if (($ins = mysql_query($ins)) === true) {
            $sql = "SELECT id FROM Anfragen WHERE Ninja = '|$User->id|' ORDER BY id DESC Limit 0,1";
            $query = mysql_query($sql);
            $Eintrag = mysql_fetch_object($query);
            $Datum = date("d.m.Y, H:i");
            $ins = "INSERT INTO Anfragen_Posts (Von, Topic, Text, Postdatum) VALUES
				('', '$Eintrag->id', '$Ausarbeitung', '$Datum'),('', '$Eintrag->id', 'Keine SL-Informationen vorhanden', '$Datum'),('', '$Eintrag->id', 'Keine KR-Informationen vorhanden', '$Datum')";
            $ins = mysql_query($ins) or die("Fehler2");
            return true;
        }
        return false;
    }

    public function gpAnfrageOben($id): bool
    {
        $sql = "SELECT id FROM Anfragen WHERE `Ninja` LIKE '%|$id|%' && `Titel` = 'Gefahrenpotential'";
        $query = mysql_query($sql);
        if (mysql_num_rows($query)) {
            return true;
        }
        return false;
    }

    public function missionPunkte($ARP, $BRP, $SRP)
    {
        $mP = $BRP;
        $mP += $ARP * 2;
        $mP += $SRP * 6;
        return $mP;
    }
}
