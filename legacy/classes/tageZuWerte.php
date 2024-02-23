<?php

final class tageZuWerte
{
    /**
     * Berechnet Traintage eines Grundwerts
     */
    public function grundwerte(object $u_Besonderheiten, $Niveau, $Tage, string $wert): float|int
    {
        $Tage = ($Tage < 0) ? 0 : $Tage;
        $Wert = 1;
        $Wert += 0.35;
        if ($u_Besonderheiten->$wert == 1) {
            $Wert += (0.25);
        }
        if ($u_Besonderheiten->sKorper == 1) {
            $Wert -= 0.25;
        }
        $Wert = round($Wert, 2);
        return $Tage * $Wert;
    }

    public function ausdauer($u_Besonderheiten, $Niveau, $Tage): float|int
    {
        $Tage = ($Tage < 0) ? 0 : $Tage;
        $Wert = 5;
        $Wert += 2.5;
        if ($u_Besonderheiten->Ausdauernd == 1) {
            $Wert *= 1.5;
        }
        if ($u_Besonderheiten->sAusdauer == 1) {
            $Wert /= 1.5;
        }
        $Wert = round($Wert, 2);
        return $Wert * $Tage;
    }

    public function chakra($u_Besonderheiten, $Niveau, $Tage): float|int
    {
        $Tage = ($Tage < 0) ? 0 : $Tage;
        $Wert = 2.5;
        $Wert += 1.25;
        if ($u_Besonderheiten->Chakra == 1) {
            $Wert *= 1.5;
        }
        if ($u_Besonderheiten->sChakra == 1) {
            $Wert /= 1.5;
        }
        $Wert = round($Wert, 3);
        return $Wert * $Tage;
    }

    public function geldplus($Tage, $Art): float|bool|int
    {
        $werte = [500, 1000, 2000];
        if ($Tage > 0) {
            return $Tage * $werte[$Art];
        }
        return false;
    }

    public function tageVerteil($dorfs2, $u_Besonderheiten, $chakra, $ausdauer, $geschw, $vert, $str, $tageback, $spalte, $sure, $verteil, $Niveau, $geldT, $posting): void
    {
        $strback = $dorfs2->Stärke + $this->grundwerte($u_Besonderheiten, $Niveau, $str, 'Staerke');
        $vertback = $dorfs2->Verteidigung + $this->grundwerte($u_Besonderheiten, $Niveau, $vert, 'Verteidigung');
        $geschwback = $dorfs2->Geschwindigkeit + $this->grundwerte($u_Besonderheiten, $Niveau, $geschw, 'Geschwindigkeit');
        $ausdback = $dorfs2->Ausdauer + $this->ausdauer($u_Besonderheiten, $Niveau, $ausdauer);
        $chakraback = $dorfs2->Chakra + $this->chakra($u_Besonderheiten, $Niveau, $chakra);
        $geldb = $dorfs2->Geld + $this->geldplus($geldT, 1);
        $tageGesamt = $ausdauer + $chakra + $geschw + $vert + $str + $verst + $pupW + $geldT;
        if ($tageGesamt < 0) {
            $tageGesamt = 0;
        }
        if ($verteil == 1) {
            if ($tageGesamt > $tageback) {
                echo 'Du hast ';
                echo $tageGesamt - $tageback;
                echo ' Tage zu viel verteilt.<br>
				Bitte passe die verteilten Tage an.';
                $sure = 0;
            } elseif ($sure == 0) {
                echo 'Du hast ';
                echo $tageGesamt;
                echo ' Tage verteilt.<br>
				Bitte prüfe, ob die Verteilung korrekt ist und passe sie gegebenenfalls an.';
                echo 'Du hast danach noch ';
                echo $tageback - $tageGesamt;
                echo ' Tage zur Verteilung übrig.';
            }
            echo '<br>';

        }
        if ($sure == 1) {
            $aendern = "UPDATE user set `$spalte` =  `$spalte`-'$tageGesamt',`Ausdauer` = '$ausdback', `Chakra` = '$chakraback', `Geschwindigkeit` = '$geschwback', `Stärke` = '$strback', `Verteidigung` = '$vertback', `Geld` = '$geldb' WHERE id = '$dorfs2->id'";
            mysql_query($aendern) or die("Fehler beim updaten des Ninja!18");
        }
        echo 'Du kannst noch ';
        echo $tageback;
        echo ' Trainingstage verteilen<br>';

        echo 'Wie sollen diese verteilt werden?
			 <form method=\'POST\' action=\'?verteil=1\'>
			 Ausdauer (';
        echo $dorfs2->Ausdauer;
        echo '->';
        echo $ausdback;
        echo '): <input name=ausdauer id=\"ausdauer\" type=text autocomplete=off value=\'' . $ausdauer . '\'><br>
			 Chakra (';
        echo $dorfs2->Chakra;
        echo '->';
        echo $chakraback;
        echo '): <input name=chakra id=\"chakra\" type=text autocomplete=off value=\'' . $chakra . '\'><br>
		 Geschwindigkeit (';
        echo $dorfs2->Geschwindigkeit;
        echo '->';
        echo $geschwback;
        echo '): <input name=geschw id=\"geschw\" type=text autocomplete=off value=\'' . $geschw . '\'><br>
		 Verteidigung (';
        echo $dorfs2->Verteidigung;
        echo '->';
        echo $vertback;
        echo '): <input name=vert id=\"vert\" type=text autocomplete=off value=\'' . $vert . '\'><br>
		 Stärke (';
        echo $dorfs2->Stärke;
        echo '->';
        echo $strback;
        echo '): <input name=str id=\"str\" type=text autocomplete=off value=\'' . $str . '\'><br>';

        echo 'Geld 1k/Tag (';
        echo $dorfs2->Geld;
        echo '->';
        echo $geldb;
        echo '): <input name=geldT id=\"geldT\" type=text autocomplete=off value=\'' . $geldT . '\'><br>';
        echo 'Neue Werte jetzt eintragen? (Kann nicht rückgängig gemacht werden)';
        echo '<select name="sure" id="sure">
					<option value=\'0\'>Nein</option>
					<option value=\'1\'>Ja</option>
			   </select>';
        echo '<input type=\'submit\' value=\'weiter\'></form>';
    }
}
