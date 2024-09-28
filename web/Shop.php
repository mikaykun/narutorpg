<?php

include(__DIR__ . "/../Menus/layout1.inc");

$c_loged = $_COOKIE["c_loged"];
$dorfs2 = nrpg_get_current_character();

include(__DIR__ . "/../layouts/Overview/OverviewLand.php");
include(__DIR__ . "/../layouts/Overview/OverviewShop.php");

$tools = new otherHelpfulTools();
$costs = $tools->getKindOfCosts();
$effectViewModel = new EffectViewModel();
$itemViewModel = new ItemViewModel();
$tps = new tpKosten();

echo "<tr><td align='center' colspan='6'><br>";

$dbc = nrpg_get_database();
$object = nrpg_get_current_character();
$abfrage2 = "SELECT * FROM Besonderheiten WHERE id LIKE '$c_loged'";
$ergebnis2 = mysql_query($abfrage2);
$object3 = mysql_fetch_object($ergebnis2);
$u_Jutsu = $dbc->query("SELECT * FROM Jutsuk WHERE id = '$dorfs2->id'")->fetch(PDO::FETCH_ASSOC);
$tps->thisIsOkMax($u_Jutsu, $dorfs2);

if ($object->id > 0) {
    $geld = $object->Geld;
    if (isset($verkauf) and $_GET['Savior'] == $object_save_forms) {
        echo "Du kannst nur Items verkaufen, welche bei dir zu Hause lagern!";
        echo "<form method='POST' action='Shop.php?verkaufen=1&Savior=" . "$object_save_forms" . "'>Item: <select name='Verkaufitem'>";
        $sql = "SELECT * FROM Item WHERE Von = '$c_loged'";
        $query = mysql_query($sql);
        while ($row = mysql_fetch_array($query)) {
            if ($row["Angelegt"] == "") {
                if ($row["Menge"] > 0) {
                    $Item = $row["Item"];
                    $Menge = $row["Menge"];
                    $Itemid = $row["id"];
                    echo "<option value='$Itemid'>$Item ($Menge Stk.)";
                }
            }
        }
        echo "</select><br><input type='submit' value='Verkaufen'></form>";
    } elseif (isset($verkaufnu) and $_GET['Savior'] == $object_save_forms) {
        $sql = "SELECT * FROM Item WHERE id = '$verkaufnu'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemk = mysql_fetch_object($query);
        $Verkaufnetgeht = 0;
        if ($Item->Angelegt == "") {
            if ($Mengeverkauf > 0) {
                if ($Item->Menge >= $Mengeverkauf) {
                    if ($Item->Von == $c_loged) {
                        $Geldgibt = $Itemk->Kosten * 0.75;
                        //$Geldgibt /= 2;
                        $Geldgibt = round($Geldgibt, 0);
                        $Geldgibt *= $Mengeverkauf;
                        $geld += $Geldgibt;
                        $up = "UPDATE user SET Geld = '$geld' WHERE id = '$c_loged'";
                        $up = mysql_query($up);
                        $Itemmenge = $Item->Menge;
                        $Itemmenge -= $Mengeverkauf;
                        if ($Itemmenge > 0) {
                            $up = "UPDATE Item SET Menge = '$Itemmenge' WHERE id = '$verkaufnu'";
                            $up = mysql_query($up);

                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item x$Item->Menge verkauft auf $Itemmenge', '$Date')";
                            $ins = mysql_query($ins);

                        } else {
                            $del = "DELETE FROM Item WHERE id = '$verkaufnu'";
                            $del = mysql_query($del);

                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item x$Item->Menge komplett verkauft', '$Date')";
                            $ins = mysql_query($ins);
                        }
                        $up = "UPDATE Item SET Angelegt = '' WHERE Angelegt = 'Item: $verkaufnu'";
                        $up = mysql_query($up);
                        echo "$Mengeverkauf mal $Itemk->Name für $Geldgibt Ryô verkauft! - <a href='Shop.php'>Zurück zum Shop</a><br /><br />";
                    }
                }
            }
        }
    } elseif (isset($verkaufen) and $_GET['Savior'] == $object_save_forms) {
        $sql = "SELECT * FROM Item WHERE id = '$Verkaufitem'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemk = mysql_fetch_object($query);
        if ($Item->Angelegt == "") {
            if ($Item->Von == $c_loged) {
                echo "<form method='POST' action='Shop.php?verkaufnu=$Item->id&Savior=" . "$object_save_forms" . "'>$Item->Item <select name='Mengeverkauf'>";
                $Menge = $Item->Menge;
                while ($Menge > 0) {
                    echo "<option>$Menge";
                    $Menge -= 1;
                }
                $Verkaufpreis = $Itemk->Kosten * 0.75;
                //$Verkaufpreis /= 2;
                $Verkaufpreis = round($Verkaufpreis, 0);
                echo "</select> mal für $Verkaufpreis Ryô pro Stück<br><input type='submit' value='verkaufen'></form>";
            }

        }

    }
    if (isset($kauf) and $_GET['Savior'] == $object_save_forms) {
        $sql = "SELECT * FROM Itemsk WHERE id = '$kauf' AND Wokaufen = 'Shop'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        if ($Menge > 0) {

            if ($Item->id > 0) {
                $Preise = $Menge;
                $Preise *= $Item->Kosten;
                if ($Preise <= $geld) {
                    $darfste = 1;

                    $katArray = [];
                    $sql = "SELECT * FROM itemKategorie WHERE itemID = '$Item->id'";
                    $queryKat = mysql_query($sql);
                    while ($itemKat = mysql_fetch_object($queryKat)) {
                        $katArray[$itemKat->jutsuKategorie] = true;
                        if ($tps->thisItemOk($Item->Niveau, $itemKat->jutsuKategorie) == false) {
                            $darfste = 0;
                        }
                    }
                    if ($Item->Perso != "0") {
                        $Item->Useronly = "%&%&%$Item->Useronly%&%&%";
                        $pos = strpos($Item->Useronly, ",$object->name%&%&%");
                        if ($pos === false) {
                            $pos = strpos($Item->Useronly, "%&%&%$object->name%&%&%");
                            if ($pos === false) {
                                $pos = strpos($Item->Useronly, "%&%&%$object->name,");
                                if ($pos === false) {
                                    $pos = strpos($Item->Useronly, ",$object->name,");
                                    if ($pos === false) {
                                        $darfste = 0;
                                    }
                                }
                            }
                        }
                    }
                    $TP = $tps->howMuchIsThisItem($Item->id, 0, $object->id, 0);
                    $tpMon = [1 => 0, 2 => 6, 3 => 8, 4 => 10];
                    $CP = $TP / ($tpMon[$object->Niveau] * 2);
                    $abfrage2 = "SELECT COUNT(*) FROM itemFaeh WHERE uId = '$c_loged' AND iId = '$Item->id'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $beherrscht = mysql_fetch_row($ergebnis2);
                    $beherrscht = $beherrscht[0];
                    if ($beherrscht >= 1) {
                        $TP = 0;
                    }
                    if ($TP == 0) {
                        $CP = 0;
                    }
                    if ($TP > ($object->Trainingspunkte + $tpMon[$object->Niveau]) && $TP != 0) {
                        $darfste = 0;
                    } elseif ($CP > $object->CenterPunkte && $CP != 0) {
                        $darfste = 0;
                    }
                    if ($Item->Land != "") {
                        $pos = strpos($Item->Land, "$object->Heimatdorf");
                        if ($pos === false) {
                            $darfste = 0;
                        }
                    }
                    if ($Item->Useronly != "") {
                        $pos = strpos($Item->Useronly, "$object->name");
                        if ($pos === false) {
                            $darfste = 0;
                        }
                    }
                    if ($Item->Clan != "") {
                        if ($Item->Clan != $object->Clan) {
                            $darfste = 0;
                        }
                    }

                    if ($Item->Abhängigkeit != "") {
                        $abfrage2 = "SELECT * FROM Jutsuk WHERE id LIKE '$c_loged'";
                        $ergebnis2 = mysql_query($abfrage2);
                        $Jutsuk = mysql_fetch_object($ergebnis2);
                        $abfrage2 = "SELECT * FROM Fähigkeiten WHERE id LIKE '$c_loged'";
                        $ergebnis2 = mysql_query($abfrage2);
                        $Fähigkeiten = mysql_fetch_object($ergebnis2);

                        $SonZaehler = 0;
                        $Teiler = explode("&", $Item->Abhängigkeit);
                        $Teil = 0;
                        while ($Teiler[$Teil] != "") {
                            $Teiler2 = explode("%", $Teiler[$Teil]);

                            $Wert = $Teiler2[1];
                            $Wert2 = $Teiler2[2];

                            if ($Teiler2[0] == "Jutsu") {
                                $Werto = $Jutsuk->$Wert;
                                if ($Werto < $Wert2) {
                                    $darfste = 0;
                                }
                            }

                            if ($Teiler2[0] == "Faehigkeit") {
                                $Werto = $Fähigkeiten->$Wert;
                                if ($Werto < $Wert2) {
                                    $darfste = 0;
                                }
                            }
                            $Teil += 1;
                        }

                    }

                    if ($darfste == 1) {
                        if ($Item->Name == "Chips") {
                            $lols = 10;
                            $lols *= $Menge;
                            $kal = $object->Kalorien;
                            $kal += $lols;
                            $kal = ceil($kal);
                            $kal = ($kal > 3000) ? 3000 : $kal;
                            $aendern = "UPDATE user set Kalorien = '$kal' WHERE id = '$c_loged'";
                            $update = mysql_query($aendern) or die("Fehler beim eintragen der Items!");
                            $geld -= $Preise;
                            $aendern = "UPDATE user set Geld = '$geld' WHERE id = '$c_loged'";
                            $update = mysql_query($aendern) or die("Fehler beim abziehen des Geldes!");
                            echo "Sie haben $Menge mal Chips für $Preise Ryô gekauft (Das bringt dir $lols Kalorien). Sie haben noch $geld Ryô";
                            echo "<br><a href='Shop.php'>Zurück</a>";
                        } elseif ($Item->Name == "Barbeque") {
                            $lols = 20;
                            $lols *= $Menge;
                            $kal = $object->Kalorien;
                            $kal += $lols;
                            $kal = ceil($kal);
                            $kal = ($kal > 3000) ? 3000 : $kal;
                            $aendern = "UPDATE user set Kalorien = '$kal' WHERE id = '$c_loged'";
                            $update = mysql_query($aendern) or die("Fehler beim eintragen der Items!");
                            $geld -= $Preise;
                            $aendern = "UPDATE user set Geld = '$geld' WHERE id = '$c_loged'";
                            $update = mysql_query($aendern) or die("Fehler beim abziehen des Geldes!");
                            echo "Sie haben $Menge mal Barbeque für $Preise Ryô gekauft (Das bringt $lols Kalorien). Sie haben noch $geld Ryô";
                            echo "<br><a href='Shop.php'>Zurück</a>";
                        } else {
                            //Anfang
                            $ok = 0;
                            $abfrage2 = "SELECT * FROM Jutsuk WHERE id LIKE '$c_loged'";
                            $ergebnis2 = mysql_query($abfrage2);
                            $Jutsuk = mysql_fetch_object($ergebnis2);
                            if ($Name == "Sandflasche") {
                                if ($object3->Sand == 1) {
                                    $ok = 1;
                                }
                            } else {
                                $ok = 1;
                            }
                            if ($Item->Tabelle != "") {
                                $cake = "SELECT * FROM $Item->Tabelle WHERE id = '$c_loged'";
                                $cakes = mysql_query($cake);
                                $Tabellenwert = mysql_fetch_object($cakes);
                                $Spalte = $Item->Spalte;
                                $Wertduhast = $Tabellenwert->$Spalte;
                                $Wertbraucht = str_replace("<=", "", $Item->Wert);
                                $Wertbraucht = str_replace(">=", "", $Wertbraucht);
                                $Wertbraucht = str_replace(">", "", $Wertbraucht);
                                $Wertbraucht = str_replace("<", "", $Wertbraucht);

                                $pos = strpos($Item->Wert, ">=");
                                if ($pos === false) {
                                    $pos = strpos($Item->Wert, "<=");
                                    if ($pos === false) {
                                        $pos = strpos($Item->Wert, ">");
                                        if ($pos === false) {
                                            $pos = strpos($Item->Wert, "<");
                                            if ($pos === false) {
                                                if ($Wertduhast == $Wertbraucht) {
                                                } else {
                                                    $ok = 0;
                                                }
                                            } else {
                                                if ($Wertduhast < $Wertbraucht) {
                                                } else {
                                                    $ok = 0;
                                                }
                                            }
                                        } else {
                                            if ($Wertduhast > $Wertbraucht) {
                                            } else {
                                                $ok = 0;
                                            }
                                        }
                                    } else {
                                        if ($Wertduhast <= $Wertbraucht) {
                                        } else {
                                            $ok = 0;
                                        }
                                    }
                                } else {
                                    if ($Wertduhast >= $Wertbraucht) {
                                    } else {
                                        $ok = 0;
                                    }
                                }
                            }
                            if ($ok == 1) {
                                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Item = '$Item->Name' AND Angelegt = ''";
                                $query = mysql_query($sql);
                                $Item2 = mysql_fetch_object($query);
                                if ($Item2->id > 0 and $Item->NonStack != 1) {
                                    $up = "UPDATE Item SET Menge = Menge+$Menge WHERE id = '$Item2->id'";
                                    $up = mysql_query($up);

                                    $Date = date("d.m.Y, H:i");
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item2->Item x$Menge gekauft', '$Date')";
                                    $ins = mysql_query($ins);

                                } else {

                                    $Grosse = $Item->Platz;
                                    if ($Grosse == 3) {
                                        $Grosse = 5;
                                    }
                                    if ($Item->NonStack != 1) {

                                        $Date = date("d.m.Y, H:i");
                                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Name x$Menge gekauft', '$Date')";
                                        $ins = mysql_query($ins);

                                        $ins = "INSERT INTO Item (Von, Item, Menge, Ort, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Item->Name', '$Menge', '', '$Grosse', '$Item->RustAusdauer', '$Item->RustBeschrankung', '$Item->RustBlutung')";
                                        $ins = mysql_query($ins);
                                    } else {
                                        $Mengen = $Menge;
                                        while ($Mengen > 0) {
                                            $ins = "INSERT INTO Item (Von, Item, Menge, Ort, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Item->Name', '1', '', '$Grosse', '$Item->RustAusdauer', '$Item->RustBeschrankung', '$Item->RustBlutung')";
                                            $ins = mysql_query($ins);
                                            $Date = date("d.m.Y, H:i");
                                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Name x1 gekauft', '$Date')";
                                            $ins = mysql_query($ins);
                                            $Mengen -= 1;
                                        }
                                    }
                                }
                                $geld -= $Preise;
                                $up = "UPDATE user SET Geld = '$geld',`Trainingspunkte` = `Trainingspunkte`-$TP, CenterPunkte = `CenterPunkte`-$CP WHERE id = '$c_loged'";
                                $up = mysql_query($up);
                                if ($beherrscht < 1) {
                                    $up = "INSERT INTO itemFaeh VALUES ($c_loged,$Item->id)";
                                    $up = mysql_query($up);
                                    $Dateingert = date("d.m.Y");
                                    $ENDTP = $dorfs2->Trainingspunkte - $TP;
                                    $insert = "INSERT INTO NPCSystem (NPC, Text, Datum, Land, Training, Ninkriegt, Passiertemit, TP)
                                    VALUES ('', 'Item gekauft: " . $Item->Name . " (" . $dorfs2->Trainingspunkte . " - " . $TP . " = " . $ENDTP . " TP)', '" . $Dateingert . "', '" . $dorfs2->Heimatdorf . "',
                                    '" . $dorfs2->id . " " . $Item->Name . " 1', '" . $dorfs2->id . "', '1', '1')";
                                    $insert = mysql_query($insert);
                                }
                                echo "Du hast $Menge mal das Item \"$Item->Name\" für $Preise Ryô gekauft.<br><a href='Shop.php'>Zurück</a>";
                            } else {
                                echo "Diesen Gegenstand kannst du nicht kaufen!<br><br>";
                            }

                        }
                    }
                    //Ende
                } else {
                    echo "Du hast nicht genug Geld!<br><br>";
                }
            }
        }
    } else {
        $abfrage2 = "SELECT * FROM user WHERE id LIKE '$c_loged'";
        $ergebnis2 = mysql_query($abfrage2);
        $object = mysql_fetch_object($ergebnis2);
        $abfrage2 = "SELECT * FROM Jutsuk WHERE id LIKE '$c_loged'";
        $ergebnis2 = mysql_query($abfrage2);
        $Jutsuk = mysql_fetch_object($ergebnis2);
        echo "Die Items im Shop sind immer auf die momentanen Item-Erhaltmöglichkeiten des eigenen Charakters bezogen.<br/>";
        echo "Du besitzt $geld Ryô - <a href='Shop.php?verkauf=1&Savior=$object_save_forms'>Items verkaufen</a><br>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' background=''>";
        if ($Shopseite < 1) {
            $Shopseite = 1;
        }
        echo "
            <tr><td colspan='4'><br><br></td></tr>";

        if ($Shopseite < 1) {
            $Shopseite = 1;
        }
        $Itemsperpage = 20;
        $Itemmenge = 0;

        $Startid = "";

        if ($Shopseite <= 1) {
            $Start = 0;
        } else {
            $Start = ($Shopseite * $Itemsperpage) - $Itemsperpage;
        }
        $Ranguser = $dorfs2->Niveau + 1;
        $Itemkramsen = 0;
        if ($KategorieShop == "Waffen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden != '' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name";
        } elseif ($KategorieShop == "Rustungen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Rüstung != '' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name";
        } elseif ($KategorieShop == "Taschen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden = '' AND Rüstung = '' AND Platzdrin > '0' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name";
        } elseif ($KategorieShop == "Sonstiges") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden = '' AND Rüstung = '' AND Platzdrin < '1' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name";
        } elseif ($KategorieShop == "Gifte") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND (Abhängigkeit LIKE '%gift%' OR Abhängigkeit LIKE '%Heilkunde%') ORDER BY Name";
        } else {
            $sql = "SELECT * FROM Itemsk WHERE id = '0' ORDER BY Name";
        }
        $query = mysql_query($sql);
        while ($row = mysql_fetch_array($query)) {

            $Itemid = $row["id"];
            $Name = $row["Name"];
            $ok = 0;
            if ($Name == "Sandflasche") {
                if ($object3->Sand == 1) {
                    $ok = 1;
                }
            } else {
                $ok = 1;
            }
            if ($row["Tabelle"] != "") {
                $cake = "SELECT * FROM $row[Tabelle] WHERE id = '$c_loged'";
                $cakes = mysql_query($cake);
                $Tabellenwert = mysql_fetch_object($cakes);
                $Spalte = $row["Spalte"];
                $Wertbraucht = str_replace("<=", "", $row["Wert"]);
                $Wertbraucht = str_replace(">=", "", $Wertbraucht);
                $Wertbraucht = str_replace(">", "", $Wertbraucht);
                $Wertbraucht = str_replace("<", "", $Wertbraucht);
                $Wertduhast = $Tabellenwert->$Spalte;
                $pos = strpos($row["Wert"], ">=");
                if ($pos === false) {
                    $pos = strpos($row["Wert"], "<=");
                    if ($pos === false) {
                        $pos = strpos($row["Wert"], ">");
                        if ($pos === false) {
                            $pos = strpos($row["Wert"], "<");
                            if ($pos === false) {
                                if ($Wertduhast == $Wertbraucht) {
                                } else {
                                    $ok = 0;
                                }
                            } else {
                                if ($Wertduhast < $Wertbraucht) {
                                } else {
                                    $ok = 0;
                                }
                            }
                        } else {
                            if ($Wertduhast > $Wertbraucht) {
                            } else {
                                $ok = 0;
                            }
                        }
                    } else {
                        if ($Wertduhast <= $Wertbraucht) {
                        } else {
                            $ok = 0;
                        }
                    }
                } else {
                    if ($Wertduhast >= $Wertbraucht) {
                    } else {
                        $ok = 0;
                    }
                }
            }
            if ($ok == 1) {
                $Beschreibung = $row["Beschreibung"];
                $Waffe = $row["Waffe"];
                $Waffenah = $row["Waffenah"];
                $Waffefern = $row["Waffefern"];
                $Waffenart = $row["Waffenart"];
                $Land = $row["Land"];
                $Clan = $row["Clan"];
                $Preis = $row["Kosten"];
                $Useronly = $row["Useronly"];
                $darfste = 1;
                if ($Land != "") {
                    $pos = strpos($Land, "$object->Heimatdorf");
                    if ($pos === false) {
                        $darfste = 0;
                    }
                }

                $Perso = $row['Perso'];
                $Useronly = $row['Useronly'];

                if ($Perso != "0") {
                    $Useronly = "%&%&%$Useronly%&%&%";
                    $pos = strpos($Useronly, ",$object->name%&%&%");
                    if ($pos === false) {
                        $pos = strpos($Useronly, "%&%&%$object->name%&%&%");
                        if ($pos === false) {
                            $pos = strpos($Useronly, "%&%&%$object->name,");
                            if ($pos === false) {
                                $pos = strpos($Useronly, ",$object->name,");
                                if ($pos === false) {
                                    $darfste = 0;
                                }
                            }
                        }
                    }
                }

                if ($Useronly != "") {
                    $pos = strpos($Useronly, "$object->name");
                    if ($pos === false) {
                        $darfste = 0;
                    }
                }
                if ($Clan != "") {
                    if ($Clan != $object->Clan) {
                        $darfste = 0;
                    }
                }

                if ($row['Abhängigkeit'] != "") {
                    $abfrage2 = "SELECT * FROM Jutsuk WHERE id LIKE '$c_loged'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $Jutsuk = mysql_fetch_object($ergebnis2);
                    $abfrage2 = "SELECT * FROM Fähigkeiten WHERE id LIKE '$c_loged'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $Fähigkeiten = mysql_fetch_object($ergebnis2);

                    $SonZaehler = 0;
                    $Teiler = explode("&", $row['Abhängigkeit']);
                    $Teil = 0;
                    while ($Teiler[$Teil] != "") {
                        $Teiler2 = explode("%", $Teiler[$Teil]);

                        $Wert = $Teiler2[1];
                        $Wert2 = $Teiler2[2];

                        if ($Teiler2[0] == "Jutsu") {
                            $Werto = $Jutsuk->$Wert;
                            if ($Werto < $Wert2) {
                                $darfste = 0;
                            }
                        }

                        if ($Teiler2[0] == "Faehigkeit") {
                            $Werto = $Fähigkeiten->$Wert;
                            if ($Werto < $Wert2) {
                                $darfste = 0;
                            }
                        }

                        $Teil += 1;
                    }

                }

                if ($darfste == 1) {
                    $Itemmenge += 1;

                    $Blap = $Itemmenge / 20;
                    $Blop = round($Blap, 0);
                    if ($Blap == $Blop) {
                        $Startid[$Blop] = $row['id'];
                    }

                } else {
                    if ($Itemmenge < $Start) {
                        $Itemkramsen += 1;
                    }
                }
            } else {
                if ($Itemmenge < $Start) {
                    $Itemkramsen += 1;
                }
            }
        }
        $Itemmengen = $Itemmenge / $Itemsperpage;
        $Seiten = ceil($Itemmengen);
        if ($Itemkramsen > 0) {
            $Itemkramsen -= 1;
        }


        echo "<tr>";
        echo "<td>";
        if ($Shopseite > 1) {
            $Page = $Shopseite - 1;
            echo "<a href='Shop.php?Shopseite=$Page&KategorieShop=$KategorieShop'>";
            echo "<<<";
            echo "</a>";
        }
        echo "</td>";
        echo "<td colspan='2'><center>";
        $Nummer = 0;
        while ($Nummer < $Seiten) {
            $Nummer += 1;
            if ($Nummer > 1) {
                echo ", ";
            }
            if ($Nummer == $Shopseite) {
                echo "<b>[$Nummer]</b>";
            } else {
                echo "<a href='Shop.php?Shopseite=$Nummer&KategorieShop=$KategorieShop'>$Nummer</a>";
            }
        }
        echo "</center></td>";
        echo "<td>";
        if ($Shopseite < $Seiten) {
            $Page = $Shopseite + 1;
            echo "<a href='Shop.php?Shopseite=$Page&KategorieShop=$KategorieShop'>";
            echo ">>>";
            echo "</a>";
        }
        echo "</td>";
        echo "</tr></table>";

        if ($Shopseite <= 1) {
            $Start = 0;
        } else {
            $Start = ($Shopseite * $Itemsperpage) - $Itemsperpage - 1;
        }
        //if ($Start > 0){$Start += 1;}
        $Limit = 20;
        if ($Itemkramsen > 0) {
            $Itemkramsen += 1;
        }
        $Start += $Itemkramsen;
        if ($KategorieShop == "Waffen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden != '' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%'  ORDER BY Name LIMIT $Start,50000";
        } elseif ($KategorieShop == "Rustungen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Rüstung != '' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name LIMIT $Start,50000";
        } elseif ($KategorieShop == "Taschen") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden = '' AND Rüstung = '' AND Platzdrin > '0' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name LIMIT $Start,50000";
        } elseif ($KategorieShop == "Sonstiges") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND Schaden = '' AND Rüstung = '' AND Platzdrin < '1' AND Abhängigkeit NOT LIKE '%gift%' AND Abhängigkeit NOT LIKE '%Heilkunde%' ORDER BY Name LIMIT $Start,50000";
        } elseif ($KategorieShop == "Gifte") {
            $sql = "SELECT * FROM Itemsk WHERE Wokaufen = 'Shop' AND Rang < '$Ranguser' AND (Abhängigkeit LIKE '%gift%' OR Abhängigkeit LIKE '%Heilkunde%') ORDER BY Name LIMIT $Start,50000";
        } else {
            $sql = "SELECT * FROM Itemsk WHERE id = '0'";
        }

        $query = mysql_query($sql);
        while ($row = mysql_fetch_array($query)) {
            $Itemid = $row["id"];
            $Name = $row["Name"];
            $ok = 0;
            if ($Name == "Sandflasche") {
                if ($object3->Sand == 1) {
                    $ok = 1;
                }
            } else {
                $ok = 1;
            }
            if ($row["Tabelle"] != "") {
                $cake = "SELECT * FROM $row[Tabelle] WHERE id = '$c_loged'";
                $cakes = mysql_query($cake);
                $Tabellenwert = mysql_fetch_object($cakes);
                $Spalte = $row["Spalte"];
                $Wertbraucht = str_replace("<=", "", $row["Wert"]);
                $Wertbraucht = str_replace(">=", "", $Wertbraucht);
                $Wertbraucht = str_replace(">", "", $Wertbraucht);
                $Wertbraucht = str_replace("<", "", $Wertbraucht);
                $Wertduhast = $Tabellenwert->$Spalte;
                $pos = strpos($row["Wert"], ">=");
                if ($pos === false) {
                    $pos = strpos($row["Wert"], "<=");
                    if ($pos === false) {
                        $pos = strpos($row["Wert"], ">");
                        if ($pos === false) {
                            $pos = strpos($row["Wert"], "<");
                            if ($pos === false) {
                                if ($Wertduhast == $Wertbraucht) {
                                } else {
                                    $ok = 0;
                                }
                            } else {
                                if ($Wertduhast < $Wertbraucht) {
                                } else {
                                    $ok = 0;
                                }
                            }
                        } else {
                            if ($Wertduhast > $Wertbraucht) {
                            } else {
                                $ok = 0;
                            }
                        }
                    } else {
                        if ($Wertduhast <= $Wertbraucht) {
                        } else {
                            $ok = 0;
                        }
                    }
                } else {
                    if ($Wertduhast >= $Wertbraucht) {
                    } else {
                        $ok = 0;
                    }
                }
            }
            if ($ok == 1) {
                $Beschreibung = $row["Beschreibung"];
                $itemId = $row["id"];
                $itemNiveau = $row["Niveau"];
                $Waffe = $row["Waffe"];
                $Waffenah = $row["Waffenah"];
                $Waffefern = $row["Waffefern"];
                $Waffenart = $row["Waffenart"];
                $Land = $row["Land"];
                $Clan = $row["Clan"];
                $Preis = $row["Kosten"];
                $darfste = 1;
                $katArray = [];
                if ($Land != "") {
                    $pos = strpos($Land, "$object->Heimatdorf");
                    if ($pos === false) {
                        $darfste = 0;
                    }
                }

                $sql = "SELECT * FROM itemKategorie WHERE itemID = '$itemId'";
                $queryKat = mysql_query($sql);
                while ($itemKat = mysql_fetch_object($queryKat)) {
                    $katArray[$itemKat->jutsuKategorie] = true;
                    if ($tps->thisItemOk($itemNiveau, $itemKat->jutsuKategorie) == false) {
                        $darfste = 0;
                    }
                }

                $Perso = $row['Perso'];
                $Useronly = $row['Useronly'];

                if ($Perso != "0") {
                    $Useronly = "%&%&%$Useronly%&%&%";
                    $pos = strpos($Useronly, ",$object->name%&%&%");
                    if ($pos === false) {
                        $pos = strpos($Useronly, "%&%&%$object->name%&%&%");
                        if ($pos === false) {
                            $pos = strpos($Useronly, "%&%&%$object->name,");
                            if ($pos === false) {
                                $pos = strpos($Useronly, ",$object->name,");
                                if ($pos === false) {
                                    $darfste = 0;
                                }
                            }
                        }
                    }
                }

                if ($row['Abhängigkeit'] != "") {
                    $abfrage2 = "SELECT * FROM Jutsuk WHERE id LIKE '$c_loged'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $Jutsuk = mysql_fetch_object($ergebnis2);
                    $abfrage2 = "SELECT * FROM Fähigkeiten WHERE id LIKE '$c_loged'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $Fähigkeiten = mysql_fetch_object($ergebnis2);
                    $SonZaehler = 0;
                    $Teiler = explode("&", $row['Abhängigkeit']);
                    $Teil = 0;
                    while ($Teiler[$Teil] != "") {
                        $Teiler2 = explode("%", $Teiler[$Teil]);

                        $Wert = $Teiler2[1];
                        $Wert2 = $Teiler2[2];

                        if ($Teiler2[0] == "Jutsu") {
                            $Werto = $Jutsuk->$Wert;
                            if ($Werto < $Wert2) {
                                $darfste = 0;
                            }
                        }

                        if ($Teiler2[0] == "Faehigkeit") {
                            $Werto = $Fähigkeiten->$Wert;
                            if ($Werto < $Wert2) {
                                $darfste = 0;
                            }
                        }

                        $Teil += 1;
                    }

                }

                if ($Clan != "") {
                    if ($Clan != $object->Clan) {
                        $darfste = 0;
                    }
                }
                if ($darfste == 1 and $Limit > 0) {
                    $Limit -= 1;
                    $Beschreibung = nl2br($Beschreibung);
                    $Wunden = $row["Wunden"];
                    echo "<table border='0' width='697' height='250' cellpadding='0' cellspacing='0' background='/layouts/Uebergang/Untergrund.png'>
                        <form method='POST' action='Shop.php?kauf=$Itemid&Savior=" . "$object_save_forms" . "'>
                        <tr>
                        <td background='Bilder/Infos/" . "$farbeforum" . "Shopzwischen.gif' width='15%' align='center'><img src='Bilder/Inventar/";
                    if ($row['Stackmenge'] < 1) {
                        echo "$row[Inventarbild]";
                    } else {
                        $Bildser = $row['Inventarbild'];
                        $Bild = str_replace("Itemzahl", "1", $Bildser);
                        echo "$Bild";
                    }
                    echo "'><br>$Name</td>
                        <td colspan='2' width='70%'>
                        <table border='0' width='100%'>
                        <tr>
                        <td background='Bilder/Infos/" . "$farbeforum" . "Shopzwischen.gif' width='50%'>&nbsp;$Beschreibung</td>
                        <td background='Bilder/Infos/" . "$farbeforum" . "Shopzwischen.gif' width='50%'>";
                    if ($Waffe != 1) {
                        if ($Wunden == "") {
                            echo "Siehe Beschreibung";
                        } else {
                            echo "$Wunden";
                        }
                    } else {
                        echo "$Wunden";
                    }
                    echo "</td>
                        </td>
                        </tr>";

                    if ($row['Schaden'] != "") {
                        echo "<tr>
                            <td colspan='2'>";
                        echo "<table border='0' width='90%'>
                            <tr>
                            <td colspan='10'><b><u>Waffendaten</u></b></td>
                            </tr>
                            <tr>
                            <td><b>Angriff</b></td>
                            <td><b>Schaden</b></td>
                            <td><b>EP</b></td>
                            <td><b>BP</b></td>
                            <td><b>Art</b></td>
                            </tr>";
                        $Teiler = explode("&", $row['Schaden']);
                        $Teil = 0;
                        while ($Teiler[$Teil] != "") {
                            $Teiles = explode("%", $Teiler[$Teil]);

                            echo "<tr>
                                <td>$Teiles[0]";
                            if ($Teiles[7] == 1) {
                                if ($Teiles[0] != "Schuss") {
                                    if ($Teiles[8] == "Gefächert") {
                                        echo " (Gefächert)";
                                    } else {
                                        echo " (Gebündelt)";
                                    }
                                }
                            }
                            echo "</td>
                                <td>$Teiles[1]";
                            $pos = strpos($Teiles[1], "Ausdauer");
                            if ($pos === false) {
                                echo "%";
                            }
                            echo "</td>
                                <td>$Teiles[2]</td>
                                <td>$Teiles[3]</td>
                                <td>$Teiles[4]</td>
                                </tr>";
                            $Teil += 1;
                        }

                        echo "</table>";


                        echo "</td>
                            </tr>";
                    }

                    if ($row['Rüstung'] != "") {
                        echo "<tr>
                            <td colspan='2'>";
                        echo "<table border='0' width='95%'>
                            <tr>
                            <td colspan='6'><b><u>Rüstungsdaten</u></b></td>
                            </tr>
                            <tr>
                            <td><b>Ausdauer:</b></td>
                            <td>$row[RustAusdauer]</td>
                            <td><b>pro Angriff:</b></td>
                            <td>$row[RustAusdauerpro]%</td>
                            <td><b>EP pro Angriff:</b></td>
                            <td>$row[EPab]</td>
                            </tr>
                            <tr>
                            <td><b>BP pro Angriff:</b></td>
                            <td>$row[BPab]</td>
                            <td colspan='4'></td>
                            </tr>

                            <tr>
                            <td colspan='8'><b>Schützt an:</b> (Zahl in Klammern = Chance, dass kein Schutz auftritt)</td>
                            </tr>
                            <tr>
                            <td colspan='8'>
                            <table border='0'>";
                        $SonZaehler = 0;
                        $Teiler = explode("&", $row['Rüstung']);
                        $Teil = 0;
                        while ($Teiler[$Teil] != "") {
                            $Teiles = explode("%", $Teiler[$Teil]);
                            if ($SonZaehler == 0) {
                                echo "<tr>";
                                $SonZaehler += 1;
                            } else {
                                $SonZaehler += 1;
                            }
                            echo "
                                <td width='50%'>$Teiles[0] $Teiles[5] ($Teiles[4]%)</td>
                                ";
                            if ($SonZaehler == 2) {
                                echo "</tr>";
                                $SonZaehler = 0;
                            }

                            $Teil += 1;
                        }
                        if ($SonZaehler != 0) {
                            echo "</tr>";
                        }
                        echo "</table>
                            </td>
                            </tr>";
                        echo "</table>";


                        echo "</td>
                            </tr>";
                    }

                    echo "<tr>";
                    echo "<td colspan='2'>";

                    if ($row['Stackmenge'] > 1) {
                        echo "<table border='0'>";
                        echo "<tr>";
                        echo "<td><b>Stapelbar</b></td>";
                        echo "<td>x$row[Stackmenge]</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                    if ($row['Ort1'] != "") {
                        echo "<b>Anlegbar an:</b>";
                        echo "<table border='0' width='100%'>";
                        echo "<tr>";
                        if ($row['Ort1'] != "") {
                            echo "<td>$row[Ort1]</td>";
                        }
                        if ($row['Ort2'] != "") {
                            echo "<td>$row[Ort2]</td>";
                        }
                        if ($row['Ort3'] != "") {
                            echo "<td>$row[Ort3]</td>";
                        }
                        if ($row['Ort4'] != "") {
                            echo "<td>$row[Ort4]</td>";
                        }
                        if ($row['Ort5'] != "") {
                            echo "<td>$row[Ort5]</td>";
                        }
                        echo "</tr>";
                        echo "</table>";
                    }
                    if ($row['Tieranleg'] != '') {
                        echo '<b>Inuzukatier: anlegbar an:</b><br>';
                        $tierOrte = str_replace('|', ',', $row['Tieranleg']);
                        $tierOrte = str_replace(',,', ',', $tierOrte);
                        $tierOrte = substr($tierOrte, 1, strlen($tierOrte) - 2);
                        echo $tierOrte . '<br>';
                    }

                    echo "</td>";
                    echo "</tr>";

                    echo "</table>
                        <td width='15%'>";

                    //Itemkategorie

                    if (!empty($katArray)) {
                        echo "<b>Kategorie:</b><br>";
                        foreach ($katArray as $katName => $katValue) {
                            echo $katName . "<br>";
                        }
                    }
                    echo "<br>";
                    $TP = $tps->howMuchIsThisItem($row['id'], 0, $object->id, 0);
                    $tpMon = [1 => 0, 2 => 6, 3 => 8, 4 => 10];
                    $Niveau = [0 => 'E', 1 => 'D', 2 => 'C', 3 => 'B', 4 => 'A', 5 => 'S'];
                    echo 'Niveau: ' . $Niveau[$row['Niveau']] . '<br>';
                    $vorgQuer = "SELECT iK.`Name` as Name,iK.`id` as ID FROM `itemVorg` iV LEFT JOIN `Itemsk` iK ON iV.`vId` = iK.`id` WHERE iV.`iId` = '" . $row['id'] . "'";
                    $vorgQuer = mysql_query($vorgQuer);
                    $count = 0;
                    while ($vorg = mysql_fetch_array($vorgQuer)) {
                        if ($count == 0) {
                            echo '<b>Vorgänger</b><br/>';
                        }
                        if ($count > 0) {
                            echo ',';
                        }
                        echo $vorg['Name'];
                        $count++;
                    }
                    echo '<br>';
                    $nachQuer = "SELECT iK.`Name` as Name,iK.`id` as ID FROM `itemVorg` iV LEFT JOIN `Itemsk` iK ON iV.`iId` = iK.`id` WHERE iV.`vId` = '" . $row['id'] . "'";
                    $nachQuer = mysql_query($nachQuer);
                    $count = 0;
                    while ($nach = mysql_fetch_array($nachQuer)) {
                        if ($count == 0) {
                            echo '<b>Nachfolger</b><br/>';
                        }
                        if ($count > 0) {
                            echo ',';
                        }
                        echo $nach['Name'];
                        $count++;
                    }
                    echo '<br>';
                    $abfrage2 = "SELECT COUNT(*) FROM itemFaeh WHERE uId = '$c_loged' AND iId = '$row[id]'";
                    $ergebnis2 = mysql_query($abfrage2);
                    $beherrscht = mysql_fetch_row($ergebnis2);
                    $beherrscht = $beherrscht[0];
                    if ($beherrscht >= 1) {
                        $TP = 0;
                        echo 'beherrscht<br>';
                    } else {
                        echo 'Zum Beherrschen: ' . $TP . ' TP<br>';
                    }
                    echo $Preis . ' Ryô<br>pro Stk.<br>';
                    if ($geld < $Preis) {
                        $ben = $Preis - $geld;
                        echo "Zu teuer<br />(Benötigt:" . $ben . ")";
                    } elseif ($TP > ($object->Trainingspunkte + $tpMon[$object->Niveau]) && $TP != 0) {
                        echo "Nicht genügend TP";
                    } elseif ($TP > (($object->CenterPunkte * 2) * $tpMon[$object->Niveau]) && $TP != 0) {
                        echo "Nicht genügend CP";
                    } else {
                        echo "<select name='Menge'>";
                        $Zahl = 0;
                        while ($Zahl != 25) {
                            $Zahl += 1;
                            $Preise = (int)$row["Kosten"];
                            $Preise *= $Zahl;
                            if ($Preise > $geld) {
                                $Zahl = 25;
                            } else {
                                echo "<option>$Zahl";
                            }
                        }
                        echo "</select><input type='submit' value='Kaufen'>";
                    }
                    echo "</td>
                        </form></tr>";
                    $itemViewModel->NewItem($row['id']);
                    echo "<tr><td colspan='3'><b>Effekte:</b><br>";
                    if ($itemViewModel->item != null) {
                        $itemViewModel->GetItemEffects();
                        if (count($itemViewModel->effects) > 0) {
                            ?>
                            <table class='withGroups' width="100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Beschreibung</th>
                                    <th>Kosten</th>
                                    <th>Rang</th>
                                    <th>Freie Aktion</th>
                                </tr>
                                <?php
                                $punkte = 0;
                            $connGroup = 0;
                            $groupCosts = [1 => [], 2 => [], 3 => []];
                            foreach ($itemViewModel->effects as $effect) {
                                $tdClass = "";
                                if ($connGroup != $effect->connectionGroup || $connGroup == 0) {
                                    $punkte += $effectViewModel->costsEcho($groupCosts, $connGroup);
                                    $groupCosts = [1 => [], 2 => [], 3 => []];
                                    if ($connGroup == 0 && $connGroup == $effect->connectionGroup) {
                                        $tdClass = " class='lastCol'";
                                    }
                                }
                                ?>
                                    <tr>
                                        <td<?php echo $tdClass; ?> align="center">
                                            <?php echo $effect->Name; ?>
                                        </td>
                                        <td<?php echo $tdClass; ?> ><?php echo $effect->Description; ?></td>
                                        <td<?php echo $tdClass; ?> align="center"><?php
                                if ($effect->IsAdvantage != 1 && $effect->kindOfCosts == 1) {
                                    $effect->Costs = -$effect->Costs;
                                }
                                if ($effect->kindOfCosts == 2 && $effect->affectAll == 1) {
                                    $effect->kindOfCosts = 3;
                                }
                                $groupCosts[$effect->kindOfCosts][] = $effect->Costs;
                                $connGroup = $effect->connectionGroup;
                                if ($effect->connectionGroup == 0) {
                                    echo $effect->Costs . $costs[$effect->kindOfCosts];
                                } ?></td>
                                        <td<?php echo $tdClass; ?> align="center"><?php echo $effect->Rank; ?></td>
                                        <td<?php echo $tdClass; ?>
                                            align="center"><?php echo $effect->freeAction; ?></td>
                                    </tr>
                                <?php }
                            $punkte += $effectViewModel->costsEcho($groupCosts, $connGroup);
                            ?>
                                <tr>
                                    <td align="center">
                                    </td>
                                    <td></td>
                                    <td align="center">Gesamt:</td>
                                    <td align="center"><?php
                                    echo $punkte; ?></td>
                                </tr>
                            </table>
                            <?php
                        }
                    }

                    echo "</td>
                </tr>";

                    echo "</table><br><br>";
                }
            }
        }
        echo "<table border='0' width='100%'>";
        echo "<tr>";
        echo "<td>";
        if ($Shopseite > 1) {
            $Page = $Shopseite - 1;
            echo "<a href='Shop.php?Shopseite=$Page&KategorieShop=$KategorieShop'>";
            echo "<<<";
            echo "</a>";
        }
        echo "</td>";
        echo "<td colspan='2'><center>";
        $Nummer = 0;
        while ($Nummer < $Seiten) {
            $Nummer += 1;
            if ($Nummer > 1) {
                echo ", ";
            }
            if ($Nummer == $Shopseite) {
                echo "<b>[$Nummer]</b>";
            } else {
                echo "<a href='Shop.php?Shopseite=$Nummer&KategorieShop=$KategorieShop'>$Nummer</a>";
            }
        }
        echo "</center></td>";
        echo "<td>";
        if ($Shopseite < $Seiten) {
            $Page = $Shopseite + 1;
            echo "<a href='Shop.php?Shopseite=$Page&KategorieShop=$KategorieShop'>";
            echo ">>>";
            echo "</a>";
        }
        echo "</td>";
        echo "</tr>";
        echo "</table>";
    }
}
echo "</td></tr></table>";

get_footer();
