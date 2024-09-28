<?php

include(__DIR__ . "/../Menus/layout1.inc");

$dorfs = nrpg_get_current_user();

if ($dorfs->admin >= 3 || $dorfs->CoAdmin == 4) {
    if ($newNPC == 1) {
        echo "<form method='POST' action='NPC.php?makenewNPC=1";
        if ($NPCBewerbung > 0) {
            echo "&NPCBewerbung=$NPCBewerbung";
        }
        echo "'>
            Name des NPC: <input type='text' name='NPCname'><br>
            Dorf des NPC: <select name='NPCLand'>
            <option>Konohagakure
            <option>Sunagakure
            <option>Kumogakure
            <option>Iwagakure
            <option>Takigakure
            <option>Kusagakure
            <option>Amegakure
            </select><br>
            Spieler des NPC (ganzer Name): <input type='text' name='NPCSpieler'";
        if ($NPCBewerbung > 0) {
            $sql = "SELECT Von FROM Test WHERE id = '$NPCBewerbung' AND Test = 'NPC-Bewerbung'";
            $query = mysql_query($sql);
            $Bewerbung = mysql_fetch_object($query);
            $abfrage2 = "SELECT name FROM user WHERE id LIKE '$Bewerbung->Von'";
            $ergebnis2 = mysql_query($abfrage2);
            $User = mysql_fetch_object($ergebnis2);
            echo " value='$User->name'";
        }
        echo " id=\"theText\" autocomplete=off onkeyup=\"editForm(this.value)\">
        <div id=\"livesearch\"></div><br>
        <input type='submit' value='Eintragen'></form>";
    }

    if ($makenewNPC) {
        $sql = "SELECT id FROM user WHERE name = '$NPCSpieler'";
        $query = mysql_query($sql);
        $usernpc = mysql_fetch_object($query);

        if ($usernpc->id < 1) {
            echo "Es gibt den genannten Spieler nicht!<br><br>";
        } else {
            $sql = "SELECT id FROM NPC WHERE NPC = '$NPCname'";
            $query = mysql_query($sql);
            $usernpc2 = mysql_fetch_object($query);

            if ($usernpc2->id >= 1) {
                echo "Es gibt bereits einen NPC mit diesem Namen!<br><br>";
            } else {
                $c_IP = $_SERVER['REMOTE_ADDR'];
                $lol = gethostbyaddr($c_IP);
                $rofl = $_SERVER["HTTP_USER_AGENT"];

                $Date = date("d.m.Y, H:i");
                $ins = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('NPC erstellt in $NPCLand, Spieler: $NPCSpieler', '$c_loged', '$Date', 'NPC', '$c_IP : $lol : $rofl')";
                $ins = mysql_query($ins);

                if ($NPCBewerbung > 0) {
                    $sql = "SELECT id, Von, Kommentar FROM Test WHERE id = '$NPCBewerbung' AND Test = 'NPC-Bewerbung'";
                    $query = mysql_query($sql);
                    $Vorschlager = mysql_fetch_object($query);
                    $Meinungadminsa = str_replace("'", "\"", $Meinungadminsa);
                    $Meinungadminsa = htmlentities($Meinungadminsa);
                    $lol = 0;
                    $Textbekommen = "Deine NPC-Bewerbung wurde bearbeitet. Die bearbeitenden Admins haben folgende Kritiken abgegeben:<br>";
                    $Reihe = explode("&&&&&&&&&", $Vorschlager->Kommentar);
                    $Zahl = 1;
                    while ($Reihe[$Zahl] != "") {
                        $Reihe2 = explode("%%%%%%%%", $Reihe[$Zahl]);
                        $sql = "SELECT name FROM user WHERE id = '$Reihe2[0]'";
                        $query = mysql_query($sql);
                        $Name = mysql_fetch_object($query);
                        $Textbekommen = $Textbekommen . "<b>$Name->name:</b><br>$Reihe2[1]";
                        $Zahl += 1;
                    }

                    $Date = date("d.m.Y, H:i");

                    $eintrag2 = "INSERT INTO Posteingang (Von, An, Text, Betreff, Datum, Gelesen) VALUES ('System', '$Vorschlager->Von', '$Textbekommen', 'NPC-Bewerbung angenommen', '$Date', '0')";
                    mysql_query($eintrag2) or die("Senden fehlgeschlagen!");
                    $messagesend = 1;

                    $up = "UPDATE Test SET Pkt = '1' WHERE id = '$NPCBewerbung' AND Test = 'NPC-Bewerbung'";
                    mysql_query($up);
                    $up = "UPDATE Test SET Test = 'NPC-Bewerbung-Archiv' WHERE id = '$NPCBewerbung' AND Test = 'NPC-Bewerbung'";
                    mysql_query($up);
                }

                $ins = "INSERT INTO NPC (User, NPC, Land, Zugelassen) VALUES ('$usernpc->id', '$NPCname', '$NPCLand', '1')";
                mysql_query($ins);
                $up = "UPDATE user SET Spielleiter = '1',NPC = '1' WHERE id = '$usernpc->id'";
                mysql_query($up);
                echo "Der NPC wurde erfolgreich eingetragen!<br><br>";
            }
        }
    }
}

