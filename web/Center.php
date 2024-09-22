<?php

verify_loggedin_user();

require __DIR__ . '/../Menus/layout1.inc';

$sql = "SELECT * FROM user WHERE id = '$dorfs->id'";
$u_dat = mysql_query($sql) or die("Invalid query1");
$u_dat2 = mysql_fetch_object($u_dat);
$Rang = $u_dat2->Rang;
$test = $u_dat2->feddig;
$u_dat4 = mysql_query("SELECT * FROM Fähigkeiten WHERE id = '$dorfs->id'") or die("Invalid query4");
$u_faeh = mysql_fetch_array($u_dat4, MYSQL_ASSOC);
$sqllll = "SELECT * FROM Jutsuk WHERE id = '$dorfs->id'";
$u_dat4 = mysql_query("SELECT * FROM Jutsuk WHERE id = '$dorfs->id'") or die("Invalid query4");
$u_Jutsu = mysql_fetch_array($u_dat4, MYSQL_ASSOC);
$tps = new tpKosten();
$TPausgegeben = $tps->tpBackGesamt($dorfs2, $u_Jutsu, $u_faeh);
$EETPausgegeben = $tps->howMuchRAllEEs($dorfs2, $u_Jutsu);
[$sAllowed, $tpUnderS, $sErlernt] = $tps->howMuchS($dorfs2, $u_Jutsu, $u_faeh);

