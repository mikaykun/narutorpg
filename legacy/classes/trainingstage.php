<?php

final class trainingstage
{
    /*
     * Berechnet Traintage eines Grundwerts
     */
    public function grundwerte($Userwert, $u_Besonderheiten, $wert, $Niveau): float|int
    {
        $Teiler = 1;
        if ($u_Besonderheiten->$wert == 1) {
            $Teiler += 0.25;
        }
        if ($u_Besonderheiten->sKorper == 1) {
            $Teiler -= 0.25;
        }
        $Teiler += 0.35;
        return ($Userwert) / $Teiler;
    }

    public function ausdauer($u_Besonderheiten, $Userwert, $Niveau): float|int
    {
        $Teiler = 5;
        $Teiler += 2.5;
        $Wert = ($Userwert) / $Teiler;
        if ($u_Besonderheiten->Ausdauernd == 1) {
            $Wert /= 1.5;
        }
        if ($u_Besonderheiten->sAusdauer == 1) {
            $Wert *= 1.5;
        }
        return $Wert;
    }

    public function chakra($u_Besonderheiten, $Userwert, $Niveau): float
    {
        $Teiler = 2.5;
        $Teiler += 1.25;
        $Wert = ($Userwert) / $Teiler;
        if ($u_Besonderheiten->Chakra == 1) {
            $Wert /= 1.5;
        }
        if ($u_Besonderheiten->sChakra == 1) {
            $Wert *= 1.5;
        }
        return $Wert;
    }

    private function missionen($User)
    {
        $Tagebekommen = 0;
        $Tagebekommen += ($User->C - $User->CRP);
        $Tagebekommen += ($User->B - $User->BRP);
        $Tagebekommen += ($User->A - $User->ARP);
        $Tagebekommen += ($User->S - $User->SRP);
        return $Tagebekommen;
    }

    private function alleGrundwerte($User, object $u_Besonderheiten): float|int
    {
        $Tagebekommen = 0;
        $vorteil = [
            'Stärke' => 'Staerke',
            'Verteidigung' => 'Verteidigung',
            'Geschwindigkeit' => 'Geschwindigkeit',
        ];
        foreach ($vorteil as $spalte => $wert) {
            $Tagebekommen += $this->grundwerte(
                $User->$spalte - 10,
                $u_Besonderheiten,
                $wert,
                1
            );
        }
        return $Tagebekommen;
    }

    private function gfsTage($u_Besonderheiten): int
    {
        return ($u_Besonderheiten->Gentle == 1) ? 1 : 0;
    }

    /**
     * $User is object
     * Benötigt werden:
     * $User->
     * Clan,Stärke,Verteidigung,Geschwindigkeit,Ausdauer,Chakra,Lern
     * ,C,CRP,D,DRP,B,BRP,A,ARP,S,SRP
     * $u_Besonderheiten is object
     * Benötigt werden:
     * Alle Vor/Nachteile, die sich auf Grundwerte auswirken
     */
    public function gesamtChar(object $User, object $u_Besonderheiten, $trainWerte): float
    {
        $Tagebekommen = 0;
        $Tagebekommen += $this->alleGrundwerte($User, $u_Besonderheiten);
        $schnitt = ($User->Geschwindigkeit + $User->Stärke + $User->Verteidigung) / 3;
        $endschnitt = $Tagebekommen / 3;
        echo $User->name . '<br>';
        echo 'GW:' . $Tagebekommen . 'Schnitt:' . $schnitt . 'vorher:' . $trainWerte->grundwerte($u_Besonderheiten, 0, $endschnitt, 'Geschwindigkeit') . '<br>';
        $TagebekommenOld = $Tagebekommen;
        $Tagebekommen += $this->ausdauer($u_Besonderheiten, $User->Ausdauer - 50, 1);
        echo 'Ausd:' . $Tagebekommen . 'Wert' . $User->Ausdauer . 'Now' . $trainWerte->ausdauer($u_Besonderheiten, 0, $Tagebekommen - $TagebekommenOld) . '<br>';
        $Tagebekommen += $this->chakra($u_Besonderheiten, $User->Chakra - 50, 1);
        echo 'Chakravor' . $User->Chakra . 'Chakranach' . $trainWerte->chakra($u_Besonderheiten, 1, $this->chakra($u_Besonderheiten, $User->Chakra - 50, 1));
        echo 'Chakra:' . $Tagebekommen . '<br>';

        if ($User->Clan == "Hyuuga Clan") {
            $Tagebekommen += $this->gfsTage($u_Besonderheiten);
            echo 'Hyuuga gfs:' . $Tagebekommen . '<br>';
        }
        $Tagebekommen += $this->missionen($User);
        echo 'Missis:' . $Tagebekommen . '<br>';
        $Tagebekommen = ceil($Tagebekommen);
        echo 'Gesamt:' . $Tagebekommen . '<br>';
        return $Tagebekommen;
    }
}