if ($del) {
    $sql = "SELECT * FROM NPC WHERE id = '$del'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    echo "$NPC->NPC wirklich löschen?<br><a href='NPC.php?dele=$del'>Ja löschen!</a>";
}

if ($dele and $dorfs->admin >= 3) {
    $sql = "SELECT * FROM NPC WHERE id = '$dele'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    $del = "DELETE FROM NPC WHERE id = '$dele'";
    $del = mysql_query($del);
    $c_IP = $_SERVER['REMOTE_ADDR'];
    $lol = gethostbyaddr($c_IP);
    $rofl = $_SERVER["HTTP_USER_AGENT"];

    $Date = date("d.m.Y, H:i");
    $ins = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('NPC gelöscht: $NPC->NPC (Spielerid: $NPC->User)', '$c_loged', '$Date', 'NPC', '$c_IP : $lol : $rofl')";
    $ins = mysql_query($ins);
    echo "$NPC->NPC wurde gelöscht!";
}

if ($dele and $dorfs->CoAdmin >= 1) {
    $sql = "SELECT * FROM NPC WHERE id = '$dele'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    $del = "DELETE FROM NPC WHERE id = '$dele'";
    $del = mysql_query($del);

    $c_IP = $_SERVER['REMOTE_ADDR'];
    $lol = gethostbyaddr($c_IP);
    $rofl = $_SERVER["HTTP_USER_AGENT"];

    $Date = date("d.m.Y, H:i");
    $ins = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('NPC gelöscht: $NPC->NPC (Spielerid: $NPC->User)', '$c_loged', '$Date', 'NPC', '$c_IP : $lol : $rofl')";
    $ins = mysql_query($ins);

    echo "$NPC->NPC wurde gelöscht!";
}

if ($dele and $dorfs->CoAdmin >= 4) {
    $sql = "SELECT * FROM NPC WHERE id = '$dele'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    $del = "DELETE FROM NPC WHERE id = '$dele'";
    $del = mysql_query($del);

    $c_IP = $_SERVER['REMOTE_ADDR'];
    $lol = gethostbyaddr($c_IP);
    $rofl = $_SERVER["HTTP_USER_AGENT"];

    $Date = date("d.m.Y, H:i");
    $ins = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('NPC gelöscht: $NPC->NPC (Spielerid: $NPC->User)', '$c_loged', '$Date', 'NPC', '$c_IP : $lol : $rofl')";
    $ins = mysql_query($ins);

    echo "$NPC->NPC wurde gelösch!";
}