if ($test != "1") {
    ?>
    <h1>Willkommen</h1>
    <p>Bitte schließe die Charaktererstellung ab.</p>
    <a href="/Ninja.php">Zur Charakterstellung</a>
    <?php
}
elseif (isset($_GET['Training']) && $_GET['Training'] == 1)
{
    echo "<b>Übersicht über deine vergangenen Logs und dein vergangenes Training</b><br><br>
        <table border='0' width='95%'>
        <tr>
        <td width='15%'><center><b>Datum</b></center></td>
        <td><center><b>Training</b></center></td>
        </tr>";
    $Land = "";
    $sql = "SELECT * FROM NPCSystem WHERE Ninkriegt = '$dorfs->id' OR NPC = '$dorfs->id' ORDER BY id DESC";
    $query = mysql_query($sql);
    while ($Daten = mysql_fetch_object($query)) {
        if ($Daten->Land != $Land) {
            echo "<tr><td colspan='2'><b>$Land</b></td></tr>";
        }
        echo "<tr><td colspan='2'><hr></td></tr>
            <tr>
            <td><center>$Daten->Datum</center></td>
            <td><center>$Daten->Text</center></td>
            </tr>";
        if ($Daten->Kritik == "") {
            $Daten->Kritik = "Kritik nicht vorhanden (Log möglicher Weise vor dem 29.08.10 eingetragen worden)";
        }
        $Daten->Kritik = nl2br($Daten->Kritik);
        echo "<tr><td></td><td colspan='2'>
            <a href=\"javascript:show('Kritik$Daten->id');\">Kritik</a>
            <div id='Kritik$Daten->id' style='display: none;'>$Daten->Kritik</div>
            </td></tr>
            ";
    }
    echo "</table>";
}
else
{
    if ($u_dat2->Clan == "Inuzuka Familie") {
        if ($u_dat2->Tiererstellt != 1) {
            echo "<a href='Inuzuka.php'>Du hast noch kein Tier gewählt! Wähle es hier aus!</a><br><br>";
        }
    }

    include(__DIR__ . "/../layouts/Overview/Overview1.php");
    include(__DIR__ . "/../layouts/Overview/OverviewNinja.php");

    ?>
    <script>
        function showU1() {
            UserUbersicht.style.display = 'block';
            UserBeschreibung.style.display = 'none';
            UserGeschichte.style.display = 'none';
            UserFragenChar.style.display = 'none';
        }

        function showU2() {
            UserUbersicht.style.display = 'none';
            UserBeschreibung.style.display = 'block';
            UserGeschichte.style.display = 'none';
            UserFragenChar.style.display = 'none';
        }

        function showU3() {
            UserUbersicht.style.display = 'none';
            UserBeschreibung.style.display = 'none';
            UserGeschichte.style.display = 'block';
            UserFragenChar.style.display = 'none';
        }

        function showU4() {
            UserUbersicht.style.display = 'none';
            UserBeschreibung.style.display = 'none';
            UserGeschichte.style.display = 'none';
            UserFragenChar.style.display = 'block';
        }
    </script>
    <?php

    $id = $dorfs2->id;

    if ($u_dat2->id < 1) {
        if ($derwirdnichgezeigt != 1) {
            echo "Diesen User gibt es nicht!";
        }
    } else {
        $id2 = "$id";
        $abfrage = "SELECT * FROM user WHERE id = '$id'";
        $ergebnis = mysql_query($abfrage);
        $row = mysql_fetch_object($ergebnis);

        $abfrage2 = "SELECT * FROM userdaten WHERE id = '$id'";
        $ergebnis2 = mysql_query($abfrage2);
        $row2 = mysql_fetch_object($ergebnis2);

        echo "<table border='0' width='95%'>
            <tr>
            <td width='25%' background='/layouts/Uebergang/Oben2.png' align='center'><a href=\"javascript:showU1();\">Übersicht</a></td>
            <td width='25%' background='/layouts/Uebergang/Oben2.png' align='center'><a href=\"javascript:showU2();\">Charakterbeschreibung</a></td>
            <td width='25%' background='/layouts/Uebergang/Oben2.png' align='center'><a href=\"javascript:showU3();\">Charaktergeschichte</a></td>
            <td width='25%' background='/layouts/Uebergang/Oben2.png' align='center'><a href=\"javascript:showU4();\">20 Fragen</a></td>
            </tr>";

        $ver = 0;
        $sql = "SELECT * FROM `Verwarnungen` WHERE User = '$row->id'";
        $query = mysql_query($sql);
        while ($Verwarnungen = mysql_fetch_object($query)) {
            if ($ver == 0) {
                echo "<tr><td colspan='4' align='center' background='/layouts/Uebergang/Untergrund.png'>
                <b><font color='#cc0033'>Verwarnungen:</font></b><br><table border='0' width='90%'>";
                $ver = 1;
            }
            echo "<tr><td><font color='#cc0033'>\"<b>$Verwarnungen->Bereich</b>\"</font></td><td><font color='#cc0033'>$Verwarnungen->Text</font></td>";
            if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                echo "<td><a href='?AufhebVerwarnung=$Verwarnungen->id&usernam=$usernam&decknam=$decknam'>Entfernen</a></td>";
            }
            echo "</tr>";
        }

        if ($ver == 1) {
            echo "</table></td></tr>";
        }

        if ($row2->Gesperrt == 1) {
            echo "<tr><td colspan='4' align='center' background='/layouts/Uebergang/Oben2.png'><b><font color='#FF0000'>User ist gesperrt!</font></b></td></tr>";
        }
        if ($row2->Timetoloesch > 0) {
            $Loesch = $row2->Timetoloesch - 1;
            echo "<tr><td colspan='4' align='center' background='/layouts/Uebergang/Oben2.png'><b><font color='#cc0033'>Dieser Account loescht sich in $Loesch Tag(en) selbst!</font></b></td></tr>";
        }
        if ($row2->Charloesch > 0) {
            $Loesch = $row2->Charloesch - 1;
            echo "<tr><td colspan='4' align='center' background='/layouts/Uebergang/Oben2.png'><b><font color='#cc0033'>Dieser Charakter loescht sich in $Loesch Tag(en) selbst!</font></b></td></tr>";
        }

        echo "
            <tr>
            <td colspan='4' background='/layouts/Uebergang/Untergrund.png'>
            <div id='UserUbersicht' style='display:block'>";
        if (isset($_GET['rangtpUmtausch']) && $_GET['rangtpUmtausch'] == 1) {
            if ($u_dat2->rangTp >= $mengeUmtausch) {
                $update = "UPDATE user SET `rangTp` =`rangTp`-$mengeUmtausch, `Trainingspunkte` = `Trainingspunkte` + $mengeUmtausch WHERE id = '$dorfs->id'";
                mysql_query($update) or die('Fehler beim Umtauschen der Rangtp');
                $datum = date("d.m.Y");
                $gesamtMenge = $dorfs2->Trainingspunkte + $mengeUmtausch;
                $ins = "INSERT INTO NPCSystem (NPC, Text, Datum, Land, Training, Ninkriegt, Passiertemit, TP) VALUES
                        ('0', '$dorfs2->name wandelt $mengeUmtausch RangTP in TP um ($dorfs2->Trainingspunkte + $mengeUmtausch = " . $gesamtMenge . " TP)', '$datum', '$dorfs2->Heimatdorf', '', '$dorfs2->id', '1', '1')";
                $ins = mysql_query($ins) or die ('Fehler beim Umtauschen der TP');
                echo $mengeUmtausch . ' Rangtp erfolgreich in TP umgetauscht.';
                $dorfs2->Trainingspunkte += $mengeUmtausch;
                $u_dat2->rangTp -= $mengeUmtausch;
            } else {
                echo 'So viele TP kannst du nicht umtauschen.';
            }
        }
        if (isset($_GET['punktetpUmtausch']) && $_GET['punktetpUmtausch'] == 1) {
            if ($u_dat2->PunkteTP >= $mengeUmtausch) {
                $update = "UPDATE user SET `PunkteTP` =`PunkteTP`-$mengeUmtausch, `Trainingspunkte` = `Trainingspunkte` + $mengeUmtausch WHERE id = '$dorfs->id'";
                mysql_query($update) or die('Fehler beim Umtauschen der Punktetp');
                $datum = date("d.m.Y");
                $gesamtMenge = $dorfs2->Trainingspunkte + $mengeUmtausch;
                $ins = "INSERT INTO NPCSystem (NPC, Text, Datum, Land, Training, Ninkriegt, Passiertemit, TP) VALUES
                        ('0', '$dorfs2->name wandelt $mengeUmtausch PunkteTP in TP um ($dorfs2->Trainingspunkte + $mengeUmtausch = " . $gesamtMenge . " TP)', '$datum', '$dorfs2->Heimatdorf', '', '$dorfs2->id', '1', '1')";
                $ins = mysql_query($ins) or die ('Fehler beim Umtauschen der TP');
                echo $mengeUmtausch . ' Punktetp erfolgreich in TP umgetauscht.';
                $dorfs2->Trainingspunkte += $mengeUmtausch;
                $u_dat2->PunkteTP -= $mengeUmtausch;
            } else {
                echo 'So viele TP kannst du nicht umtauschen.';
            }
        }

        echo "
            <table border='0' width='90%' id='table2'>
            <tr>
            <td width='50%' align='center'valign='top'>
            ";

        if ($dorfs2->Rang == "Missing-Nin") {
            $dorf = $dorfs2->Angehoer;
            $Zusatzrang = "A";
        } else {
            $dorf = $dorfs2->Heimatdorf;
            $Zusatzrang = "";
        }
        echo '<img border="0" src="/Bilder/Infos/Landbilder/' . $dorf . $Zusatzrang . '.bmp">';

        $tager = $u_dat2->Geburtstag;
        if ($tager > 334){$monat = 12; $tager -= 334;}
        elseif ($tager > 304){$monat = 11; $tager -= 304;}
        elseif ($tager > 273){$monat = 10; $tager -= 273;}
        elseif ($tager > 243){$monat = 9; $tager -= 243;}
        elseif ($tager > 212){$monat = 8; $tager -= 212;}
        elseif ($tager > 181){$monat = 7; $tager -= 181;}
        elseif ($tager > 151){$monat = 6; $tager -= 151;}
        elseif ($tager > 120){$monat = 5; $tager -= 120;}
        elseif ($tager > 90){$monat = 4; $tager -= 90;}
        elseif ($tager > 59){$monat = 3; $tager -= 59;}
        elseif ($tager > 31){$monat = 2; $tager -= 31;}
        elseif ($tager > 0){$monat = 1; $tager -= 0;}
        $monat = str_replace("10", "Oktober", $monat);
        $monat = str_replace("11", "November", $monat);
        $monat = str_replace("12", "Dezember", $monat);
        $monat = str_replace("1", "Januar", $monat);
        $monat = str_replace("2", "Februar", $monat);
        $monat = str_replace("3", "M&auml;rz", $monat);
        $monat = str_replace("4", "April", $monat);
        $monat = str_replace("5", "Mai", $monat);
        $monat = str_replace("6", "Juni", $monat);
        $monat = str_replace("7", "Juli", $monat);
        $monat = str_replace("8", "August", $monat);
        $monat = str_replace("9", "September", $monat);

        echo "
            <br><br>
            <table border='0' width='100%'>
            <tr>
            <td width='50%'><b>Alter:</b></td>
            <td width='50%'>$dorfs2->Alt Jahre</td>
            </tr>
            <tr>
            <td><b>Geburtstag:</b></td>
            <td>$tager. $monat</td>
            </tr>
            <tr>
            <td><b>Clan:</b></td>
            <td>$dorfs2->Clan</td>
            </tr>
            <tr>
            <td><b>Rang:</b></td>
            <td>";
        if ($dorfs2->Spezial == 1 and $dorfs2->Rang == "Jounin") {
            echo "Spezial-Jounin";
        } else {
            echo "$dorfs2->Rang";
        }
        if ($dorfs2->Rang == "Missing-Nin") {
            switch ($dorfs2->Rangwert) {
                case 1:
                    echo " (Akademist-Niveau)";
                    break;
                case 2:
                    echo " (Genin-Niveau)";
                    break;
                case 3:
                    echo " (Chuunin-Niveau)";
                    break;
                case 4:
                    echo " (Jounin-Niveau)";
                    break;
            }
        }
        $raenge = array("Akademist","Genin","Chuunin","Jounin");
        echo "</td>
            </tr>
            <tr>
            <td>
            <b>Niveau:</b>
            </td>
            <td>";
        $niveau = $dorfs2->Niveau;
        echo $raenge[$niveau - 1];
        if ($niveau != 4) {
            $myNiveau = new Niveau();
            $myNiveau->getNiveau($dorfs2->id, $dorfs2->Lern);
            $punkte = $myNiveau->getPunkte();
            echo " (Punkte: $punkte)";
        }
        echo "</td>
            </tr>";
                //Test für den Erfahrungswert
                echo "<tr>
                    <td><b>Erfahrung:</b></td>
            <td>";
        switch ($dorfs2->maxNiveau) {
            case 1:
                echo " Akademist";
                break;
            case 2:
                echo " Genin";
                break;
            case 3:
                echo " Chuunin";
                break;
            case 4:
                echo " Jounin";
                break;
        }
        echo "</td>
            </tr>";
        echo "<tr>
            <td>
            <b>Bekanntheit:</b>
            </td>
            <td>";
        echo $dorfs2->Bekanntheit;
        echo "</td>
            </tr>";
        echo "<tr>
            <td><b>Gefahrenpotential:</b></td>
            <td>";
        $GP = array('E-Rang', 'D-Rang', 'C-Rang', 'B-Rang', 'A-Rang', 'S-Rang');
        echo $GP[$dorfs2->Gefahrenpotential];
        $gp = new gefahrenPotential();
        $gefahrenp = $gp->GPErhoeh($u_dat2, $u_Jutsu, $u_faeh, 0);
        if ($gefahrenp[0] == 4 || $gefahrenp[0] == 5) {
            echo '(Punkte ';
            echo $GP[$gefahrenp[0]];
            echo ' GP : ';
            echo $gefahrenp[1];
            echo ' )';
        }
        echo "</td>
            </tr>
            <tr>
            <td><b>Aufenthaltsort:</b></td>
            <td>$dorfs2->Standort</td>
            </tr>
            <tr>
            <td><b>Trainingspunkte:</b></td>
            <td>$dorfs2->Trainingspunkte</td>
            </tr>";

                echo  "</td><td><b>Clan-TP:</b></td>
                        <td>$dorfs2->ClanTP</td>
            </tr>";
            if($u_dat2->rangTp > 0)
                {
                    echo "<tr>
                    <td><b>+ Rang-TP:</b></td>
                    <td>$u_dat2->rangTp <form method='POST' action='?rangtpUmtausch=1'><select name='mengeUmtausch'>";
                    $i = 0;
                    while($i < $u_dat2->rangTp)
                    {
                        $i++;
                        echo "<option value='$i'>$i";
                    }
                    echo "<input type='submit' value='umtauschen'>
                    </form></td></tr>";
                }
                if($u_dat2->PunkteTP > 0)
                {
                    echo "<tr>
                    <td><b>+ Erstellungspunkte-TP:</b></td>
                    <td>$u_dat2->PunkteTP <form method='POST' action='?punktetpUmtausch=1'><select name='mengeUmtausch'>";
                    $i = 0;
                    while($i < $u_dat2->PunkteTP)
                    {
                        $i++;
                        echo "<option value='$i'>$i";
                    }
                    echo "<input type='submit' value='umtauschen'>
                    </form></td></tr>";
                }
            echo "<tr>
            <td><b>Ausgegebene TP*:</b></td>
            <td>$TPausgegeben</td>
            </tr>";
            echo "<tr>
            <td><b>Für EEs ausgegebene TP*:</b></td>
            <td>$EETPausgegeben</td>
            </tr>";
            echo '<tr>
            <td><b><a href="https://wiki.narutorpg.de/index.php?title=Regeln_von_Eigenentwicklungen#Anforderungen_f.C3.BCr_Eigenentwicklungen">Derzeit in EEs investierbare TP</a>*:</b></td>
            <td>';
        if (($TPausgegeben - $EETPausgegeben) < 400) {
            echo floor($TPausgegeben * 0.15 - $EETPausgegeben);
        } else {
            echo 'unbegrenzt';
        }
            echo "</td>
            </tr>";
            if($dorfs2->Niveau == 4)
            {
                echo "<tr>
                <td><b>Ausgegebene TP unter S*:</b></td>
                <td>";
                echo $tpUnderS;
                echo "</td>
                </tr>";
                echo "<tr>
                <td><b>Bereits erlernte S-Rangs*:</b></td>
                <td>";
                echo $sErlernt;
                echo "</td>
                </tr>";
                echo "<tr>
                <td><b>Derzeit erlernbare S-Rangs*:</b></td>
                <td>";
                echo $sAllowed;
                echo "</td>
                </tr>";
            }

            $multis= array();
            $sql   = "SELECT `user`.`name` AS `multName`,`userdaten`.`multFrei` as `multFreig` FROM `multi` LEFT JOIN `user` ON `multi`.`uId2` = `user`.`id` LEFT JOIN `userdaten` ON `multi`.`uId2` = `userdaten`.`id` WHERE `uId1` = '$dorfs->id' && (`multOk` = '0' OR `multOk` = '2') && `Counter` > '0'";
            $query = mysql_query($sql);
            while ($NPC = mysql_fetch_object($query)) {
                $multis[] = $NPC->multName;
                $multfrei[$NPC->multName] = $NPC->multFreig;
            }
            $query2 = mysql_query("SELECT u.name AS multName, ud.multFrei AS multFreig FROM multi m LEFT JOIN user u ON m.uId1 = u.id LEFT JOIN userdaten ud ON m.uId2 = ud.id WHERE uId2 = '{$dorfs->id}' && (multOk = '0' OR multOk = '2') && Counter > 0");
            while ($NPC2 = mysql_fetch_object($query2)) {
                $multis[] = $NPC->multName;
                $multfrei[$NPC->multName] = $NPC->multFreig;
            }
            $multis=array_unique($multis);
            if(count($multis)){
                    echo "<tr><td><b>Nicht genehmigter Multi mit: </b></td><td>";
                foreach($multis as $multName){
                    echo "<a href='userpopup.php?usernam=$multName'>$multName</a> ";
                    if($multfrei[$multName] == 1)
                    {
                        echo '(Anfragen/EEfreigabe)';
                    }
                }
                echo "</td></tr>";
            }
            $multis= array();
            $sql   = "SELECT `user`.`name` AS `multName`,`userdaten`.`multFrei` as `multFreig` FROM `multi` LEFT JOIN `user` ON `multi`.`uId2` = `user`.`id` LEFT JOIN `userdaten` ON `multi`.`uId2` = `userdaten`.`id` WHERE `uId1` = '$dorfs->id' && `multOk` = '1' && `Counter` > '0'";
            $query = mysql_query($sql);
            while ($NPC = mysql_fetch_object($query)) {
                $multis[] = $NPC->multName;
                $multfrei[$NPC->multName] = $NPC->multFreig;
            }
            $sql2 = "SELECT u.name AS multName, userdaten.multFrei AS multFreig FROM multi LEFT JOIN user u ON `multi`.`uId1` = u.`id` LEFT JOIN userdaten ON `multi`.`uId2` = `userdaten`.`id` WHERE `uId2` = '$dorfs->id' && `multOk` = '1' && `Counter` > '0'";
            $query2 = mysql_query($sql2);
            while ($NPC2 = mysql_fetch_object($query2)) {
                $multis[] = $NPC->multName;
                $multfrei[$NPC->multName] = $NPC->multFreig;
            }
            $multis=array_unique($multis);
            if(count($multis)){
                    echo "<tr><td><b>Genehmigter Multi mit: </b></td><td>";
                foreach($multis as $multName){
                    echo "<a href='userpopup.php?usernam=$multName'>$multName</a> ";
                }
                echo "</td></tr>";
            }

            echo "</table>";
            echo '* Abweichungen durch nicht vom System erfasste EEs m&ouml;glich';


        if ($dorfs2->Team > 0) {
            $query = mysql_query("SELECT id, Name FROM Teams WHERE id = '$dorfs2->Team'");
            $Team = mysql_fetch_object($query);

            echo "<br><br><b>$Team->Name</b>";

            echo "<table border='0' width='100%' id='table3'><tr><td align='center' width='33%'>";
            $sql = "SELECT id, name, Passfoto FROM user WHERE Team = '$dorfs2->Team' AND id != '$dorfs2->id' LIMIT 1";
            $query = mysql_query($sql);
            $Teammate = mysql_fetch_object($query);
            if ($Teammate->id > 0) {
                echo "<a href='userpopup.php?usernam=$Teammate->name'>";

                if ($Teammate->Passfoto != "") {
                    echo "<img src='$Teammate->Passfoto' name='Passbild' border='0' width='75' height='75'><br>";
                } else {
                    echo "<img src='/img/user/no_image.svg' name='Passbild' border='0'><br>";
                }
                echo "$Teammate->name</a>";
            }

            echo "</td><td align='center' width='33%'>";
            $sql = "SELECT id, name, Passfoto FROM user WHERE Team = '$dorfs2->Team' AND id != '$dorfs2->id' LIMIT 1,1";
            $query = mysql_query($sql);
            $Teammate = mysql_fetch_object($query);
            if ($Teammate->id > 0) {
                echo "<a href='userpopup.php?usernam=$Teammate->name'>";

                if ($Teammate->Passfoto != "") {
                    echo "<img src='$Teammate->Passfoto' name='Passbild' border='0' width='75' height='75'><br>";
                } else {
                    echo "<img src='/img/user/no_image.svg' name='Passbild' border='0'><br>";
                }
                echo "$Teammate->name</a>";
            }

            echo "</td><td align='center' width='33%'>";
            $sql = "SELECT * FROM Teams WHERE id = '$dorfs2->Team'";
            $query = mysql_query($sql);
            $gruppe = mysql_fetch_object($query);

            $sql = "SELECT id, name, Passfoto, Heimatdorf FROM user WHERE id = '$gruppe->Leiter'";
            $query = mysql_query($sql);
            $Leiter = mysql_fetch_object($query);
            if ($Leiter->id > 0) {
                if ($Leiter->Heimatdorf == $gruppe->Land) {
                    echo "<a href='userpopup.php?usernam=$Leiter->name'>";

                    if ($Leiter->Passfoto != "") {
                        echo "<img src='$Leiter->Passfoto' border='0' name='Passbild' width='75' height='75'><br>";
                    } else {
                        echo "<img src='/img/user/no_image.svg' name='Passbild' border='0'><br>";
                    }
                    echo "$Leiter->name</a>";
                } else {
                    $sql = "SELECT id, NPC, Passfoto FROM NPC WHERE land = '$gruppe->Land" . "gakure' AND User = '$Leiter->id'";
                    $query = mysql_query($sql);
                    $NPC = mysql_fetch_object($query);
                    if ($NPC->id > 0) {
                        echo "<a href='NPC.php?id=$NPC->id'>";

                        if ($NPC->Passfoto != "") {
                            echo "<img src='$NPC->Passfoto' border='0' name='Passbild' width='75' height='75'><br>";
                        } else {
                            echo "<img src='/img/user/no_image.svg' name='Passbild' border='0'><br>";
                        }
                        $split = explode(" ", $NPC->NPC);
                        echo "$split[0]</a>";
                    }
                }
            }

            echo "</td></tr></table>";
        }

        echo "<td width='50%' align='center' valign='top'>";

        if ($dorfs2->Rang != "Missing-Nin") {
            echo "<br><b>$dorfs2->Heimatdorf" . "gakure</b><br>";
        } else {
            echo "<br><b>Heimatlos</b><br>";
        }

        echo "<br>";

        if ($dorfs2->Passfoto != "") {
            echo "<img border='0' src='$dorfs2->Passfoto'>";
        } else {
            echo "<img border='0' src='/img/user/no_image.svg' width='200' height='200'>";
        }

        echo "<br>
            <br>
            <b>$dorfs2->name</b><br>
            <br><a href='?Training=1'>Vergangene Logs</a><br><br>
            <b>Missionserfahrung:</b>";

        echo "<br>
            &nbsp;<table border='0' width='60%' id='table4'>
            <tr>
            <td width='15'><b>D</b></td>
            <td align='right'>
            <p align='right'>$dorfs2->D ($dorfs2->DRP)</td>
            </tr>
            <tr>
            <td width='15'><b>C</b></td>
            <td align='right'>$dorfs2->C ($dorfs2->CRP)</td>
            </tr>
            <tr>
            <td width='15'><b>B</b></td>
            <td align='right'>$dorfs2->B ($dorfs2->BRP)</td>
            </tr>
            <tr>
            <td width='15'><b>A</b></td>
            <td align='right'>$dorfs2->A ($dorfs2->ARP)</td>
            </tr>
            <tr>
            <td width='15'><b>S</b></td>
            <td align='right'>$dorfs2->S ($dorfs2->SRP)</td>
            </tr>
            </table>

            <br><br><b>Kampferfahrung:</b><br>
            &nbsp;<table border='0' width='60%' id='table4'>
            <tr>
            <td><b>Siege</b></td>
            <td align='right'>
            <p align='right'>$dorfs2->Siege</td>
            </tr>
            <tr>
            <td><b>Niederlagen</b></td>
            <td align='right'>$dorfs2->Niederlagen</td>
            </tr>
            <tr>
            <td><b>Unentschieden</b></td>
            <td align='right'>$dorfs2->Unendschieden</td>
            </tr>

            </table>
            <p><br>
            &nbsp;</td>
            </tr>
            </table>";

        echo "</div>";

        echo "<div id='UserBeschreibung' style='display:none'>";

        $bild = $object->pic;
        if ($bild != "" and $row->Picchecked == 1) {
            echo "<center><img src='$bild' name='Charpic'></center>";
        } elseif ($row->Picchecked != 1 and $bild != "") {
            echo "<i>Bild nicht freigeschaltet</i>";
        }
        echo "<br>
            <b>Beschreibung von $row->name:</b><br><br>";
        if ($row->CBchecked == 1 OR $row->Charabeschr == ""){
            $Beschr = $u_dat2->Charabeschr;

            $Beschr = str_replace("[b]", "<b>", $Beschr);
            $Beschr = str_replace("[/b]", "</b>", $Beschr);
            $Beschr = str_replace("[i]", "<i>", $Beschr);
            $Beschr = str_replace("[/i]", "</i>", $Beschr);
            $Beschr = str_replace("[u]", "<u>", $Beschr);
            $Beschr = str_replace("[/u]", "</u>", $Beschr);
            $Beschr = str_replace("[B]", "<b>", $Beschr);
            $Beschr = str_replace("[/B]", "</b>", $Beschr);
            $Beschr = str_replace("[I]", "<i>", $Beschr);
            $Beschr = str_replace("[/I]", "</i>", $Beschr);
            $Beschr = str_replace("[U]", "<u>", $Beschr);
            $Beschr = str_replace("[/U]", "</u>", $Beschr);

            $Beschr = preg_replace("#\[COLOR=(.*)\](.*)\[\/COLOR\]#isU" , " <font color='$1'> $2 </font> " , $Beschr);
            $Beschr = preg_replace("#\[img\](.*)\[\/img\]#isU" , " <a target='_blank' href='$1'> <img style='max-height:550px;max-width:550px' src='$1'></a> " , $Beschr);
            $Beschr = preg_replace("#\[URL=(.*)\](.*)\[\/URL\]#isU" , " <a href='$1'>$2</a> " , $Beschr);
            $Beschr = preg_replace("#\[URL\](.*)\[\/URL\]#isU", " <a href='$1'>Link</a> ", $Beschr);


            $Beschr = nl2br($Beschr);
            echo "$Beschr";
        } else {
            echo "<i>Noch nicht freigeschaltet.</i>";
        }
        echo "<br><br></div>";

        echo "<div id='UserGeschichte' style='display:none'>";

        echo "<br><b>Charaktergeschichte von $row->name:</b><br><br>";
        if ($row->CSchecked == 1 or $row->Charstory == "") {
            $Beschr = $u_dat2->Charstory;

            $Beschr = str_replace("[b]", "<b>", $Beschr);
            $Beschr = str_replace("[/b]", "</b>", $Beschr);
            $Beschr = str_replace("[i]", "<i>", $Beschr);
            $Beschr = str_replace("[/i]", "</i>", $Beschr);
            $Beschr = str_replace("[u]", "<u>", $Beschr);
            $Beschr = str_replace("[/u]", "</u>", $Beschr);
            $Beschr = str_replace("[B]", "<b>", $Beschr);
            $Beschr = str_replace("[/B]", "</b>", $Beschr);
            $Beschr = str_replace("[I]", "<i>", $Beschr);
            $Beschr = str_replace("[/I]", "</i>", $Beschr);
            $Beschr = str_replace("[U]", "<u>", $Beschr);
            $Beschr = str_replace("[/U]", "</u>", $Beschr);

            $Beschr = preg_replace("#\[COLOR=(.*)\](.*)\[\/COLOR\]#isU" , " <font color='$1'> $2 </font> " , $Beschr);
            $Beschr = preg_replace("#\[img\](.*)\[\/img\]#isU" , " <a target='_blank' href='$1'> <img style='max-height:550px;max-width:550px' src='$1'></a> " , $Beschr);
            $Beschr = preg_replace("#\[URL=(.*)\](.*)\[\/URL\]#isU" , " <a href='$1'>$2</a> " , $Beschr);
            $Beschr = preg_replace("#\[URL\](.*)\[\/URL\]#isU" , " <a href='$1'>Link</a> " , $Beschr);
            $Beschr = preg_replace("#\[url=(.*)\](.*)\[\/url\]#isU" , " <a href='$1'>$2</a> " , $Beschr);
            $Beschr = preg_replace("#\[url\](.*)\[\/url\]#isU" , " <a href='$1'>Link</a> " , $Beschr);

            $Beschr = nl2br($Beschr);
            echo "$Beschr";
            echo "<br><br>";}else{echo "<i>Charaktergeschichte noch nicht zugelassen!</i><br><br>";}

        echo "</div>";

        echo "<div id='UserFragenChar' style='display:none'>";

        echo "<br><b>20 Fragen und Antworten zum Charakter $dorfs2->name:</b><br><br>";

        if ($dorfs2->Fragenchecked != 1) {
            echo "<i>Noch nicht freigeschaltet.</i><br><br>";
        }

        $Fragen = 1;
        while ($Fragen < 21) {
            switch ($Fragen) {
                case 1:
                    $Titel = "Wie sieht dein Charakter aus?";
                    break;
                case 2:
                    $Titel = "Wie wirkt der Charakter auf einen Fremden?";
                    break;
                case 3:
                    $Titel = "Wie ist der Charakter aufgewachsen?";
                    break;
                case 4:
                    $Titel = "Hat der Charakter noch eine enge Bindung zu Menschen aus seiner Jugend?";
                    break;
                case 5:
                    $Titel = "Warum wird dein Charakter Ninja?";
                    break;
                case 6:
                    $Titel = "Wo ist der Charakter schon gewesen?";
                    break;
                case 7:
                    $Titel = "Ist der Charakter sehr abergläubisch/religiös?";
                    break;
                case 8:
                    $Titel = "Wie steht der Charakter zu Technologie, Feuerwaffen, Ninjutsu und Genjutsu?";
                    break;
                case 9:
                    $Titel = "Für wen oder was würde der Charakter sein Leben riskieren?";
                    break;
                case 10:
                    $Titel = "Was ist der größte Wunsch des Charakters?";
                    break;
                case 11:
                    $Titel = "Was fürchtet der Charakter mehr, als alles andere auf der Welt?";
                    break;
                case 12:
                    $Titel = "Wie sieht es mit seiner Moral und seiner Gesetzestreue aus?";
                    break;
                case 13:
                    $Titel = "Ist er Fremden gegenüber aufgeschlossen?";
                    break;
                case 14:
                    $Titel = "Welchen Stellenwert hat Leben für ihn?";
                    break;
                case 15:
                    $Titel = "Wie steht der Charakter zu Tieren?";
                    break;
                case 16:
                    $Titel = "Hat der Charakter einen Sinn für Schönheit?";
                    break;
                case 17:
                    $Titel = "Was isst und trinkt der Charakter am liebsten?";
                    break;
                case 18:
                    $Titel = "Wie sieht es mit der Liebe aus?";
                    break;
                case 19:
                    $Titel = "Gibt es ein dunkles Geheimnis aus seiner Vergangenheit?";
                    break;
                case 20:
                    $Titel = "Welche Charakterzüge bestimmen ihn?";
                    break;
            }

            $Wert = "Frage$Fragen";
            $Antwort = nl2br($dorfs2->$Wert);
            if ($Antwort != "") {
                echo "<u><b>Frage $Fragen:</b> $Titel</u><br>";
                echo "$Antwort<br><br>";
            }
            $Fragen += 1;
        }

        echo "</div>";
        echo "</td></tr></table>";
        $u_dat5 = mysql_query("SELECT * FROM userdaten WHERE id LIKE '$id'") or die("Invalid query");
        $object2 = mysql_fetch_object($u_dat5);
        $admin = $object2->admin;
    }
}

get_footer();
