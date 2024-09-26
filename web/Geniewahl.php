<?php

include(__DIR__ . "/../Menus/layout1.inc");

$genie = new Geniewahl();
$genie->start($faeh, $dorfs->id, $dorfs2->tplf, $dorfs2->name, $dorfs2->Heimatdorf, $faeheintrag);

class Geniewahl
{
    private int $mytplf = 0;

    public function start($faeh, $uid, $tplf, $uname, $Heimatdorf, $faeheintrag): void
    {
        $this->mytplf = $tplf;
        if ($this->mytplf > 0) {
            if ($faeheintrag == 1) {
                $this->faeheintrag($faeh, $uid, $uname, $Heimatdorf);
            }
            $this->faehauswahl($uid);
        } else {
            echo 'Du kannst keine weiteren LF-TP verteilen.';
        }
    }

    protected function faehok($lehre, $forschung, $lfart): bool
    {
        $ok = 1; // Why? It's always 1... so why not just return true?
        if ($ok == 1) {
            return true;
        } elseif ($lehre < 1 && $forschung < 1) {
            if ($lfart == 'lehre' || $lfart == 'forschung') {
                return true;
            }
            return false;
        } elseif ($lehre < 1) {
            if ($lfart == 'lehre') {
                return true;
            }
            return false;
        } elseif ($lfart == 'forschung') {
            return true;
        }
        return false;
    }

    protected function faehinfos($uid): array
    {
        $sql = "SELECT * FROM Fähigkeiten WHERE id = '$uid'";
        $query = mysql_query($sql);
        $Faehigks = mysql_fetch_array($query, MYSQL_ASSOC);
        $lffaeh = [];
        $forschung = 0;
        $lehre = 0;
        $sql = "SELECT `name`,`Bereich2`,`Kosten1`,`Tabellenname` FROM Informationen_Faehs WHERE `LFFaeh` = '1'";
        $query = mysql_query($sql);
        while ($Faeh = mysql_fetch_array($query, MYSQL_ASSOC)) {
            if ($Faehigks[$Faeh['Tabellenname']] < 1) {
                $lffaeh[$Faeh['Tabellenname']] = [$Faeh['name'], $Faeh['Bereich2'], $Faeh['Kosten1']];
            } else {
                $lehre += ($Faeh['Bereich2'] == 'lehre') ? 1 : 0;
                $forschung += ($Faeh['Bereich2'] == 'forschung') ? 1 : 0;
            }
        }
        foreach ($lffaeh as $tabname => $faeh) {
            if (!$this->faehok($lehre, $forschung, $faeh[1])) {
                unset($lffaeh[$tabname]);
            }
        }
        return $lffaeh;
    }

    protected function faehauswahl($uid): void
    {
        $faehs = $this->faehinfos($uid);
        echo 'F&auml;higkeiten w&auml;hlen:<br>
        <form method=\'POST\' action=\'?faeheintrag=1\'>
        <select name=\'faeh\'>';
        foreach ($faehs as $tabname => $faeh) {
            echo '<option value="' . $tabname . '">' . $faeh[0] . '</option>';
        }
        echo '<input type=\'submit\' value=\'F&auml;higkeit w&auml;hlen\'></td></form>';
    }

    protected function faeheintrag($faeh, $uid, $uname, $Heimatdorf): void
    {
        $faehs = $this->faehinfos($uid);
        if (array_key_exists($faeh, $faehs)) {
            $abzug = ($faehs[$faeh][2] > $this->mytplf) ? $this->mytplf : $faehs[$faeh][2];
            $update = 'UPDATE user SET `tplf` = `tplf`-' . $abzug . ' WHERE id = \'' . $uid . '\'';
            mysql_query($update) or die('Fehler beim wählen der LFFähigkeit 1');

            $update = 'UPDATE Fähigkeiten SET `' . $faeh . '` = \'1\' WHERE id = \'' . $uid . '\'';
            mysql_query($update) or die('Fehler beim wählen der LFFähigkeit 2');
            $this->mytplf -= $faehs[$faeh][2];
            if ($this->mytplf < 0) {
                $update = 'UPDATE user SET `Trainingspunkte` = `Trainingspunkte`+' . $this->mytplf . ' WHERE id = \'' . $uid . '\'';
                mysql_query($update) or die('Fehler beim wählen der LFFähigkeit 1');
                $TP = ' und es wurden ' . abs($this->mytplf) . ' TP Differenz abgezogen!';
            }
            $datum = date("d.m.Y");
            $ins = 'INSERT INTO NPCSystem (NPC, Text, Datum, Land,  Ninkriegt, Passiertemit, TP) VALUES
                    ( \'0\', \'' . $uname . ' erhält die Fähigkeit  ' . $faehs[$faeh][0] . ' auf Grund von Lernfähigkeit' . $TP . '\', \'' . $datum . '\', \'' . $Heimatdorf . '\', \'' . $uid . '\', \'1\', \'1\')';
            mysql_query($ins) or die('Fehler beim Umtauschen der TP');
        }
    }
}

get_footer();
