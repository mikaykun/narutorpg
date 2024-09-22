<?php

include(__DIR__ . "/../Menus/layout1.inc");

$dorfs = nrpg_get_current_user();

if ($dorfs->admin == 3 or $dorfs->CoAdmin > 0) {
    $Admin = 1;
} else {
    $Admin = 0;
}

if ($dorfs->id > 0) {
    $Landzugriffober = ['\'Kiri\'', '\'Oto\'', '\'\'', '\'Landlos\''];

    if ($Eintraganders > 0) {
        $sql          = "SELECT User, Kategorie FROM Aktens WHERE id = '$Eintraganders'";
        $query        = mysql_query($sql);
        $Aktererander = mysql_fetch_object($query);
        $Akteuser     = $Aktererander->User;
        $Aktenteil    = $Aktererander->Kategorie;
    }
    if ($Eintragungs > 0) {
        $sql          = "SELECT User, Kategorie FROM Aktens WHERE id = '$Eintragungs'";
        $query        = mysql_query($sql);
        $Aktererander = mysql_fetch_object($query);
        $Akteuser     = $Aktererander->User;
        $Aktenteil    = $Aktererander->Kategorie;
    }
    if ($Aktenteilloesche > 0) {
        $sql          = "SELECT User, Kategorie FROM Aktens WHERE id = '$Aktenteilloesche'";
        $query        = mysql_query($sql);
        $Aktererander = mysql_fetch_object($query);
        $Akteuser     = $Aktererander->User;
        $Aktenteil    = $Aktererander->Kategorie;
    }

    $sql   = "SELECT id, name, Clan, Heimatdorf, Team, Rang FROM user WHERE id = '$Akteuser'";
    $query = mysql_query($sql);
    $Ninja = mysql_fetch_object($query);

    $Erlaubnis        = 0;
    $ErlaubnisChuunin = 0;
    $ErlaubnisLand    = 0;
    $sql2       = "SELECT Land FROM Regierung WHERE Helfer1 = '$c_loged' OR Helfer2 = '$c_loged' OR Helfer3 = '$c_loged' OR Helfer4 = '$c_loged'";
    $query2     = mysql_query($sql2);
    $Landzugang = mysql_fetch_object($query2);
    if ($Landzugang->Land != "") {
        $Landzugang->Land = str_replace("gakure", "", $Landzugang->Land);
        $Landzugriffober[] = '\'' . $Landzugang->Land . '\'';
        $Erlaubnis       = 1;
        $ErlaubnisChuunin = 1;
        $ErlaubnisLand   = 1;
    }

    $sql   = "SELECT NPC, Land FROM NPC WHERE User = '$c_loged'";
    $query = mysql_query($sql);
    while ($NPC = mysql_fetch_object($query)) {
        $NPC->Land = str_replace("gakure", "", $NPC->Land);
        $Landzugriffober[] = '\'' . $NPC->Land . '\'';
        $Erlaubnis       = 1;
        $ErlaubnisChuunin = 1;
        $ErlaubnisLand   = 1;

    }

    if ($Admin == 1) {
        $Erlaubnis       = 1;
        $ErlaubnisChuunin = 1;
        $ErlaubnisLand   = 1;
        array_push($Landzugriffober, '\'Konoha\'', '\'Kusa\'', '\'Suna\'', '\'Iwa\'', '\'Ame\'', '\'Kumo\'', '\'Taki\'');
    }

    if ($dorfs2->Rang != "Akademist" and $dorfs2->Rang != "Genin" and $dorfs2->Rang != "Missing-Nin") {
        $ErlaubnisChuunin = 1;
        $Erlaubnis        = 1;
        $Landzugriffober[] = '\'' . $dorfs2->Heimatdorf . '\'';
    }
    if ($dorfs2->Rang != "Akademist" and $dorfs2->Rang != "Genin" and $dorfs2->Rang != "Missing-Nin" and $dorfs2->Rang != "Chuunin") {
        $ErlaubnisChuunin = 1;
        $ErlaubnisLand    = 1;
        $Erlaubnis        = 1;
    }
    $Landzugriffober = array_unique($Landzugriffober);
    $Landzugriffober = implode(',', $Landzugriffober);
    if ($Erlaubnis == 1) {
        echo "<u><b>Akte von $Ninja->name </b></u><br><br>";
        echo "<table border='0' width='700' cellpadding='0' cellspacing='0'>
            <tr>";

        if ($Aktenteil == 1) {
            echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/Selected2.gif'>";
        } else {
            echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/NonSelected2.gif'>";
        }
        $sql   = "SELECT id FROM Aktens WHERE User = '$Ninja->id' AND Kategorie = '1' AND Eintrager = '$c_loged'";
        $query = mysql_query($sql);
        $Akte  = mysql_fetch_object($query);
        if ($Akte->id > 0) {
            echo "<b>";
        }
        echo "<a href='Akten.php?Akteuser=$Ninja->id&Aktenteil=1'><font color='black'>Persönliche Akte</font></a>";
        if ($Akte->id > 0) {
            echo "</b>";
        }
        echo "</td>";

        if ($ErlaubnisChuunin == 1) {
            $Lookilooki = 1;
        } elseif ($ErlaubnisLand) {
            $Lookilooki = 1;
        } else {
            $Lookilooki = 0;
        }
        if ($Lookilooki == 1) {
            if ($Aktenteil == 2) {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/Selected2.gif'>";
            } else {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/NonSelected2.gif'>";
            }
            $sql   = "SELECT id FROM Aktens WHERE User = '$Ninja->id' AND Kategorie = '2' AND `Landakte` IN ($Landzugriffober)";
            $query = mysql_query($sql);
            $Akte  = mysql_fetch_object($query);
            if ($Akte->id > 0) {
                echo "<b>";
            }
            echo "<a href='Akten.php?Akteuser=$Ninja->id&Aktenteil=2'><font color='black'>Akte ab Chuunin</font></a>";
            if ($Akte->id > 0) {
                echo "</b>";
            }
        } else {
            echo "<td align='center' width='175' height='33' background='Bilder/Infos/Akten/Notthere.gif'>";
        }
        echo "</td>";

        if ($ErlaubnisLand) {
            $Lookilooki = 1;
        } else {
            $Lookilooki = 0;
        }
        if ($Lookilooki == 1) {
            if ($Aktenteil == 3) {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/Selected2.gif'>";
            } else {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/NonSelected2.gif'>";
            }
            $sql   = "SELECT id FROM Aktens WHERE User = '$Ninja->id' AND Kategorie = '3' AND `Landakte` IN ($Landzugriffober)";
            $query = mysql_query($sql);
            $Akte  = mysql_fetch_object($query);
            if ($Akte->id > 0) {
                echo "<b>";
            }
            echo "<a href='Akten.php?Akteuser=$Ninja->id&Aktenteil=3'><font color='black'>Akte ab Jounin</font></a>";
            if ($Akte->id > 0) {
                echo "</b>";
            }
        } else {
            echo "<td align='center' width='175' height='33' background='Bilder/Infos/Akten/Notthere.gif'>";
        }
        echo "</td>";

        if ($ErlaubnisChuunin == 1) {
            $Lookilooki = 1;
        } elseif ($ErlaubnisLand) {
            $Lookilooki = 1;
        } else {
            $Lookilooki = 0;
        }
        if ($Lookilooki == 1) {
            if ($Aktenteil == 5) {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/Selected2.gif'>";
            } else {
                echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/NonSelected2.gif'>";
            }
            $sql   = "SELECT id FROM Aktens WHERE User = '$Ninja->id' AND Kategorie = '5' AND `Landakte` IN ($Landzugriffober)";
            $query = mysql_query($sql);
            $Akte  = mysql_fetch_object($query);
            if ($Akte->id > 0) {
                echo "<b>";
            }
            echo "<a href='Akten.php?Akteuser=$Ninja->id&Aktenteil=5'><font color='black'>Missionen</font></a>";
            if ($Akte->id > 0) {
                echo "</b>";
            }
        } else {
            echo "<td align='center' width='175' height='33' background='Bilder/Infos/Akten/Notthere.gif'>";
        }
        echo "</td>";

        if ($Aktenteil == 4) {
            echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/Selected2.gif'>";
        } else {
            echo "<td align='center' width='140' height='33' background='Bilder/Infos/Akten/NonSelected2.gif'>";
        }
        echo "<a href='Akten.php?Akteuser=$Ninja->id&Aktenteil=4'><font color='black'>Allgemeine Akte</font></a>";
        echo "</td>";

        echo "</tr>
            <td colspan='5' align='center' background='Bilder/Infos/Akten/Background.gif'>";
        echo "<table border='0' width='99%'><tr><td>";
        // Inhalt
        if ($Aktenteil > 0 and $Aktenteil < 6) {
            $Lookilooki = 0;
            if ($Aktenteil == 1 and $Erlaubnis == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 2 and $ErlaubnisChuunin == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 2 and $ErlaubnisLand == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 3 and $ErlaubnisLand == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 4 and $Erlaubnis == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 5 and $ErlaubnisChuunin == 1) {
                $Lookilooki = 1;
            }
            if ($Aktenteil == 5 and $ErlaubnisLand == 1) {
                $Lookilooki = 1;
            }
            if ($Lookilooki == 1) {

                $darfstwasmachen = 0;
                if ($Ninja->id != $c_loged or $Admin == 1) {
                    $darfstwasmachen = 1;
                } elseif ($Aktenteil == 1) {
                    $darfstwasmachen = 1;
                }
                if ($darfstwasmachen == 1) {
                    if ($Eintraganders > 0 and $Aktenteil != 4) {
                        if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                            $Adminbist = 1;
                        } else {
                            $Adminbist = 0;
                        }
                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            $sql    = "SELECT Inhalt, Titel FROM Aktens WHERE id = '$Eintraganders' AND `Landakte` IN ($Landzugriffober)";
                            $query  = mysql_query($sql);
                            $Aktere = mysql_fetch_object($query);
                            echo "<br><form method='POST' action='Akten.php?Eintragungs=$Eintraganders&Aktenteil=$Aktenteil&Akteuser=$Akteuser'>
                                <b>Titel des Eintrags:</b> $Aktere->Titel<br>
                                <b>Inhalt:</b><br>
                                <textarea name='Content' cols='40' rows='6'>$Aktere->Inhalt</textarea><br>
                                <input type='submit' value='Eintrag editieren'><br>
                                </form>";
                        }
                    } elseif ($Aktenteilloesch > 0 and $Aktenteil != 4) {
                        $Adminbist = ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) ? 1 : 0;

                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            echo "Soll der Eintrag in der Akte wirklich gelöscht werden?<br>
                                <a href='Akten.php?Aktenteilloesche=$Aktenteilloesch&Aktenteil=$Aktenteil&Akteuser=$Akteuser'>Ja, Eintrag löschen!</a><br><br>";
                        }
                    } elseif ($Aktenteilloesche > 0 and $Aktenteil != 4) {
                        $Adminbist = ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) ? 1 : 0;

                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            $c_IP = $_SERVER['REMOTE_ADDR'];
                            $lol  = gethostbyaddr($c_IP);
                            $rofl = $_SERVER["HTTP_USER_AGENT"];

                            $sql    = "SELECT * FROM Aktens WHERE id = '$Aktenteilloesche' AND `Landakte` IN ($Landzugriffober)";
                            $query  = mysql_query($sql);
                            $Aktere = mysql_fetch_object($query);

                            $Date = date("d.m.Y, H:i");
                            $ins  = "INSERT INTO Adminlog (Was, Wer, Wann, Bereich, IP) VALUES ('$dorfs2->name löscht den Akteneintrag von $Ninja->name:<br><b>$Aktere->Titel</b><br>$Aktere->Inhalt', '$c_loged', '$Date', 'Akten', '$c_IP : $lol : $rofl')";
                            $ins  = mysql_query($ins);

                            $del = "DELETE FROM Aktens WHERE id = '$Aktenteilloesche' AND `Landakte` IN ($Landzugriffober)";
                            $del = mysql_query($del);
                            echo "Akteneintrag wurde gelöscht!<br>";
                        }
                    } elseif ($Eintragungs > 0 and $Aktenteil != 4) {
                        $Adminbist = ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) ? 1 : 0;

                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            $sql    = "SELECT Inhalt, Datummach FROM Aktens WHERE id = '$Eintragungs' AND `Landakte` IN ($Landzugriffober)";
                            $query  = mysql_query($sql);
                            $Aktere = mysql_fetch_object($query);
                            $Datum  = date("d.m.Y - H:i");
                            $Datum  = "$Aktere->Datummach<br>$Datum - <i>Änderung von $dorfs2->name </i>";
                            if ($Aktenteil != 5) {
                                $Content = htmlentities((string) $Content);
                            }
                            $up = "UPDATE Aktens SET Inhalt = '$Content' WHERE id = '$Eintragungs' AND `Landakte` IN ($Landzugriffober)";
                            $up = mysql_query($up);
                            $up = "UPDATE Aktens SET Datummach = '$Datum' WHERE id = '$Eintragungs' AND `Landakte` IN ($Landzugriffober)";
                            $up = mysql_query($up);
                            echo "Eintrag wurde editiert!<br>";
                        }
                    } elseif ($Eintrag == 1 and $Aktenteil != 4) {
                        echo "<br><form method='POST' action='Akten.php?Eintragung=1&Aktenteil=$Aktenteil&Akteuser=$Akteuser'>
                            <b>Titel des Eintrags:</b> <input type='text' name='Title'><br>
                            <b>Inhalt:</b><br>
                            <textarea name='Content' cols='40' rows='6'></textarea><br>
                            <input type='submit' value='Eintrag tätigen'><br>
                            </form>";
                    } elseif ($Eintragung and $Aktenteil != 4) {
                        $Aktenteil = htmlentities((string) $Aktenteil);
                        $Content   = htmlentities((string) $Content);
                        $Title     = htmlentities((string) $Title);

                        $Datum = date("d.m.Y - H:i");
                        $ins   = "INSERT INTO Aktens (User, Eintrager, Kategorie, Inhalt, Datum, Titel, Datummach, Landakte) VALUES ('$Ninja->id', '$c_loged', '$Aktenteil', '$Content', '$Datum', '$Title', '$Datum', '$dorfs2->Heimatdorf')";
                        $ins   = mysql_query($ins);
                        echo "Eintrag in die Akte getätigt!<br>";
                    } elseif ($Aktenteil != 4) {
                        echo "<br><a href='Akten.php?Eintrag=1&Akteuser=$Ninja->id&Aktenteil=$Aktenteil'>Neuen Akteneintrag tätigen</a><br><br>";
                    } else {
                        echo "<br>";
                    }
                } else {
                    echo "<br>";
                }

                echo "<script>
                    function show(id)
                    {
                        if (document.getElementById(id).style.display == 'block')
                        {
                            document.getElementById(id).style.display = 'none';
                        }
                        else
                        {
                            document.getElementById(id).style.display = 'block';
                        }
                    }
                    </script>";


                if ($Aktenteil != 1) {
                    $sql   = "SELECT * FROM Aktens WHERE User = '$Akteuser' AND Kategorie = '$Aktenteil' AND `Landakte` IN ($Landzugriffober) ORDER BY id DESC";
                    $query = mysql_query($sql);
                    while ($Akten = mysql_fetch_object($query)) {
                        if ($Akten->Titel == "") {
                            $Akten->Titel = "Kein Titel";
                        }

                        echo "<style>
                            #Akte$Akten->id {
                                display:none;
                            }
                            #Datum$Akten->id {
                                display:none;
                            }
                            </style>";

                        $sqls = "SELECT name FROM user WHERE id = '$Akten->Eintrager'";
                        $querys = mysql_query($sqls);
                        $Ninjas = mysql_fetch_object($querys);

                        $Akten->Inhalt = nl2br($Akten->Inhalt);
                        $Akten->Inhalt = preg_replace("/\r|\n/s", "", $Akten->Inhalt);
                        $Akten->Titel  = nl2br($Akten->Titel);
                        $Akten->Titel  = preg_replace("/\r|\n/s", "", $Akten->Titel);
                        $needle = ["/^.*Bewertung:(.*)<br>*?.*$/Us", "/^.*Bewertung:(.*)$/Us"];
                        if ($Aktenteil == 5 && (preg_match($needle[0], (string) $Akten->Inhalt) === 1 || preg_match($needle[1], (string) $Akten->Inhalt) === 1)) {
                            $bewertung = preg_replace($needle, "$1", $Akten->Inhalt);
                            if (strlen((string) $bewertung) < 25) {
                                $Akten->Titel .= ' - ';
                                $Akten->Titel  .= $bewertung;
                            }
                        }

                        echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                        echo "<tr>";
                        echo "<td background='Bilder/Infos/Akten/Aktenteil2rest.bmp'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' height='33'><tr><td><img src='Bilder/Infos/Akten/Aktenteil2links.bmp' align='left' hspace='0'></td>
                            <td background='Bilder/Infos/Akten/Aktenteil2mitte.bmp'>
                            <a href=\"javascript:show('Akte$Akten->id');\"><font color='black'><b>$Akten->Titel</b></font></a>
                            </td><td>
                            <img src='Bilder/Infos/Akten/Aktenteil2rechts.bmp'></td></tr></table>
                            </td>";
                        echo "</tr><tr><td>
                            <div id='Akte$Akten->id'>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                            <tr>
                            <td>";
                        $dorf = $Akten->Landakte;
                        echo "<img border='0' src='/Bilder/$dorf.bmp'>";

                        echo "</td>
                            <td>
                            Original angelegt von:<br>
                            <b>";
                        if ($Ninjas->name == "") {
                            echo "System";
                        } else {
                            echo "$Ninjas->name";
                        }
                        echo "</b></td>
                            <td>
                            am<br>
                            <a href=\"javascript:show('Datum$Akten->id');\"><font color='black'>$Akten->Datum</font></a><div id='Datum$Akten->id'>$Akten->Datummach</div>
                            </td>
                            <td>";

                        if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                            $Adminbist = 1;
                        } else {
                            $Adminbist = 0;
                        }
                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            echo "<a href='Akten.php?Eintraganders=$Akten->id&Akteuser=$Ninja->id&Aktenteil=$Aktenteil'>Eintrag editieren</a>
                                <br><a href='Akten.php?Aktenteilloesch=$Akten->id&Akteuser=$Ninja->id&Aktenteil=$Aktenteil'>Eintrag löschen</a>";
                        }

                        echo "</td>
                            </tr>
                            <tr>
                            <td colspan='4'><hr>$Akten->Inhalt<br><br></td>
                            </tr>
                            </table>
                            </div></td>";
                        echo "</tr>";
                        echo "</table>";


                    }
                } else {
                    $sql   = "SELECT * FROM Aktens WHERE User = '$Akteuser' AND Kategorie = '$Aktenteil' AND Eintrager = '$c_loged' AND `Landakte` IN ($Landzugriffober)";
                    $query = mysql_query($sql);
                    while ($Akten = mysql_fetch_object($query)) {
                        if ($Akten->Titel == "") {
                            $Akten->Titel = "Kein Titel";
                        }

                        echo "<style type=\"text/css\">
                            <!--
#Akte$Akten->id
                            {
display:none;
                            }
#Datum$Akten->id
                        {
display:none;
                        }
                        -->
                            </style>";

                        $sqls   = "SELECT name FROM user WHERE id = '$Akten->Eintrager'";
                        $querys = mysql_query($sqls);
                        $Ninjas = mysql_fetch_object($querys);

                        $Akten->Inhalt = nl2br($Akten->Inhalt);
                        $Akten->Inhalt = preg_replace("/\r|\n/s", "", $Akten->Inhalt);
                        $Akten->Titel  = nl2br($Akten->Titel);
                        $Akten->Titel  = preg_replace("/\r|\n/s", "", $Akten->Titel);

                        echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                        echo "<tr>";
                        echo "<td background='Bilder/Infos/Akten/Aktenteil2rest.bmp'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' height='33'><tr><td><img src='Bilder/Infos/Akten/Aktenteil2links.bmp' align='left' hspace='0'></td>
                            <td background='Bilder/Infos/Akten/Aktenteil2mitte.bmp'>
                            <a href=\"javascript:show('Akte$Akten->id');\"><font color='black'><b>$Akten->Titel</b></font></a></td><td><img src='Bilder/Infos/Akten/Aktenteil2rechts.bmp'></td>
                            </td></tr></table>
                            </td>";
                        echo "</tr><tr><td>
                            <div id='Akte$Akten->id'>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                            <tr>
                            <td>";
                        $dorf = $Akten->Landakte;
                        echo "<img border='0' src='/Bilder/$dorf.bmp'>";

                        echo "</td>
                            <td>
                            Original angelegt von:<br>
                            <b>";
                        if ($Ninjas->name == "") {
                            echo "System";
                        } else {
                            echo "$Ninjas->name";
                        }
                        echo "</b></td>
                            <td>
                            am<br>
                            <a href=\"javascript:show('Datum$Akten->id');\"><font color='black'>$Akten->Datum</font></a><div id='Datum$Akten->id'>$Akten->Datummach</div>
                            </td>
                            <td>";

                        if ($dorfs->admin >= 3 or $dorfs->CoAdmin > 0) {
                            $Adminbist = 1;
                        } else {
                            $Adminbist = 0;
                        }
                        if ($Aktenteil != 5 or $Adminbist == 1) {
                            echo "<a href='Akten.php?Eintraganders=$Akten->id&Akteuser=$Ninja->id&Aktenteil=$Aktenteil'>Eintrag editieren</a>
                                <br><a href='Akten.php?Aktenteilloesch=$Akten->id&Akteuser=$Ninja->id&Aktenteil=$Aktenteil'>Eintrag löschen</a>";
                        }

                        echo "</td>
                            </tr>
                            <tr>
                            <td colspan='4'><hr>$Akten->Inhalt<br><br></td>
                            </tr>
                            </table>
                            </div></td>";
                        echo "</tr>";
                        echo "</table>";


                    }
                }

            }
        } else {
            echo "<center>
                <br><br><b>Ninjaakte von</b><br><br>
                $Ninja->name<br><br>";
            echo "<img border='0' src='Bilder/" . $Ninja->Heimatdorf . ".bmp'>";
            echo "<br><br>$Ninja->Rang";
        }
        // Inhalt
        echo "</td></tr></table></center></td>
            </tr>
            <tr>
            <td colspan='5'>
            <img src='Bilder/Infos/Akten/Under.gif'></td>";
        echo "</tr></table>";
    } else {
        echo "Du hast keine Erlaubnis die Akten dieses Ninja einzusehen!";
    }
}

get_footer();