if ($ed) {
    $sql = "SELECT * FROM NPC WHERE id = '$ed'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    if ($c_loged == $NPC->User or $dorfs->admin >= 3 or $dorfs->CoAdmin == 4) {
        echo "<form method='POST' action='NPC.php?edit=$ed'>
            <table border='0' width='70%'>
            <tr>
            <td width='25%'><b>Name:</b></td><td><input type='text' name='NameNPC' value='$NPC->NPC'></td>
            </tr>
            <tr>
            <td width='25%'><b>Bild:</b></td><td><input type='text' name='BildNPC' value='$NPC->Bild'></td>
            </tr>
            <tr>
            <td width='25%'><b>Passbild:</b></td><td><input type='text' name='BildNPCPass' value='$NPC->Passfoto'></td>
            </tr>
            <tr>
            <td colspan='2'><b>Charakterbeschreibung:</b></td>
            </tr>
            <tr>
            <td colspan='2'><textarea rows='10' cols='80' name='BeschrNPC'>$NPC->Story</textarea></td>
            </tr>
            <tr>
            <td colspan='2'><b>Steckbrief (HTML ist aktiv):</b></td>
            </tr>
            <tr>
            <td colspan='2'><textarea rows='10' cols='80' name='SteckbriefNPC'>$NPC->Steckbrief</textarea></td>
            </tr>
            </table>
            <br>
            <b>Info zum Steckbrief:</b> Hier nur HTML-Grundlagen wie Tabellen, Fettschreibung, Kursivschreibung, Unterstriche und Co verwenden! Andere Anwendungen sind nicht erwünscht!<br>
            <input type='submit' value='Editieren'></form>";
    }
}

if ($edit) {
    $NameNPC = htmlentities($NameNPC);
    $BildNPC = htmlentities($BildNPC);
    $BildNPCPass = htmlentities($BildNPCPass);
    $BeschrNPC = htmlentities($BeschrNPC);

    $sql = "SELECT * FROM NPC WHERE id = '$edit'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);
    if ($c_loged == $NPC->User or $dorfs->admin >= 3 or $dorfs->CoAdmin == 4) {
        $up = "UPDATE NPC SET NPC = '$NameNPC' WHERE id = '$edit'";
        mysql_query($up);
        $up = "UPDATE NPC SET Story = '$BeschrNPC' WHERE id = '$edit'";
        mysql_query($up);
        $SteckbriefNPC = str_replace("'", "\"", $SteckbriefNPC);
        $SteckbriefNPC = str_replace("<script", "", $SteckbriefNPC);
        $SteckbriefNPC = str_replace("<div", "", $SteckbriefNPC);
        $SteckbriefNPC = str_replace("<iframe", "", $SteckbriefNPC);
        $SteckbriefNPC = str_replace("<form", "", $SteckbriefNPC);
        $BildNPC = htmlentities($BildNPC);
        $BildNPCPass = htmlentities($BildNPCPass);
        $up = "UPDATE NPC SET Steckbrief = '$SteckbriefNPC' WHERE id = '$edit'";
        mysql_query($up);
        $up = "UPDATE NPC SET Bild = '$BildNPC' WHERE id = '$edit'";
        mysql_query($up);
        $up = "UPDATE NPC SET Passfoto = '$BildNPCPass' WHERE id = '$edit'";
        mysql_query($up);
        echo "$NPC->NPC wurde erfolgreich editiert!";
    }
}

