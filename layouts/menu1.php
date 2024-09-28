<style>
    .untermenu_bg,
    .unterpunkt {
        background-color: <?= '#' . $Innenbereich; ?>;
    }
</style>
<script src="/js/menu1.js"></script>
<div class="menulinks">
    <?php
    $dorfs = nrpg_get_current_user();
    $dorfs2 = nrpg_get_current_character();

    if ($dorfs2->id > 0 && $dorfs2->feddig) {
        echo '<div class="bild">';

        if ($dorfs2->Rang == "Missing-Nin") {
            echo '<div class="bild2">';
            echo '<img border="0" src="/Bilder/Infos/Landbilder/Missing.png">';
            echo '</div>';
        }

        if ($dorfs2->Heimatdorf != "") {
            echo '<img border="0" src="/Bilder/Infos/Landbilder/' . $dorfs2->Heimatdorf . '.bmp">';
        }

        echo '</div>';

        #Startseite
        echo '<div class="unterpunkt"><a href="/index.php">Startseite</a>';
        echo '</div>';
        #Startseite

        #Informationen
        echo '<div class="unterpunkt"><a href="/Infos.php">Informationen</a>';
        echo '</div>';
        #Informationen

        #Usercenter

        #Highlight-Abfrage

        $menu_sql = "SELECT id FROM Anfragen WHERE Zustand < '1' AND lastuser < lastadmin AND Ninja LIKE '%|$dorfs->id|%' AND Standby = '0'";
        $menu_query = mysql_query($menu_sql);
        $menu_Anfrage = mysql_fetch_object($menu_query);
        if ($menu_Anfrage === false || $menu_Anfrage->id < 1) {
            $menu_sql = "SELECT id FROM Anfragen WHERE Zustand < '1' AND lastuser > lastadmin AND Ninja LIKE '%|$dorfs->id|%' AND Userlast != '$dorfs->id' AND Userlast != '0' AND Standby = '0'";
            $menu_query = mysql_query($menu_sql);
            $menu_Anfrage = mysql_fetch_object($menu_query);
        }

        $menu_sql = "SELECT id FROM X_Jutsueintrag WHERE ((Entwickende = '0' AND Zustand != '2') OR (`Anderungswunsch` = '1' AND `Entwickende` = '1')) AND lastuser < lastadmin AND Ninja = '$dorfs->id'";
        $menu_query = mysql_query($menu_sql);
        $menu_EE = mysql_fetch_object($menu_query);

        echo '<div class="unterpunkt';
        if ((is_object($menu_Anfrage) && $menu_Anfrage->id > 0) or (is_object($menu_EE) && $menu_EE->id > 0)) {
            echo ' highlight';
        }
        echo '">';

        echo '<a href="#" tabindex="0" onclick="fadeIn(\'links_usercenter\'); return false;"
            onblur="fadeOut(\'links_usercenter\');">';
        echo 'Usercenter';
        echo '</a>';

        echo '<div class="untermenulinks" id="links_usercenter">';

        echo '<div class="untermenu_bg">';

        echo '<div class="unterpunkt"><a href="/Center.php">Ninja Daten</a></div>';
        echo '<div class="unterpunkt"><a href="/Inventar.php">Inventar</a></div>';
        echo '<div class="unterpunkt"><a href="/Notizen.php">Notizen</a></div>';
        echo '<div class="unterpunkt';
        if (is_object($menu_Anfrage) && $menu_Anfrage->id > 0) {
            echo ' highlight';
        }
        echo '"><a href="/Anfragen.php?Ubersicht=1">Anfragen</a></div>';

        if ($dorfs2->Rang != "" and $dorfs2->Rang != "Akademist") {
            echo '<div class="unterpunkt';
            if (is_object($menu_EE) && $menu_EE->id > 0) {
                echo ' highlight';
            }
            echo '"><a href="/Spezzieintrag.php?Ubersicht=1">Eigenentwicklung</a></div>';
        }

        echo '<div class="unterpunkt"><a href="/Gruppe.php">Logs & Team</a></div>';

        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Usercenter


        #Forum
        echo '<div class="unterpunkt"><a href="/Forum_uber.php">Forum</a>';
        echo '</div>';
        #Forum

        echo '&nbsp;<br>';


        #Ninja
        echo '<div class="unterpunkt" >';

        echo '<a href="#" tabindex="0" onclick="fadeIn(\'links_ninja\'); return false;"
            onblur="fadeOut(\'links_ninja\');">';
        echo 'Ninja-Übersicht';
        echo '</a>';

        echo '<div class="untermenulinks" id="links_ninja">';

        echo '<div class="untermenu_bg">';

        echo '<div class="unterpunkt"><a href="/Ninjas.php?RangNinja=Akademist">Akademisten</a></div>';
        echo '<div class="unterpunkt"><a href="/Ninjas.php?RangNinja=Genin">Genin</a></div>';
        echo '<div class="unterpunkt"><a href="/Ninjas.php?RangNinja=Chuunin">Chuunin</a></div>';
        echo '<div class="unterpunkt"><a href="/Ninjas.php?RangNinja=Jounin">Jounin</a></div>';
        echo '<div class="unterpunkt"><a href="/Ninjas.php?RangNinja=Gruppe">Teams</a></div>';
        echo '<div class="unterpunkt"><a href="/Einheiten.php">Einheiten</a></div>';
        echo '<div class="unterpunkt"><a href="/NPC.php">NSC/NPC</a></div>';
        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Ninja

        echo '&nbsp;<br>';

        #Dorf
        $menu_sql = "SELECT * FROM Umfragen WHERE Dran = '1' AND Land = '$dorfs2->Heimatdorf'";
        $menu_query = mysql_query($menu_sql);
        $menu_Umfrage = false;
        while ($menu_row = mysql_fetch_array($menu_query)) {
            $menu_sqls = "SELECT id FROM UmfragenA WHERE User = '$dorfs2->id' AND Umfrage = '$menu_row[id]'";
            $menu_querys = mysql_query($menu_sqls);
            $menu_rows = mysql_fetch_object($menu_querys);
            if ($menu_rows->id <= 0) {
                $menu_Umfrage = true;
            }
        }

        echo '<div class="unterpunkt">';

        echo '<a href="#" tabindex="0" onclick="fadeIn(\'links_dorf\'); return false;" onblur="fadeOut(\'links_dorf\');">';
        echo $dorfs2->Heimatdorf;
        echo '</a>';

        echo '<div class="untermenulinks" id="links_dorf">';

        echo '<div class="untermenu_bg">';
        echo '<div class="unterpunkt"><a href="/Training.php">Training</a></div>';
        echo '<div class="unterpunkt"><a href="/Reisen.php">Reisen</a></div>';
        echo '<div class="unterpunkt';
        if ($menu_Umfrage === true) {
            echo ' highlight';
        }
        $Standort = str_replace("gakure", "", $dorfs2->Heimatdorf);
        echo '"><a href="/Landinfos.php?Einanderesland=' . $Standort . '">Informationen</a></div>';
        echo '<div class="unterpunkt"><a href="/Shop.php">Shop</a></div>';
        echo '<div class="unterpunkt"><a href="/Pruefungen.php">Prüfungen</a></div>';

        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Dorf

        echo '&nbsp;<br>';

        #Missionen
        $menu_sql = "SELECT id FROM NPC WHERE User = '$dorfs2->id'";
        $menu_query = mysql_query($menu_sql);
        $NPC = mysql_fetch_object($menu_query);
        if ($dorfs2->Rangwert > 1 || (is_object($NPC) && $NPC->id > 0)) {
            $menu_sql = "SELECT id FROM Missionen WHERE Spielleiter = '$dorfs2->id' AND Readby NOT LIKE '%|$dorfs2->id|%' AND Lastpost != '$dorfs2->id' AND Abgeschlossen = '0' AND Lastpost > '0'";
            $menu_query = mysql_query($menu_sql);
            $menu_Missis = mysql_fetch_object($menu_query);
            if (!is_object($menu_Missis) || $menu_Missis->id < 1) {
                $menu_sql = "SELECT id FROM Missionen WHERE Ninja LIKE '%|$dorfs2->id|%' AND Readby NOT LIKE '%|$dorfs2->id|%' AND Lastpost != '$dorfs2->id' AND Abgeschlossen = '0' AND Lastpost > '0'";
                $menu_query = mysql_query($menu_sql);
                $menu_Missis = mysql_fetch_object($menu_query);
            }

            echo '<div class="unterpunkt';
            if (is_object($menu_Missis) && $menu_Missis->id > 0) {
                echo ' highlight';
            }
            echo '"><a href="/Missioncenter.php">Missionen';
            if (!hasSeenMissions($dorfs2->id)) {
                echo ' (neu)';
            }
            echo '</a></div>';
        }
        #Missionen

        #Spielleitung
        if ($dorfs->SLVerbot < 1) {
            $Jaaaa = 0;
            $menu_sql2 = "SELECT id FROM Teams WHERE Leiter = '$dorfs->id'";
            $menu_query2 = mysql_query($menu_sql2);
            while ($Gruppen = mysql_fetch_object($menu_query2)) {
                $menu_sql = "SELECT id FROM user WHERE Team = '$Gruppen->id'";
                $menu_query = mysql_query($menu_sql);
                while ($menu_row = mysql_fetch_array($menu_query)) {
                    $menu_sqle = "SELECT id FROM Trainingsanträge WHERE Done = '0' AND User = '{$menu_row['id']}' ORDER BY id DESC";
                    $menu_querye = mysql_query($menu_sqle);
                    $menu_Antrag = mysql_fetch_object($menu_querye);
                    if (is_object($menu_Antrag) && $menu_Antrag->id > 0) {
                        $Jaaaa = 1;
                        break;
                    }
                }
            }
        }

        if ($dorfs2->name != "") {
            $GPs = 0;
            $KRPs = 0;
            $RPGPs = 0;
            $Namensehen = 0;
            if ($dorfs->admin >= 3) {
                $GPs = 1;
                $KRPs = 1;
                $RPGPs = 1;
                $Namensehen = 1;
            } else {
                #if ($dorfs2->Rang != "Akademist" AND $dorfs2->Rang != "Genin"){$GPs = 1;}
                if ($dorfs->admin >= 2) {
                    $KRPs = 1;
                }
                if ($dorfs2->Landoberhaupt >= 1) {
                    $GPs = 1;
                }
                if ($dorfs->Pruferseindarf >= 1) {
                    $GPs = 1;
                    $KRPs = 1;
                    $RPGPs = 1;
                }
            }
        }

        echo '<div class="unterpunkt';
        if ($Jaaaa > 0) {
            echo ' highlight';
        }
        echo '">';

        echo '<a href="#" tabindex="0" onclick="fadeIn(\'links_spielleiter\'); return false;"
            onblur="fadeOut(\'links_spielleiter\');">';
        echo 'Spielleitung';
        echo '</a>';

        echo '<div class="untermenulinks" id="links_spielleiter">';

        echo '<div class="untermenu_bg">';

        if ($dorfs->SLVerbot < 1) {
            echo '<div class="unterpunkt';
            if ($Jaaaa > 0) {
                echo ' highlight';
            }
            echo '"><a href="/Leader.php">SL-Center</a></div>';
        }

        $menu_Landfuhr = "";
        $menu_sql = "SELECT id, Land, Kage FROM Regierung WHERE Kage = '$dorfs2->name'";
        $menu_query = mysql_query($menu_sql);
        $menu_Land = mysql_fetch_object($menu_query);
        if (is_object($menu_Land) && $menu_Land->id > 0) {
            $menu_Landfuhr = $menu_Land->Land;
        } else {
            $menu_sqls = "SELECT NPC FROM NPC WHERE User = '$dorfs->id'";
            $menu_querys = mysql_query($menu_sqls);
            while ($menu_NPC = mysql_fetch_object($menu_querys)) {
                $menu_sql = "SELECT id, Land, Kage FROM Regierung WHERE Kage = '$menu_NPC->NPC'";
                $menu_query = mysql_query($menu_sql);
                $menu_Lande = mysql_fetch_object($menu_query);
                if (is_object($menu_Lande) && $menu_Lande->id > 0) {
                    $menu_Landfuhr = $menu_Lande->Land;
                    $menu_Land = $menu_Lande;
                }
            }
        }
        if ($menu_Landfuhr == "") {
            $menu_sql = "SELECT * FROM Regierung WHERE Helfer1 = '$dorfs->id' OR Helfer2 = '$dorfs->id' OR Helfer3 = '$dorfs->id' OR Helfer4 = '$dorfs->id'";
            $menu_query = mysql_query($menu_sql);
            $menu_Lander = mysql_fetch_object($menu_query);
            if (is_object($menu_Lander) && $menu_Lander->id > 0) {
                $menu_Landfuhr = $menu_Lander->id;
            }
        }
        if ($menu_Landfuhr != "" or $dorfs->admin >= 3) {
            echo '<div class="unterpunkt"><a href="/Landesfuehrercenter.php">LF-Center</a></div>';
        }

        if ($dorfs->admin >= 2 or $dorfs2->Spielleiter == 1) {
            echo '<div class="unterpunkt"><a href="/kc.php">KR-Center</a></div>';
        }
        echo '<div class="unterpunkt"><a href="/KRPruefungen.php">Kampfrichter</a></div>';
        echo '<div class="unterpunkt"><a href="/Kampf.php">Karten & Kämpfe</a></div>';

        if ($GPs == 1 or $KRPs == 1 or $RPGPs == 1 or $dorfs->CoAdmin == 4) {
            if ($GPs == 1) {
                $query = mysql_query("SELECT id FROM Pruefungen_Antworten WHERE Bewertetvon = '' AND Pruefung < '10'");
                $Pruefung_10 = mysql_fetch_object($query);
            }
            if ($KRPs == 1) {
                $query = mysql_query("SELECT id FROM Pruefungen_Antworten WHERE Bewertetvon = '' AND Pruefung = '10'");
                $Pruefung_111 = mysql_fetch_object($query);
            }

            echo '<div class="unterpunkt"';
            if (
                (isset($Pruefung_111) && is_object($Pruefung_111) && $Pruefung_111->id > 0)
                || (isset($Pruefung_10) && is_object($Pruefung_10) && $Pruefung_10->id > 0)
            ) {
                echo ' highlight';
            }
            echo '><a href="/Pruefungen.php?Prufbearb=1">Prüfungen ausw.</a></div>';
        }

        echo '</div>';

        echo '</div>';

        echo '</div>';
        #Spielleitung

        #Administration
        $menu_AdminZugriff = 0;

        if ($dorfs->CoAdmin > 0 or $dorfs->admin >= 3) {
            $menu_AdminZugriff = 1;
            $menu_Zugriff = "";
            if ($dorfs->CoAdmin == 3) {
                $menu_Zugriff = "AND (Zugriffe LIKE '%|RegelCo|%')";
            }
            if ($dorfs->CoAdmin == 4) {
                $menu_Zugriff = "AND (Zugriffe LIKE '%|RPGCo|%')";
            }
            if ($dorfs->CoAdmin == 2) {
                $menu_Zugriff = "AND (Zugriffe LIKE '%|SupportCo|%')";
            }
            $menu_sql = "SELECT id FROM Anfragen WHERE (lastadmin < lastuser AND Zustand = '0' AND Standby = '0' AND Ausschluss NOT LIKE '%|$dorfs->id|%')$menu_Zugriff";
            $menu_query = mysql_query($menu_sql);
            $menu_Anfragen = mysql_fetch_object($menu_query);
        }

        if ($dorfs->admin >= 3 or $dorfs->Logprufer == 1 or $dorfs->CoAdmin >= 3) {
            $menu_AdminZugriff = 1;
            $menu_sql = "SELECT id FROM Missioneneintrag WHERE Fertig = '1'";
            $menu_query = mysql_query($menu_sql);
            $menu_Missioneneintrag = mysql_fetch_object($menu_query);

            $menu_sql = "SELECT IF(COUNT(id) > 0, 1, 0) AS id FROM user WHERE Spezinventarnew != ''";
            $menu_query = mysql_query($menu_sql);
            $menu_Standardausruestung = mysql_fetch_object($menu_query);

            $menu_sql = "SELECT id FROM Logeintrag";
            $menu_query = mysql_query($menu_sql);
            $menu_Logeintrag = mysql_fetch_object($menu_query);
        }

        if ($dorfs->Mod >= 1) {
            $menu_AdminZugriff = 1;
        }

        if ($dorfs->admin >= 3 or $dorfs->CoAdmin >= 3) {
            $menu_AdminZugriff = 1;
            $menu_sql = "SELECT id FROM X_Jutsueintrag WHERE Zustand = '0' AND lastadmin < lastuser";
            $menu_query = mysql_query($menu_sql);
            $menu_Eigenentwicklung = mysql_fetch_object($menu_query);
        }

        if ($menu_AdminZugriff == 1) {
            echo '<div class="unterpunkt';

            $menu_query = mysql_query("SELECT IF(COUNT(id) > 0, 1, 0) AS has_entries FROM Trainingsanträge WHERE Done = '0' AND Adminkontrolle = '0'");
            $menu_Trainingsantrag = mysql_fetch_object($menu_query);

            if (
                $menu_Anfragen->id > 0
                or $menu_Trainingsantrag->has_entries
                or $menu_Missioneneintrag->id > 0
                or $menu_Standardausruestung->id > 0
                or $menu_Logeintrag->id > 0
                or $menu_Eigenentwicklung->id > 0
            ) {
                echo ' highlight';
            }
            echo '" >';

            echo '<a href="#" tabindex="0" onclick="fadeIn(\'links_admin\'); return false;"
            onblur="fadeOut(\'links_admin\');">';
            if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                echo 'Administration';
            } else {
                echo 'Moderation';
            }
            echo '</a>';

            echo '<div class="untermenulinks" id="links_admin">';

            echo '<div class="untermenu_bg">';

            if ($dorfs->CoAdmin > 0 || $dorfs->admin >= 3) {
                echo '<div class="unterpunkt"><a href="/Inhalter/Admin.php">Admincenter</a></div>';
            }

            if ($dorfs->Mod > 0 or $dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                echo '<div class="unterpunkt"><a href="/Charcheck.php">Mod-Center</a></div>';
            }

            if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                echo '<div class="unterpunkt"><a href="/Chatadmin.php">Chatadmin</a></div>';
            }

            if ($dorfs->CoAdmin > 0 or $dorfs->admin >= 3) {
                echo '<div class="unterpunkt';
                if ($menu_Anfragen->id > 0) {
                    echo ' highlight';
                }
                $aemterRow = '';
                $aemter = array(2 => 'SupportCo', 3 => 'RegelCo', 4 => 'RPCo');
                if ($dorfs->admin == 3) {
                    foreach ($aemter as $einsicht) {
                        $aemterRow .= $einsicht . '=1&';
                    }
                } elseif ($dorfs->CoAdmin > 0) {
                    $aemterRow = $aemter[$dorfs->CoAdmin] . '=1&';
                }
                echo '"><a href="/AnfragenListe.php?searchIt=1&' . $aemterRow . 'aktiv=1">Anfragen</a></div>';
            }

            if ($dorfs->admin >= 3 or $dorfs->CoAdmin >= 3) {
                echo '<div class="unterpunkt';
                if ($menu_Trainingsantrag->has_entries) {
                    echo ' highlight';
                }
                echo '"><a href="/Inhalter/Spezialtraining.php">Trainingsanträge</a></div>';
            }
            if ($dorfs->admin >= 3 or $dorfs->Logprufer == 1 or $dorfs->CoAdmin >= 3) {
                echo '<div class="unterpunkt';
                if ($menu_Missioneneintrag->id > 0) {
                    echo ' highlight';
                }
                echo '"><a href="/Missioneintrag.php?schau=1">Missionen</a></div>';

                echo '<div class="unterpunkt';
                if ($menu_Logeintrag->id > 0) {
                    echo ' highlight';
                }
                echo '"><a href="/SLLogeintrag2.php?schau=1">Logs bewerten</a></div>';

                echo '<div class="unterpunkt';
                if ($menu_Standardausruestung->id > 0) {
                    echo ' highlight';
                }
                echo '"><a href="/Itemsein.php">Strd. Ausrüstung</a></div>';
            }
            if ($dorfs->admin >= 3 or $dorfs->CoAdmin >= 3) {
                echo '<div class="unterpunkt';
                if ($menu_Eigenentwicklung->id > 0) {
                    echo ' highlight';
                }
                echo '"><a href="/Spezzieintrag.php">EEs</a></div>';
            }

            echo '</div>';


            echo '</div>';

            echo '</div>';
        }
        #Administration

        echo '&nbsp;<br>';
        echo '&nbsp;<br>';

        #Logout
        echo '<div class="unterpunkt"><a href="/Logout.php?this=' . hash("sha256", $_COOKIE["c_loged"]) . '&thiss=' . hash("sha256", $_COOKIE["c_pw"]) . '">Logout</a>';
        echo '</div>';
        #Logout
    }
    elseif (($dorfs2->id > 0 && $dorfs2->feddig != 1)) {
        echo '<div class="bild">';

        if ($dorfs2->Rang == "Missing-Nin") {
            echo '<div class="bild2">';
            echo '<img border="0" src="/Bilder/Infos/Landbilder/Missing.png">';
            echo '</div>';
        }

        if ($dorfs2->Heimatdorf != "") {
            echo '<img border="0" src="/Bilder/Infos/Landbilder/' . $dorfs2->Heimatdorf . '.bmp">';
        }

        echo '</div>';

        #Startseite
        echo '<div class="unterpunkt"><a href="/index.php">Startseite</a>';
        echo '</div>';
        #Startseite

        #Informationen
        echo '<div class="unterpunkt"><a href="/Infos.php">Informationen</a>';
        echo '</div>';
        #Informationen


        #Usercenter
        echo '<div class="unterpunkt"><a href="/Ninja.php">Charakter erstellen</a>';
        echo '</div>';
        #Usercenter

        echo '&nbsp;<br>';
        echo '&nbsp;<br>';

        #Logout
        echo '<div class="unterpunkt"><a href="/Logout.php?this=' . hash("sha256", $_COOKIE["c_loged"]) . '&thiss=' . hash("sha256", $_COOKIE["c_pw"]) . '">Logout</a>';
        echo '</div>';
        #Logout
    }
    else {
        #Startseite
        echo '<div class="unterpunkt"><a href="/index.php">Startseite</a>';
        echo '</div>';
        #Startseite

        #Informationen
        echo '<div class="unterpunkt"><a href="/Infos.php">Informationen</a>';
        echo '</div>';
        #Informationen

        #Anmeldung
        echo '<div class="unterpunkt"><a href="/Anmeldung2.php">Anmeldung</a>';
        echo '</div>';
        #Anmeldung

        echo '&nbsp;<br>';
        echo '&nbsp;<br>';

        #Login
        echo '<div class="unterpunkt"><a href="/Login.php">Login</a>';
        echo '</div>';
        #Login
    }
    ?>
</div>
