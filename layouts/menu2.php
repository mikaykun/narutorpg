<style>
    .untermenu_bg_rechts {
        background-color: <?= sprintf('#%s', $Innenbereich ?? '8a8b6a'); ?>;
    }
</style>
<div class="menurechts">
    <?php
    $c_loged = \NarutoRPG\SessionHelper::getUserId();
    $traintage = new trainingstage();
    $tage = "SELECT * FROM allgdata WHERE id = '1'";
    $tages = mysql_query($tage) or die("Invalid query");
    $objectes = mysql_fetch_object($tages);
    $tager = date("d");
    $monat = date("m");
    $jahr = $objectes->Jahr;
    $tickerobject = nrpg_get_current_user();
    $dorfs2 = nrpg_get_current_character();

    if ($dorfs2->id > 0 && $dorfs2->feddig == 1) {
        echo '<div style="text-align:right;">';
        echo "<b><u>Datum: $tager.$monat.$jahr</u></b><br>";

        $sql = "SELECT Land FROM Reise_Orte WHERE Name = '$dorfs2->Standort' LIMIT 1";
        $u_dat = mysql_query($sql) or die("Invalid query");
        $Wetter = mysql_fetch_object($u_dat);
        $sql = "SELECT Wetter FROM Landdaten WHERE Land = '$Wetter->Land' LIMIT 1";
        $u_dat = mysql_query($sql) or die("Invalid query");
        $Wetter = mysql_fetch_object($u_dat);

        echo "<a href='/Wetter.php'>";
        echo match ($Wetter->Wetter) {
            "Klarer Himmel" => "<img  src='/layouts/wetter/KlarerHimmel2.png'>",
            "Leichter Schneefall" => "<img  src='/layouts/wetter/schneeleicht.png'>",
            "Schneefall" => "<img  src='/layouts/wetter/schneen.png'>",
            "Starker Schneefall" => "<img  src='/layouts/wetter/schneestark.png'>",
            "Leicht bewölkt" => "<img  src='/layouts/wetter/leichtbewoelkt.png'>",
            "Bewölkt" => "<img  src='/layouts/wetter/bewoelkt.png'>",
            "Stürmisch" => "<img  src='/layouts/wetter/stuermisch.png'>",
            "Sandsturm" => "<img  src='/layouts/wetter/sandsturm.png'>",
            "Leichter Regen" => "<img  src='/layouts/wetter/leichterregen.png'>",
            "Regen" => "<img  src='/layouts/wetter/regen.png'>",
            "Starker Regen" => "<img  src='/layouts/wetter/starkerregen.png'>",
            "Gewitter" => "<img  src='/layouts/wetter/gewitter.png'>",
            "Nebel" => "<img  src='/layouts/wetter/nebel.png'>",
            default => "Wetterübersicht",
        };
        echo "</a><br>";

        $res = 0;
        $c_loged = $_COOKIE["c_loged"];
        $dorfs2 = nrpg_get_current_character();
        $name = $dorfs2->name;
        $train = $dorfs2->Training;
        $until = $dorfs2->Dauer;
        echo "</b>Derzeitige Aktion: <br>";
        $days = '';
        $bonus = 1;
        if ($dorfs2->Bonustage >= 1) {
            $bonus += 1.5;
        }
        if ($dorfs2->doubleup > 0) {
            $bonus += 1.5;
        }
        if ($train) {
            if ($dorfs2->Biswert > 0 and $train != "") {
                echo "<b>$train <br>(Bis $dorfs2->Biswert)";
                $grundWerte = array('Verteidigung', 'Geschwindigkeit', 'St&auml;rke');
                foreach ($grundWerte as $gw) {
                    if (str_contains($train, $gw)) {
                        if ($gw == 'St&auml;rke') {
                            $gw = 'Staerke';
                            $gwn = 'Stärke';
                        } else {
                            $gwn = $gw;
                        }
                        $days = $traintage->grundwerte((($dorfs2->Biswert) - ($dorfs2->$gwn)), $u_besos, $gw, $dorfs2->Niveau);
                    }
                }
                if (str_contains($train, 'Ausdauer')) {
                    $days = $traintage->ausdauer($u_besos, (($dorfs2->Biswert) - ($dorfs2->Ausdauer)), $dorfs2->Niveau);
                }

                if (str_contains($train, 'Chakra')) {
                    $days = $traintage->chakra($u_besos, (($dorfs2->Biswert) - ($dorfs2->Chakra)), $dorfs2->Niveau);
                }
                $ninGenTai = array('Ninjutsu', 'Taijutsu', 'Genjutsu');
                foreach ($ninGenTai as $jutsuWert) {
                    if (str_contains($train, $jutsuWert)) {
                        $days = $traintage->ninGenTai($u_besos, $dorfs2->Biswert, $jutsuWert, $dorfs2->Lern, $dorfs2->Niveau) - $traintage->ninGenTai($u_besos, $dorfs2->$jutsuWert, $jutsuWert, $dorfs2->Lern, $dorfs2->Niveau);
                    }
                }
                $days /= $bonus;
                echo "(noch " . ceil($days) . " Tage)</b>";
            } else {
                echo "<b>$train <br>($until Tage) </b>";
            }
            if ($dorfs2->Training == "Puppe reparieren" or $dorfs2->Training == "Puppe verst&auml;rken" or $dorfs2->Training == "Puppe verstärken") {
                $sql = "SELECT Name FROM Marionetten WHERE id = '$dorfs2->Trainingsadi'";
                $query = mysql_query($sql);
                $Marioname = mysql_fetch_object($query);
                echo "<br>($Marioname->Name)";
            } elseif ($dorfs2->Training == "Training einer Fähigkeit" or $dorfs2->Training == "Training einer Jutsu") {
                echo "<br>($dorfs2->Trainingsadi)";
            }
            echo "<br><br>";
        } else {
            echo "Keine<br><br>";
        }
        echo '</div>';

        #Nachrichten
        $sql_select_unreadPMs = "SELECT id, COUNT(id) AS countPM FROM Posteingang WHERE Gelesen = '0' AND An = '$c_loged'";
        $query_select_unreadPMs = mysql_query($sql_select_unreadPMs);
        $unreadPMs = mysql_fetch_object($query_select_unreadPMs);

        $sql_select_inboxStatus = "SELECT readInboxStatus AS status FROM user WHERE id = '$c_loged'";
        $query_select_inboxStatus = mysql_query($sql_select_inboxStatus);
        $status = mysql_fetch_object($query_select_inboxStatus);

        echo '<div class="unterpunkt';
        if ($status->status == 0) {
            echo ' highlight';
        }
        echo '"><a href="/Nachrichten.php">';
        if ($unreadPMs->countPM > 0) {
            echo '<b>Nachrichten (' . $unreadPMs->countPM . ')</b>';
        } else {
            echo 'Nachrichten';
        }
        echo '</a></div>';
        #Nachrichten

        if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
            $menu_sql = "SELECT id FROM Adminbriefkasten WHERE Archiv = '0'";
            $menu_query = mysql_query($menu_sql);
            $menu_Adbr = mysql_fetch_object($menu_query);
            #Adminbriefkasten
            echo '<div class="unterpunkt';
            if ($menu_Adbr->id > 0) {
                echo ' highlight';
            }
            echo '"><a href="/Adminbriefkasten.php?Admin=1">Adminbriefkasten</a>';
            echo '</div>';
            #Adminbriefkasten
        }

        $votesnoch = 0;
        if ($dorfs2->Rang != "Akademist" and $dorfs2->Rang != "") {
            $sql2 = "SELECT id FROM Umfragen WHERE Dran = '1' AND Land = ''";
        } else {
            $sql2 = "SELECT id FROM Umfragen WHERE Dran = '1' AND Land = '' AND Abrang = '0'";
        }
        $query2 = mysql_query($sql2);
        while ($Umfrage = mysql_fetch_object($query2)) {
            $sql = "SELECT id FROM UmfragenA WHERE Umfrage = '$Umfrage->id' AND User = '$c_loged'";
            $query = mysql_query($sql);
            $Antwort = mysql_fetch_object($query);
            if (isset($Antwort->id) && $Antwort->id < 1) {
                $votesnoch = 1;
            }
        }
        // Umfragen dürfen nur von Usern gemacht werden die min. einen Monat registriert sind

        $select_reg_date = mysql_query("SELECT reg_date FROM userdaten WHERE id = '" . $dorfs2->id . "'");
        $result = mysql_fetch_object($select_reg_date);
        $reg_date = $result->reg_date;
        $current_time = time();
        if ((int)$reg_date + (30 * 24 * 60 * 60) <= $current_time || $reg_date == '') {
            $teilnahme = true;
        } else {
            $teilnahme = false;
        }

        #Umfragen
        echo '<div class="unterpunkt';
        if ($votesnoch >= 1 && $teilnahme == true) {
            echo ' highlight';
        }
        echo '"><a href="/Umfragen.php">Umfragen</a>';
        echo '</div>';
        #Umfragen

        #Informationen

        $menu_sql = "SELECT id FROM Neuerungen WHERE time > '$dorfs2->Neuerungen'";
        $menu_query = mysql_query($menu_sql);
        $Neuerung = mysql_fetch_object($menu_query);

        echo '<div class="unterpunkt';
        if (is_object($Neuerung) && $Neuerung->id > 0) {
            echo ' highlight';
        }
        echo '">';
        echo '<a tabindex="0" href="#">';
        echo 'Spieldaten';
        echo '</a>';

        echo '<div class="untermenurechts">';

        echo '<div class="untermenu_bg_rechts">';

        echo '<div class="unterpunkt';
        if (is_object($Neuerung) && $Neuerung->id > 0) {
            echo ' highlight';
        }
        echo '"><a href="/Neuerungen.php">Neuerungen</a></div>';
        echo '<div class="unterpunkt"><a href="/gamedata/deceased-ninja">Gestorbene Ninja</a></div>';
        echo '<div class="unterpunkt"><a href="/allgdats.php">Daten</a></div>';

        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Informationen

        #Wer ist online
        echo '<div class="unterpunkt"><a href="/Online.php">Wer ist online?</a>';
        echo '</div>';
        #Wer ist online
    }
    else {
        echo '<div style="text-align:right;">';

        #Informationen
        echo '<div class="unterpunkt">';

        echo '<a href="#" tabindex="0" onclick="fadeIn(\'rechts_test\'); return false;" onblur="fadeOut(\'rechts_test\');">';
        echo 'Spieldaten';
        echo '</a>';

        echo '<div class="untermenurechts" id="rechts_test">';

        echo '<div class="untermenu_bg_rechts">';

        echo '<div class="unterpunkt"><a href="/Neuerungen.php">Neuerungen</a></div>';
        echo '<div class="unterpunkt"><a href="/gamedata/deceased-ninja">Gestorbene Ninja</a></div>';
        echo '<div class="unterpunkt"><a href="/allgdats.php">Daten</a></div>';

        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Informationen

        #Wer ist online
        echo '<div class="unterpunkt"><a href="/Online.php">Wer ist online?</a>';
        echo '</div>';
        #Wer ist online

    }
    ?>
    <div style="text-align: center">
        <p><u><b>Affiliates:</b></u></p>
        <p>
            <a href="https://hiddenvillage.de/" target="_blank">
                <img src="/img/affiliates/hv_banner.png" alt="">
            </a>
        </p>
        <p>
            <a href="https://www.onepiecerpg.de/" target="_blank">
                <img src="/img/affiliates/oprpg.gif" alt="">
            </a>
        </p>
    </div>
</div>