if ($id) {
    $sql = "SELECT * FROM NPC WHERE id = '$id'";
    $query = mysql_query($sql);
    $NPC = mysql_fetch_object($query);

    $sql = "SELECT * FROM user WHERE id = '$NPC->User'";
    $query = mysql_query($sql);
    $User = mysql_fetch_object($query);
    $sql = "SELECT * FROM userdaten WHERE id = '$NPC->User'";
    $query = mysql_query($sql);
    $ownerdaten = mysql_fetch_object($query);
    ?>
    <script>
        function showU1() {
            UserUbersicht.style.display = 'block';
            UserBeschreibung.style.display = 'none';
        }

        function showU2() {
            UserUbersicht.style.display = 'none';
            UserBeschreibung.style.display = 'block';
        }
    </script>
    <?php
    echo "<table border='0' width='90%'>
        <tr>
        <td width='50%' background='/layouts/Uebergang/Oben.png' align='center'><a href=\"javascript:showU1();\">Übersicht</a></td>
        <td width='50%' background='/layouts/Uebergang/Oben.png' align='center'><a href=\"javascript:showU2();\">Charakterbeschreibung</a></td>
        </tr>";
    echo "
        <tr>
        <td colspan='2' background='/layouts/Uebergang/Untergrund.png'>
        ";


    echo "<div id='UserUbersicht' style='display:block'>";
    echo "
        <table border='0' width='100%' id='table2'>

        <tr>
        <td width='50%' align='center'valign='top'>
        ";


    $dorf = $NPC->Land;
    if ($dorf == "Konohagakure") {
        echo "<img border='0' src='Bilder/Konoha$Zusatzrang.bmp'>";
    } elseif ($dorf == "Sunagakure") {
        echo "<img border='0' src='Bilder/Suna$Zusatzrang.bmp'>";
    } elseif ($dorf == "Kumogakure") {
        echo "<img border='0' src='Bilder/Kumo$Zusatzrang.bmp'>";
    } elseif ($dorf == "Iwagakure") {
        echo "<img border='0' src='Bilder/Iwa$Zusatzrang.bmp'>";
    } elseif ($dorf == "Amegakure") {
        echo "<img border='0' src='Bilder/Ame$Zusatzrang.bmp'>";
    } elseif ($dorf == "Takigakure") {
        echo "<img border='0' src='Bilder/Taki$Zusatzrang.bmp'>";
    } elseif ($dorf == "Kusagakure") {
        echo "<img border='0' src='Bilder/Kusa$Zusatzrang.bmp'>";
    }

    echo "
        <br><br>
        <table border='0' width='100%'>
        <tr>
        <td colspan='2' align='center'>";
    echo "<b>$NPC->NPC</b>";
    if ($c_loged == $NPC->User or $dorfs->admin >= 3 or $dorfs->CoAdmin == 4) {
        echo "<br><a href='NPC.php?ed=$NPC->id'>Editieren</a>";
    }
    if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4) {
        echo " - <a href='NPC.php?del=$NPC->id'>Löschen</a>";
    }

    echo "<br><br></td></tr>";

    echo "<tr><td><b>Spieler:</b></td><td>";

    $sql = "SELECT id, name FROM user WHERE id = '$NPC->User'";
    $query = mysql_query($sql);
    $User = mysql_fetch_object($query);
    echo "<a href='userpopup.php?usernam=$User->name'>$User->name</a>";

    echo "</td></tr>";

    echo "<tr><td><b>Einheiten:</b></td><td>";
    $No = 0;
    $NPC->NPC = str_replace("'", "\'", $NPC->NPC);
    $sql = "SELECT id, Name FROM Einheiten WHERE Mitglieder LIKE '%|NPC:$NPC->NPC|%'";
    $query = mysql_query($sql);
    while ($Einheit = mysql_fetch_object($query)) {
        if ($No == 1) {
            echo ", ";
        }
        $No = 1;
        echo "<a href='Einheiten.php?Einheitid=$Einheit->id'>$Einheit->Name</a>";
    }
    echo "</td></tr>";

    echo "</table><br><b>Steckbrief</b><br>
        <table border='0' width='100%'><tr><td>";

    echo "$NPC->Steckbrief";

    echo "</td></tr></table>";

    echo "<br>
        &nbsp;</td>
        <td width='50%' align='center' valign='top'>";

    echo "<br><b>$NPC->Land</b><br>";

    echo "
        <br>
        ";

    if ($NPC->Passfoto != "") {
        echo "<img border='0' src='$NPC->Passfoto'>";
    } else {
        echo "<img border='0' src='/img/user/no_image.svg' width='200' height='200'>";
    }

    echo "<br><br>";

    echo "&nbsp;</td></tr>";

    echo "<tr><td align='center' valign='top' colspan='2'>";

    echo "<b>Geleitete Gruppen</b><br><br>";

    $LandNinja = str_replace("gakure", "", $NPC->Land);

    $sqlGR = "SELECT * FROM Teams WHERE Leiter = '$NPC->User' AND Land = '$LandNinja'";
    $queryGR = mysql_query($sqlGR);
    while ($Gruppens = mysql_fetch_object($queryGR)) {
        echo "
            <b><a href='userpopup.php?Gruppezeigen=$Gruppens->Name'>$Gruppens->Name</a></b>
            <table border='0' width='100%' id='table3'>
            <tr>
            <td align='center' width='33%'>";
        $sql = "SELECT id, name, Passfoto FROM user WHERE Team = '$Gruppens->id' AND id != '$row->id' LIMIT 1";
        $query = mysql_query($sql);
        $Teammate = mysql_fetch_object($query);
        if ($Teammate->id > 0) {
            echo "<a href='userpopup.php?usernam=$Teammate->name'>";

            if ($Teammate->Passfoto != "") {
                echo "<img src='$Teammate->Passfoto' name='Passfoto' border='0' width='125' height='125'><br>";
            } else {
                echo "<img src='/img/user/no_image.svg' name='Passfoto' border='0'><br>";
            }
            echo "$Teammate->name</a>";
        }

        echo "</td><td align='center' width='33%'>";
        $sql = "SELECT id, name, Passfoto FROM user WHERE Team = '$Gruppens->id' AND id != '$row->id' LIMIT 1,1";
        $query = mysql_query($sql);
        $Teammate = mysql_fetch_object($query);
        if ($Teammate->id > 0) {
            echo "<a href='userpopup.php?usernam=$Teammate->name'>";

            if ($Teammate->Passfoto != "") {
                echo "<img src='$Teammate->Passfoto' border='0' name='Passfoto' width='125' height='125'><br>";
            } else {
                echo "<img src='/img/user/no_image.svg' name='Passfoto' border='0'><br>";
            }
            echo "$Teammate->name</a>";
        }

        echo "</td><td align='center' width='33%'>";

        $sql = "SELECT id, name, Passfoto FROM user WHERE Team = '$Gruppens->id' AND id != '$row->id' LIMIT 2,1";
        $query = mysql_query($sql);
        $Teammate = mysql_fetch_object($query);
        if ($Teammate->id > 0) {
            echo "<a href='userpopup.php?usernam=$Teammate->name'>";

            if ($Teammate->Passfoto != "") {
                echo "<img src='$Teammate->Passfoto' border='0' name='Passfoto' name='Passfoto' width='125' height='125'><br>";
            } else {
                echo "<img src='/img/user/no_image.svg' name='Passfoto' name='Passfoto' border='0'><br>";
            }
            echo "$Teammate->name</a>";
        }
        echo "</td></tr></table><br>";
    }
    echo "</td></tr>";
    echo "</table><br>";
    echo "</div>";
    echo "<div id='UserBeschreibung' style='display:none'>";

    $bild = $NPC->Bild;

    echo "<center><img src='$bild' name='Charpic'></center>";

    echo "<br>
        <b>Beschreibung von $NPC->NPC:</b><br><br>";
    $Beschr = $NPC->Story;

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

    $Beschr = nl2br($Beschr);
    echo "$Beschr";

    echo "<br><br></div>";

    echo "</td></tr></table>";
} else {
    ?>
    <b><u>Nicht-Spieler Charaktere</u></b>
    <br>(NSC/NPC)<br><br>
    <?php
    echo "<table width='80%'  border='0'>";
    for ($Zahl = 1; $Zahl <= 7; ++$Zahl) {
        if ($Zahl == 1 or $Zahl == 4 or $Zahl == 7) {
            echo "<tr>";
        }
        echo "<td width='33%' valign='top'><ul>\n";
        if ($Zahl == 1) {
            $Land = "Konoha";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Konoha.bmp' border='0'>";
            $Anfuehrer = "Hokage";
        } elseif ($Zahl == 2) {
            $Land = "Suna";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Suna.bmp' border='0'>";
            $Anfuehrer = "Kazekage";
        } elseif ($Zahl == 3) {
            $Land = "Kumo";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Kumo.bmp' border='0'>";
            $Anfuehrer = "Raikage";
        } elseif ($Zahl == 4) {
            $Land = "Iwa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Iwa.bmp' border='0'>";
            $Anfuehrer = "Tsuchikage";
        } elseif ($Zahl == 5) {
            $Land = "Ame";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Ame.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 6) {
            $Land = "Taki";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Taki.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        } elseif ($Zahl == 7) {
            $Land = "Kusa";
            echo "<a href=\"javascript:show('$Land');\"><img src='Bilder/Infos/Landbilder/Kusa.bmp' border='0'>";
            $Anfuehrer = "Dorfoberhaupt";
        }
        $sql = "SELECT Kage FROM Regierung WHERE Land = '$Land'";
        $query = mysql_query($sql);
        $Regierung = mysql_fetch_object($query);

        echo "</a>";
        echo "<div id='$Land'>";

        $Land2 = "$Land" . 'gakure';
        $sql = "SELECT * FROM NPC WHERE Land = '$Land' OR Land = '$Land2' ORDER BY NPC";
        $result2 = mysql_query($sql) or die("Invalid query");
        while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            echo "<li>\n";
            echo "<A href='NPC.php?id=" . $row["id"] . "'>";
            echo htmlentities($row["NPC"]);
            echo "</a>\n";
            if ($Regierung->Kage == $row['NPC']) {
                echo " (<b>$Anfuehrer</b>)";
            }
            echo "</li>";
        }
        echo "</ul></div></td>";

        if ($Zahl == 3 or $Zahl == 6 or $Zahl == 9) {
            echo "</tr>";
        }
    }
    echo "</table>";

    echo "<br><br>";

    $sql = "SELECT COUNT(*) FROM Test WHERE Test = 'NPC-Bewerbung' || Test = 'SL-Bewerbung'";
    $query = mysql_query($sql);
    $Zahl = mysql_fetch_row($query);

    echo "<b>Derzeitige NPC/SL-Bewerbungen:</b> $Zahl[0]<br>";
    echo "<a href='NPCBewerbung.php'>Als NPC bewerben</a><br>";

    $sql = "SELECT * FROM Test WHERE Test = 'NPC-Bewerbung' AND Von = '$dorfs->id' ORDER BY id DESC";
    $query = mysql_query($sql);
    $Test = mysql_fetch_object($query);
    if ($Test->id > 0) {
        echo "<b>Deine derzeitige NPC-Bewerbung</b><br>";
        if ($Test->Anzeige == 0 and $Test->Kommentar == "") {
            echo "Unbearbeitet";
        }
        if ($Test->Anzeige == 0 and $Test->Kommentar != "") {
            echo "In Bearbeitung";
        }
        if ($Test->Anzeige == 1) {
            echo "Du erhälst voraussichtlich ein Team";
        }
        if ($Test->Anzeige == 2) {
            echo "Du erhälst voraussichtlich <u>kein</u> Team";
        }
        if ($Test->Anzeige == 3) {
            echo "Du wirst voraussichtlich zum Test als freier NPC eingetragen";
        }
        if ($Test->Anzeige == 4) {
            echo "Voraussichtlicher Einsatz bei der nächsten Geninprüfung";
        }
        if ($Test->Anzeige == 5) {
            echo "Noch unschlüssig";
        }
        echo "<br><br>";
    }

    $sql = "SELECT * FROM Test WHERE Test = 'SL-Bewerbung' AND Von = '$dorfs->id' ORDER BY id DESC";
    $query = mysql_query($sql);
    $Test = mysql_fetch_object($query);
    if ($Test->id > 0) {
        echo "<b>Deine derzeitige SL-Bewerbung</b><br>";
        if ($Test->Anzeige == 0 and $Test->Kommentar == "") {
            echo "Unbearbeitet";
        }
        if ($Test->Anzeige == 0 and $Test->Kommentar != "") {
            echo "In Bearbeitung";
        }
        if ($Test->Anzeige == 5) {
            echo "Noch unschlüssig";
        }
        echo "<br><br>";
    }

    if ($dorfs->admin >= 3 or $dorfs->CoAdmin == 4) {
        echo "<a href='NPC.php?newNPC=1'>NPC erstellen</a>";
    }
}

get_footer();
