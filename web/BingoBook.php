<?php

include __DIR__ . "/../Menus/layout1.inc";
include __DIR__ . "/../layouts/Overview/OverviewLand.php";
include __DIR__ . "/../layouts/Overview/OverviewLand3.php";

echo "<tr><td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='6'><br>";
echo "<b><u>Bingo Book</u></b><br><br>";

if ($dorfs2->Rang != "Akademist" || $dorfs2->NPC > 0) {
    if ($dorfs->admin == 3 || $dorfs->CoAdmin == 4) {
        echo '<a href=?bearb=0>Neuen Eintrag erstellen</a><br>';
    }

    if (isset($bearb) && $bearb >= 0 && ($dorfs->admin == 3 || $dorfs->CoAdmin == 4)) {
        $sql = "SELECT * FROM BingoBook WHERE id = '$bearb'";
        $query = mysql_query($sql);
        $Bingo = mysql_fetch_object($query);
        echo "<form method='POST' enctype='multipart/form-data' name='form1' action='?Bingoeden=$bearb'>
                <table border='0'>

                <tr>
                <td>Ninja:</td>
                <td><input type='text' name='NinjaBingo' value='$Bingo->Ninja'> (leer lassen zum Löschen)</td>
                </tr>

                <tr>
                <td>Mit Spieler verknüpfen:</td>
                <td><input type='text' name='SpielerBingo' value='";

        $sql = "SELECT id, name FROM user WHERE id = '$Bingo->Spieler'";
        $query = mysql_query($sql);
        $user = mysql_fetch_object($query);
        if ($user->id > 0) {
            $Bingo->Spieler = $user->name;
        } else {
            $Bingo->Spieler = "";
        }

        echo "$Bingo->Spieler'> (übernimmt Gefahrenpotential und Bilder)</td>
            </tr>
            <tr>
            <td>
            Gefahrenpotential:</td><td><input type='text' name='PotentialBingo' value='$Bingo->Gefahrenpotential'></td></tr>
            <tr>
            <tr>
            <td>
            Ehemaliger Rang:</td><td><input type='text' name='RangBingo' value='$Bingo->Rang'></td></tr>
            <tr>
            <tr>
            <td>
            Ehemaliges Dorf:</td><td><input type='text' name='DorfBingo' value='$Bingo->Heimatdorf'></td></tr>
            <tr>
            <td>Art des Eintrags:</td>
            <td><select name='GesuchtBingo'>
            <option value='1'";
        if ($Bingo->Gesucht == 1) {
            echo " selected";
        }
        echo ">Gesucht
            <option value='0'";
        if ($Bingo->Gesucht == 0) {
            echo " selected";
        }
        echo ">Vermerkt
            </select></td>
            </tr>

            <tr>
            <td>Prämie:</td>
            <td><input type='text' name='PraemieBingo' value='$Bingo->Praemie' size='6'> Ryô (falls gesucht)</td>
            </tr>

            <tr>
            <tr>
            <td>
            Bild:</td><td><input type='text' name='BildBingo' value='$Bingo->Bild'></td></tr>
            <tr>
            <td>
            Vermerkt/Gesucht in:</td><td><input type='text' name='LanderBingo' value='$Bingo->Land'> (Form z.B.: Oto, Konoha, Taki, Suna)</td></tr>
            <tr>
            <td colspan='2'>
            Anmerkungen:<br>
            <textarea name='SonstigesBingo' cols='50' rows='5'>$Bingo->Text</textarea></td>
            </tr>
            <tr>
            <td colspan='2'>
            Spezialisierung:<br>
            <textarea name='SpezialisierungBingo' cols='50' rows='5'>$Bingo->Spezialisierung</textarea></td>
            </tr>
            </table>
            <input type='submit' value='Bearbeiten'></form>";
    }

    if (isset($Bingoeden) && $Bingoeden >= 0 && ($dorfs->admin == 3 || $dorfs->CoAdmin == 4)) {
        if ($NinjaBingo == '') {
            $del = "DELETE FROM BingoBook WHERE `id` = '$Bingoeden';";
            $text = 'Löschung';
            mysql_query($del) or die("Löschung gescheitert");
        } else {
            $SonstigesBingo = str_replace("'", "\"", $SonstigesBingo);
            $DorfBingo = str_replace("'", "\"", $DorfBingo);
            $RangBingo = str_replace("'", "\"", $RangBingo);
            $PotentialBingo = str_replace("'", "\"", $PotentialBingo);
            $LanderBingo    = str_replace("'", "\"", $LanderBingo);
            $SonstigesBingo = str_replace("'", "\"", $SonstigesBingo);
            $BildBingo      = str_replace("'", "\"", $BildBingo);
            $NinjaBingo     = str_replace("'", "\"", $NinjaBingo);
            $sql   = "SELECT id, name FROM user
            WHERE name = '$SpielerBingo'";
            $query = mysql_query($sql);
            $user  = mysql_fetch_object($query);
            if ($user->id > 0 AND $user->name != "") {
                $SpielerBingo = $user->id;
            } else {
                $SpielerBingo = 0;
            }

            if ($GesuchtBingo != 1) {
                $GesuchtBingo = 0;
            }

            if ($PraemieBingo < 0) {
                $PraemieBingo = 0;
            }

                    $SpezialisierungBingo = str_replace("'", "\"", $SpezialisierungBingo);
                    $sql   = "SELECT * FROm BingoBook WHERE id = '$Bingoeden'";
                    $query = mysql_query($sql);
                    $Bingo = mysql_fetch_object($query);
                    if(mysql_num_rows($query) == 0)
                    {
                        $ins = "INSERT INTO BingoBook (Ninja, Bild,
                        Gefahrenpotential, Text, Land, Spieler, Gesucht,
                        Praemie, Spezialisierung,Rang,Heimatdorf)
                        VALUES ('$NinjaBingo', '$BildBingo', '$PotentialBingo',
                        '$SonstigesBingo', '$LanderBingo', '$SpielerBingo',
                        '$GesuchtBingo', '$PraemieBingo',
                        '$SpezialisierungBingo','$RangBingo','$DorfBingo')";
                        $text = 'Erstellung';
                        mysql_query($ins) or DIE("Erstellung gescheitert");
                    }
                    else
                    {
                        $ins = "UPDATE BingoBook SET `Ninja` = '$NinjaBingo',
                            `Bild` = '$BildBingo',
                            `Gefahrenpotential`='$PotentialBingo',
                            `Text` = '$SonstigesBingo', `Land` = '$LanderBingo',
                            `Spieler` = '$SpielerBingo',
                            `Gesucht` = '$GesuchtBingo',
                            `Praemie` = '$PraemieBingo',
                            `Spezialisierung` = '$SpezialisierungBingo',
                            `Rang` = '$RangBingo',
                            `Heimatdorf` = '$DorfBingo'
                            WHERE `id` = '$Bingoeden';";
                        $text = 'Bearbeitung';
                        mysql_query($ins) or DIE("Bearbeitung gescheitert");
                    }
            }
            $date = time();
            $c_IP = $_SERVER['REMOTE_ADDR'];
            $lol  = gethostbyaddr($c_IP);
            $rofl = $_SERVER["HTTP_USER_AGENT"];
            $ins = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('$text des Bingo Book Eintrags mit der ID $Bingoeden',
            '$dorfs->id', '$date', 'Bingo', '$c_IP : $lol : $rofl')";
            mysql_query($ins);
            echo $text.' des Bingo Book Eintrags erfolgreich.';
        }

    $Eintrag = (int)filter_input(INPUT_GET, 'Eintrag', FILTER_SANITIZE_NUMBER_INT);
    if ($Eintrag > 0)
    {
        $S_sql = "SELECT * FROM BingoBook WHERE id = '$Eintrag'";
        $S_query = mysql_query($S_sql);
        $BingoBook = mysql_fetch_object($S_query);

        $Zugang = 0;

        if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
            $Zugang = 1;
        }
        if (strpos($BingoBook->Land, $dorfs2->Heimatdorf) === false) {
            $sql = "SELECT Land FROM NPC WHERE User = '$dorfs->id'";
            $query = mysql_query($sql);
            while ($NPC = mysql_fetch_object($query)) {
                $Land = str_replace("gakure", "", $NPC->Land);
                $pos = strpos($BingoBook->Land, $Land);
                if ($pos !== false) {
                    $Zugang = 1;
                }
            }
        } else {
            $Zugang = 1;
        }

        if ($Zugang == 1 AND $BingoBook->id > 0)
        {

            echo "<table border='0' width='100%'>
                <tr>
                <td colspan='2'><b><u>$BingoBook->Ninja</u></b>";
            if ($BingoBook->Gesucht == 1){echo " - <font color='#cc0033'><u>GESUCHT</u></font>";}
            if($dorfs->admin == 3 || $dorfs->CoAdmin == 4)
            {
                    echo ' - <a href="?bearb='.$BingoBook->id.'">Bearbeiten</a>';
            }
            echo " - <a href='?'>Zurück</a><br><br></td>
                </tr>";

            if ($BingoBook->Spieler > 0)
            {
                $sql = "SELECT id, name, Passfoto FROM user WHERE id = '$BingoBook->Spieler'";
                $query = mysql_query($sql);
                $Ninja = mysql_fetch_object($query);
                $sql = "SELECT pic FROM userdaten WHERE id = '$BingoBook->Spieler'";
                $query = mysql_query($sql);
                $Ninja1 = mysql_fetch_object($query);
                echo "<tr>
                    <td width='70%' valign='top' align='center'>"; if ($Ninja1->pic != ""){echo "<img src='$Ninja1->pic' name='Charpic'>";} echo "</td>
                    <td width='30%' valign='top' align='center'>"; if ($Ninja->Passfoto != ""){echo "<img src='$Ninja->Passfoto' name='Passfoto'>";} echo "</td>
                    </tr>";
            }
            elseif ($BingoBook->NPC > 0)
            {
                $sql = "SELECT id, NPC, Bild FROM user WHERE id = '$BingoBook->NPC'";
                $query = mysql_query($sql);
                $Ninja = mysql_fetch_object($query);
                echo "<tr>
                    <td width='70%' valign='top' align='center'>"; if ($Ninja->Bild != ""){echo "<img src='$Ninja->Bild' name='Charpic'>";} echo "</td>
                    <td width='30%' valign='top' align='center'>"; if ($Ninja->Passfoto != ""){echo "<img src='$Ninja->Passfoto' name='Passfoto'>";} echo "</td>
                    </tr>";
            }
            else
            {
                echo "<tr>
                    <td width='70%' valign='top' align='center'>"; if ($BingoBook->Bild != ""){echo "<img src='$BingoBook->Bild' name='Charpic'>";} echo "</td>
                    <td width='30%' valign='top' align='center'></td>
                    </tr>";
            }

            echo "<tr>
                <td colspan='2'>";

            echo "<table border='0' width='100%'>";

            echo "<tr>
                <td width='35%' valign='top'>";

            if ($BingoBook->Gesucht == 1) {
                echo "<table border='0'>";

                if ($BingoBook->Name != "") {
                    echo "<tr>
                        <td><b>Bekannte Namen:</b></td>
                        <td>$BingoBook->Name</td>
                        </tr>";
                }

                if ($BingoBook->Spieler > 0) {
                    $sql = "SELECT id, Gefahrenpotential FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);
                    if ($Ninja->Gefahrenpotential == 0) {
                        $Gefahrenpotential = "E-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 1) {
                        $Gefahrenpotential = "D-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 2) {
                        $Gefahrenpotential = "C-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 3) {
                        $Gefahrenpotential = "B-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 4) {
                        $Gefahrenpotential = "A-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 5) {
                        $Gefahrenpotential = "S-Rang";
                    }
                } else {
                    $Gefahrenpotential = "$BingoBook->Gefahrenpotential";
                }
                echo "<tr>
                    <td><b>Gefahrenpotential:</b></td>
                    <td>$Gefahrenpotential</td>
                    </tr>";
                if ($BingoBook->Spieler > 0) {
                    $sql = "SELECT Angehoer FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);
                    $Gefahrenpotential = $Ninja->Angehoer;
                } else {
                    $Gefahrenpotential = $BingoBook->Heimatdorf;
                }
                echo "<tr>
                    <td><b>Heimatdorf:</b></td>
                    <td>$Gefahrenpotential</td>
                    </tr>";
                echo "<tr>
                    <td><b>Ehemaliger Rang:</b></td>
                    <td>$BingoBook->Rang</td>
                    </tr>";
                echo "<tr>
                    <td><b>Prämie:</b></td>
                    <td>$BingoBook->Praemie Ryô</td>
                    </tr>";
                echo "<tr>
                    <td><b>Gesucht in:</b></td>
                    <td><table border='0'>";
                $Zahls = 0;

                if ($dorfs2->Heimatdorf == "Konoha"){$pos = stripos($BingoBook->Land, "Konoha"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohanew.gif' width='25' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Konoha"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohaold.gif' width='25' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Suna"){$pos = stripos($BingoBook->Land, "Suna"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunanew.gif' width='15' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Suna"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunaold.gif' width='15' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Kumo"){$pos = stripos($BingoBook->Land, "Kumo"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumonew.gif' width='25' height='10'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Kumo"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumoold.gif' width='25' height='10'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Iwa"){$pos = stripos($BingoBook->Land, "Iwa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwanew.gif' width='25' height='23'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Iwa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwaold.gif' width='25' height='23'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Ame"){$pos = stripos($BingoBook->Land, "Ame"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Amenew.gif' width='25' height='12'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Ame"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Ameold.gif' width='25' height='12'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Taki"){$pos = stripos($BingoBook->Land, "Taki"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takinew.gif' width='24' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Taki"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takiold.gif' width='24' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Kusa"){$pos = stripos($BingoBook->Land, "Kusa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaneu.gif' width='25' height='14'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Kusa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaold.gif' width='25' height='14'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                echo "</table></td>
                    </tr>";
                echo "</table>";
            }
            else
            {
                echo "<table border='0'>";

                if ($BingoBook->Name != "")
                {
                    echo "<tr>
                        <td><b>Bekannte Namen:</b></td>
                        <td>$BingoBook->Name</td>
                        </tr>";
                }

                if ($BingoBook->Spieler > 0) {
                    $sql = "SELECT id, Gefahrenpotential FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);
                    if ($Ninja->Gefahrenpotential == 0) {
                        $Gefahrenpotential = "E-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 1) {
                        $Gefahrenpotential = "D-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 2) {
                        $Gefahrenpotential = "C-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 3) {
                        $Gefahrenpotential = "B-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 4) {
                        $Gefahrenpotential = "A-Rang";
                    } elseif ($Ninja->Gefahrenpotential == 5) {
                        $Gefahrenpotential = "S-Rang";
                    }
                } else {
                    $Gefahrenpotential = "$BingoBook->Gefahrenpotential";
                }
                echo "<tr>
                    <td><b>Gefahrenpotential:</b></td>
                    <td>$Gefahrenpotential</td>
                    </tr>";
                if ($BingoBook->Spieler > 0) {
                    $sql = "SELECT id, Rang FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);
                    $Rang = $Ninja->Rang;
                } else {
                    $Rang = $BingoBook->Rang;
                }
                if ($BingoBook->Spieler > 0)
                {
                    $sql = "SELECT Heimatdorf FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);
                    $Gefahrenpotential = $Ninja->Heimatdorf;
                }
                else
                {
                    $Gefahrenpotential = $BingoBook->Heimatdorf;
                }
                echo "<tr>
                    <td><b>Heimatdorf:</b></td>
                    <td>$Gefahrenpotential</td>
                    </tr>";
                echo "<tr>
                    <td><b>Rang:</b></td>
                    <td>$Rang</td>
                    </tr>";
                echo "<tr>
                    <td><b>Vermerkt in:</b></td>
                    <td><table border='0'>";
                $Zahls = 0;

                if ($dorfs2->Heimatdorf == "Konoha"){$pos = stripos($BingoBook->Land, "Konoha"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohanew.gif' width='25' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Konoha"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohaold.gif' width='25' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Suna"){$pos = stripos($BingoBook->Land, "Suna"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunanew.gif' width='15' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Suna"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunaold.gif' width='15' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Kumo"){$pos = stripos($BingoBook->Land, "Kumo"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumonew.gif' width='25' height='10'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Kumo"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumoold.gif' width='25' height='10'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Iwa"){$pos = stripos($BingoBook->Land, "Iwa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwanew.gif' width='25' height='23'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Iwa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwaold.gif' width='25' height='23'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Ame"){$pos = stripos($BingoBook->Land, "Ame"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Amenew.gif' width='25' height='12'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Ame"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Ameold.gif' width='25' height='12'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Taki"){$pos = stripos($BingoBook->Land, "Taki"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takinew.gif' width='24' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Taki"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takiold.gif' width='24' height='25'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                if ($dorfs2->Heimatdorf == "Kusa"){$pos = stripos($BingoBook->Land, "Kusa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaneu.gif' width='25' height='14'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}
                else{$pos = stripos($BingoBook->Land, "Kusa"); if ($pos === false){}else{if ($Zahls == 0){echo "<tr>";}echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaold.gif' width='25' height='14'></td>"; if ($Zahls == 3){echo "</tr>"; $Zahls = 0;}else{$Zahls += 1;}}}

                echo "</table></td>
                    </tr>";
                echo "</table>";
            }

            echo "</td><td valign='top'>";
            $BingoBook->Spezialisierung = nl2br($BingoBook->Spezialisierung);
            $BingoBook->Text = nl2br($BingoBook->Text);
            echo "<b>Spezialisierung</b><br>
                $BingoBook->Spezialisierung<br>
                <br><b>Anmerkungen</b><br>
                $BingoBook->Text";

            echo "</td></tr>";
            echo "</table>";
            echo "</td></tr>";
            echo "</table><br><br><br>";

        }
    }
    else
    {
        echo "<table border='0' width='750'>";
        echo "<tr><td colspan='5' width='100%'></td></tr>";

        $Zahl = 0;
        $S_sql = "SELECT * FROM BingoBook ORDER BY Ninja";
        $S_query = mysql_query($S_sql);
        while ($BingoBook = mysql_fetch_object($S_query)) {
            $Zugang = 0;

            if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                $Zugang = 1;
            }
            $pos = strpos($BingoBook->Land, $dorfs2->Heimatdorf);
            if ($pos === false) {
                $sql = "SELECT Land FROM NPC WHERE User = '$dorfs->id'";
                $query = mysql_query($sql);
                while ($NPC = mysql_fetch_object($query)) {
                    $Land = str_replace("gakure", "", $NPC->Land);
                    if (strpos($BingoBook->Land, $Land) !== false) {
                        $Zugang = 1;
                    }
                }
            } else {
                $Zugang = 1;
            }

            if ($Zugang == 1 and $BingoBook->id > 0) {
                if ($Zahl == 0) {
                    echo "<tr>";
                }

                echo "<td width='25%' align='center'>";

                if ($BingoBook->Spieler > 0) {
                    $sql = "SELECT id, name, Passfoto FROM user WHERE id = '$BingoBook->Spieler'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);

                    if ($BingoBook->BildX == 0) {
                        $size = getimagesize($Ninja->Passfoto);
                        if ($size["0"] > 125 or $size["1"] > 125) {
                            if ($size["0"] > $size["1"]) {
                                $Prozent = 125 / $size["0"];
                            } else {
                                $Prozent = 125 / $size["1"];
                            }
                        } else {
                            $Prozent = 1;
                        }
                        $size["0"] *= $Prozent;
                        $size["0"] = round($size["0"], 0);
                        $size["1"] *= $Prozent;
                        $size["1"] = round($size["1"], 0);
                        $up = "UPDATE BingoBook SET BildX = '$size[0]' WHERE id = '$BingoBook->id'";
                        mysql_query($up);
                        $up = "UPDATE BingoBook SET BildY = '$size[1]' WHERE id = '$BingoBook->id'";
                        mysql_query($up);
                        $BingoBook->BildX = $size[0];
                        $BingoBook->BildY = $size[1];
                    }
                    echo "<table border='0'><tr><td height='125' width='125' align='center' valign='top'>";

                    echo "<div style=\"position:relative;\">";

                    if ($BingoBook->Gesucht == 1) {
                        echo "<div style=\"position:absolute; left:0; top:0; z-index: 1;\" align='center'>";
                        echo "<a href='?Eintrag=$BingoBook->id'><img src='Bilder/Infos/Akten/Wanted.png' width='125' height='125'></a>";
                        echo "</div>";
                    }

                    echo "<div style=\"position:absolute; width:125px; height:125px; left:0; top:0; z-index: 0; background-color:white;\" align='center' valign='center'>";
                    echo sprintf(
                        "<a href='?Eintrag=%s'><img src='%s' width='%s' height='%s'></a>",
                        $BingoBook->id,
                        $Ninja->Passfoto ?? '',
                        $BingoBook->BildX,
                        $BingoBook->BildY
                    );
                    echo "</div>";

                    echo "</div>";

                    echo "</td></tr></table><a href='?Eintrag=$BingoBook->id'>$BingoBook->Ninja</a><br>";
                } elseif ($BingoBook->NPC > 0) {
                    $sql = "SELECT id, NPC, Passfoto FROM user WHERE id = '$BingoBook->NPC'";
                    $query = mysql_query($sql);
                    $Ninja = mysql_fetch_object($query);

                    if ($BingoBook->BildX == 0) {
                        $size = getimagesize($Ninja->Passfoto);
                        if ($size["0"] > 125 or $size["1"] > 125) {
                            if ($size["0"] > $size["1"]) {
                                $Prozent = 125 / $size["0"];
                            } else {
                                $Prozent = 125 / $size["1"];
                            }
                        } else {
                            $Prozent = 1;
                        }
                        $size["0"] *= $Prozent;
                        $size["0"] = round($size["0"], 0);
                        $size["1"] *= $Prozent;
                        $size["1"] = round($size["1"], 0);
                        $up = "UPDATE BingoBook SET BildX = '$size[0]' WHERE id = '$BingoBook->id'";
                        $up = mysql_query($up);
                        $up = "UPDATE BingoBook SET BildY = '$size[1]' WHERE id = '$BingoBook->id'";
                        $up = mysql_query($up);
                        $BingoBook->BildX = $size[0];
                        $BingoBook->BildY = $size[1];
                    }
                    echo "<table border='0'><tr><td height='125' width='125' align='center' valign='top'>";

                    echo "<div style=\"position:relative;\">";

                    if ($BingoBook->Gesucht == 1) {
                        echo "<div style=\"position:absolute; left:0; top:0; z-index: 1;\" align='center'>";
                        echo "<a href='?Eintrag=$BingoBook->id'><img src='Bilder/Infos/Akten/Wanted.png' width='125' height='125'></a>";
                        echo "</div>";
                    }

                    echo "<div style=\"position:absolute; width:125px; height:125px; left:0; top:0; z-index: 0; background-color:white;\" align='center'>";
                    echo "<a href='?Eintrag=$BingoBook->id'><img src='$Ninja->Passfoto' width='$BingoBook->BildX' height='$BingoBook->BildY'></a>";
                    echo "</div>";

                    echo "</div>";

                    echo "</td></tr></table>
                        <a href='?Eintrag=$BingoBook->id'>$BingoBook->Ninja</a><br>";
                } else {
                    if ($BingoBook->BildX == 0 && $BingoBook->Bild != "") {
                        $size = getimagesize($BingoBook->Bild);
                        if (is_array($size) && ($size[0] > 125 || $size[1] > 125)) {
                            if ($size[0] > $size[1]) {
                                $Prozent = 125 / $size[0];
                            } else {
                                $Prozent = 125 / $size[1];
                            }
                        } else {
                            $Prozent = 1;
                            $size = [125, 125];
                        }
                        $size[0] *= $Prozent;
                        $size[0] = round($size["0"], 0);
                        $size[1] *= $Prozent;
                        $size[1] = round($size["1"], 0);
                        mysql_query("UPDATE BingoBook SET BildX = '$size[0]' WHERE id = '$BingoBook->id'");
                        mysql_query("UPDATE BingoBook SET BildY = '$size[1]' WHERE id = '$BingoBook->id'");
                        $BingoBook->BildX = $size[0];
                        $BingoBook->BildY = $size[1];
                    }
                    echo "<table border='0'><tr><td height='125' width='125' align='center' valign='top'>";

                    echo "<div style=\"position:relative;\">";

                    if ($BingoBook->Gesucht > 0) {
                        echo "<div style=\"position:absolute; left:0; top:0; z-index: 1;\" align='center'>";
                        echo "<a href='?Eintrag=$BingoBook->id'><img src='Bilder/Infos/Akten/Wanted.png' width='125' height='125'></a>";
                        echo "</div>";
                    }

                    echo "<div style=\"position:absolute; width:125px; height:125px; left:0; top:0; z-index: 0; background-color:white;\" align='center'>";
                    echo "<a href='?Eintrag=$BingoBook->id'><img src='$BingoBook->Bild' width='$BingoBook->BildX' height='$BingoBook->BildY'></a>";
                    echo "</div>";

                    echo "</div>";

                    echo "</td></tr></table>
                        <a href='?Eintrag=$BingoBook->id'>$BingoBook->Ninja</a><br>";
                }
                echo "<table border='0'>";

                $Zahls = 0;

                if ($dorfs2->Heimatdorf == "Konoha") {
                    if (stripos($BingoBook->Land, "Konoha") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohanew.gif' width='25' height='25'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Konoha") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Konohaold.gif' width='25' height='25'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Suna") {
                    if (stripos($BingoBook->Land, "Suna") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunanew.gif' width='15' height='25'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Suna") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Sunaold.gif' width='15' height='25'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Kumo") {
                    if (stripos($BingoBook->Land, "Kumo") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumonew.gif' width='25' height='10'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Kumo") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kumoold.gif' width='25' height='10'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Iwa") {
                    if (stripos($BingoBook->Land, "Iwa") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwanew.gif' width='25' height='23'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Iwa") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Iwaold.gif' width='25' height='23'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Ame") {
                    if (stripos($BingoBook->Land, "Ame") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Amenew.gif' width='25' height='12'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Ame") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Ameold.gif' width='25' height='12'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Taki") {
                    if (stripos($BingoBook->Land, "Taki") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takinew.gif' width='24' height='25'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Taki") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Takiold.gif' width='24' height='25'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                if ($dorfs2->Heimatdorf == "Kusa") {
                    if (stripos($BingoBook->Land, "Kusa") !== false) {
                        if ($Zahls == 0) {
                            echo "<tr>";
                        }
                        echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaneu.gif' width='25' height='14'></td>";
                        if ($Zahls == 3) {
                            echo "</tr>";
                            $Zahls = 0;
                        } else {
                            $Zahls += 1;
                        }
                    }
                } elseif (stripos($BingoBook->Land, "Kusa") !== false) {
                    if ($Zahls == 0) {
                        echo "<tr>";
                    }
                    echo "<td width='20%' height='35' align='center'><img src='Bilder/Forum/Landkram/Kusaold.gif' width='25' height='14'></td>";
                    if ($Zahls == 3) {
                        echo "</tr>";
                        $Zahls = 0;
                    } else {
                        $Zahls += 1;
                    }
                }

                echo "</table>";
                echo "<br></td>";
                if ($Zahl == 3) {
                    echo "</tr>";
                    $Zahl = 0;
                } else {
                    $Zahl += 1;
                }
            }
        }
        echo "</table>";
    }
}

echo "</td></tr></table>";

get_footer();
