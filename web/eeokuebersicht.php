<?php

include(__DIR__ . "/../Menus/layout1.inc");

if ($dorfs->CoAdmin == 3 || $dorfs->admin == 3) {
    $tps = new tpKosten();
    echo 'Rot = darf derzeit weder eine EE erstellen, noch oben haben. Orange = darf derzeit eine EE oben haben und hat derzeit eine oben. Weiß = k&ouml;nnte derzeit eine stellen.<br>';
    echo '<b>EEAnforderungen:</b><p>';
    echo '<table id="faehs" class="tablesorter">
    <thead>
    <tr>
        <th>Name</th>
        <th>TPAusGesamt</th>
        <th>EETPOk</th>
        <th>Multi</th>
        <th>EEAktiv</th>
        <th>Inaktiv</th>
        <th>MissionenLastYear</th>
        <th>EELernOK</th>
        <th>TPBisUnbegrenzt</th>
        <th>StuffUnderS</th>
    </tr>
    </thead>
    <tbody>';
    $numok = 0;
    $sql = "SELECT * FROM `user` WHERE `Rangwert` > '1'";
    $usrquery = mysql_query($sql);
    $num = 0;
    $numOben = 0;
    while ($usr = mysql_fetch_object($usrquery)) {
        $num++;
        $okEE = 1;
        $sql = "SELECT * FROM Jutsuk WHERE id = '$usr->id'";
        $query = mysql_query($sql);
        $u_Jutsu = mysql_fetch_array($query, MYSQL_ASSOC);
        $sql = "SELECT * FROM Fähigkeiten WHERE id = '$usr->id'";
        $query = mysql_query($sql);
        $u_Fähigkeiten = mysql_fetch_array($query, MYSQL_ASSOC);
        $TPAusGes = $tps->tpBackGesamt($usr, $u_Jutsu, $u_Fähigkeiten);
        $stuffUnderS = $tps->allStuffUnderS($usr, $u_Jutsu, $u_Fähigkeiten);
        $EETPausgegeben = $tps->howMuchRAllEEs($usr, $u_Jutsu);
        $multiQ    = "SELECT `uId2` FROM `multi` WHERE (multOk = '0' OR `multOk` = '2') AND `uId1` = '$usr->id' AND `Counter` > '1' AND `uId2` < `uId1`";
        $multiQ  = mysql_query($multiQ);
        $multiQ    = "SELECT `uId1` FROM `multi` WHERE (multOk = '0' OR `multOk` = '2') AND `uId2` = '$usr->id' AND `Counter` > '1' AND `uId1` < `uId2`";
        $multiQ  = mysql_query($multiQ);
        $sql2    = "SELECT COUNT(*) FROM X_Jutsueintrag WHERE Ninja = '$usr->id' AND Eingetragen = '0' AND Zustand != '2'";
        $EEALTAkt  = mysql_query($sql2);
        $EEALTAkt = mysql_fetch_row($EEALTAkt);
        $sql = "SELECT COUNT( `id` ) as cnt FROM `Missionen` WHERE `Ninja` LIKE '%|$usr->id|%' AND `Abschlusszeit` > ( UNIX_TIMESTAMP() -60 *60 *24 *356 ) ";
        $query = mysql_query($sql);
        $MissisLastYear = mysql_fetch_array($query, MYSQL_ASSOC);
        $MissisLastYear = $MissisLastYear['cnt'];
        $tPBisUnbegr = 0;
        if(($TPAusGes - $EETPausgegeben) < 400) {
            $TPEEOK = floor($TPAusGes * 0.15 - $EETPausgegeben);
            if($TPEEOK < 0):$okEE = 0; endif;
            $tPBisUnbegr = 400 - ($TPAusGes - $EETPausgegeben);
        } else {
            $TPEEOK = 'unbegrenzt';
        }
        $okEE = ($TPAusGes < 80) ? 0 : $okEE;
        if($multii = mysql_fetch_row($multiQ)) {
            $Multi = 'Ja';
            $okEE = 0;
        } elseif($multii = mysql_fetch_row($multiQ2)) {
            $Multi = 'Ja';
            $okEE = 0;
        } else {
            $Multi = 'Nein';
        }
        if($EEALTAkt[0] > 0) {
            $EEAktiv = 'Ja';
        } else {
            $EEAktiv = 'Nein';
        }
        if($MissisLastYear < 2) {
            $okEE = 0;
        }
        if($usr->Inaktivitaet == 1) {
            $okEE = 0;
        }
        $col = '';
        if($stuffUnderS < $TPAusGes) {
            $col = ' style="background-color:ff00ff;"';
        } elseif($okEE == 0) {
            $col = ' style="background-color:red;"';
        } elseif($EEAktiv == 'Ja') {
            $col = ' style="background-color:orange;"';
            $numOben++;
        }
        echo '<tr' . $col . '><td' . $col . '>' . $usr->name . '</td><td' . $col . '>' . $TPAusGes . '</td><td' . $col . '>' . $TPEEOK . '</td><td' . $col . '>' . $Multi . '</td><td' . $col . '>' . $EEAktiv . '</td><td' . $col . '>' . $usr->Inaktivitaet . '</td><td' . $col . '>' . $MissisLastYear . '</td><td' . $col . '>' . $okEE . '</td><td' . $col . '>' . $tPBisUnbegr . '</td><td' . $col . '>' . $stuffUnderS . '</td></tr>';
        if ($okEE == 1) {
            $numok++;
        }
    }
    echo '</tbody></table>';
    echo 'Derzeit k&ouml;nnen ' . $numok . ' User  von ' . $num . ' Usern ab Genin eine EE beantragen.';
    echo $numOben . ' davon haben derzeit bereits eine EE hochgeladen.';
} else {
    echo 'Nur Coadmins haben hier Einsicht!';
}

get_footer();
