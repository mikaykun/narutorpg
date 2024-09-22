<?php

include __DIR__ . '/../Menus/layout1.inc';

if (is_user_logged_in()) {
    ?>
    <script>
        function show(id) {
            if (document.getElementById(id).style.display == 'block') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'block';
            }
        }

        function shows(id) {
            if (document.getElementById(id).style.display == 'block') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'block';
            }
        }
    </script>
    <?php
    $dorfs = nrpg_get_current_user();
    $c_loged = $_COOKIE['c_loged'];
    $NachAktionen = $_POST['NachAktionen'] ?? '';
    $Artnachrichten = $_GET['Artnachrichten'] ?? '';
    $Order = $_GET['Order'] ?? '';

    // set readInboxStatus to 1 so the link doesn't appear red anymore
    mysql_query("UPDATE user SET readInboxStatus = '1' WHERE id = {$dorfs->id}");

    $Textunten = "";
    if ($NachAktionen == "del_msgsngg") {
        if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
            $sql = "SELECT id FROM Posteingang WHERE An = '$dorfs->id'";
        } else {
            $sql = "SELECT id FROM Postausgang WHERE Von = '$dorfs->id'";
        }
        $query = mysql_query($sql);
        while ($Mitteilung = mysql_fetch_object($query)) {
            $Werter = "Nachricht$Mitteilung->id";
            $Werters = $_POST[$Werter];
            if ($Werters == 1) {
                if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
                    $del = "DELETE FROM Posteingang WHERE id = '$Mitteilung->id'";
                } else {
                    $del = "DELETE FROM Postausgang WHERE id = '$Mitteilung->id'";
                }
                $del = mysql_query($del);
            }
        }
        $Textunten = "Es wurden alle ausgewählten Nachrichten gelöscht!<br><br>";
    }

    if ($NachAktionen == "Ordner") {
        $Ordnername = htmlentities((string) $Ordnername);
        $Ordnername = str_replace("'", "\"", $Ordnername);
        if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
            $sql = "SELECT id FROM Posteingang WHERE An = '$dorfs->id'";
        } else {
            $sql = "SELECT id FROM Postausgang WHERE Von = '$dorfs->id'";
        }
        $query = mysql_query($sql);
        while ($Mitteilung = mysql_fetch_object($query)) {
            $Werter = "Nachricht$Mitteilung->id";
            $Werters = $_POST[$Werter];
            if ($Werters == 1) {
                if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
                    $del = "UPDATE Posteingang SET Ordner = '$Ordnername' WHERE id = '$Mitteilung->id'";
                } else {
                    $del = "UPDATE Postausgang SET Ordner = '$Ordnername' WHERE id = '$Mitteilung->id'";
                }
                $del = mysql_query($del);
            }
        }
        $Textunten = "Die gewählten Nachrichten wurden in den Ordner \"$Ordnername\" verschoben!<br><br>";
    }

    if (isset($_GET['del_all'])) {
        if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
            $Textunten = "Alle Nachrichten im Posteingang wurden gelöscht!<br><br>";
            $loeschen = "DELETE FROM Posteingang WHERE An = '$dorfs->id'";
        } else {
            $Textunten = "Alle Nachrichten im Postausgang wurden gelöscht!<br><br>";
            $loeschen = "DELETE FROM Postausgang WHERE Von = '$dorfs->id'";
        }
        $loesch = mysql_query($loeschen) or die("Löschen der PM fehlgeschlagen!");
    }

    if (isset($_GET['pmiid'])) {
        if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
            $sql = "SELECT An, Gelesen, id, Von, Text, Datum, Betreff, Logleser FROM Posteingang WHERE id = '{$_GET['pmiid']}' AND An = '$c_loged'";
        } else {
            $sql = "SELECT An, id, Von, Text, Datum, Betreff, Logleser FROM Postausgang WHERE id = '{$_GET['pmiid']}' AND Von = '$c_loged'";
        }
        $u_dat = mysql_query($sql) or die("Invalid query");
        $row = mysql_fetch_object($u_dat);

        if (is_object($row) && $row->id > 0) {
            $read = $row->Gelesen;
            if ($read != "1" && ($Artnachrichten == "Posteingang" or $Artnachrichten == "")) {
                mysql_query("UPDATE Posteingang SET Gelesen = '1' WHERE id = '$_GET[pmiid]'") or die("Fehler!");
            }
            $pmid = $row->id;
            if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
                $Vons = $row->Von;
                $Vonis = "Von: ";
                $Artnachrichten = "Posteingang";
            } else {
                $Vons = $row->An;
                $Vonis = "An: ";
            }
            $sql = "SELECT id, name FROM user WHERE id = '$Vons'";
            $query = mysql_query($sql) or die("Fehler!1");
            $userobject = mysql_fetch_object($query);
            if (isset($userobject->id) && $userobject->id > 0) {
                $Von = "<a href='userpopup.php?usernam=$userobject->name'>$userobject->name</a>";
            } elseif ($Vons == "System" || $Vons == 0) {
                $Von = "System";
            } elseif ($Vons == "Adminbriefkasten") {
                $Von = "Adminbriefkasten";
            } else {
                $Von = "$Von";
            }
            $Text = $row->Text;
            $anzahl = 0;
            $anzahl2 = 0;
            $Text = nl2br($Text);
            $Text = convert_bbcode_basic($Text);
            $Text = convert_bbcode_spoiler($Text);
            $Text = convert_bbcode_fonts($Text);
            $Text = convert_bbcode_quote($Text);
            $Textunten = "<table border='0' width='90%'>
				<tr>
				<td width='10%'><b>$Vonis</b></td>
				<td>$Von</td>
				</tr>
				<tr>
				<td width='10%'><b>Datum:</b></td>
				<td>$row->Datum</td>
				</tr>
				<tr>
				<td><b>Betreff:</b></td>
				<td><b>$row->Betreff</b></td>
				</tr>
				<tr>
				<td colspan='2'>
				$Text
				</td>
				</tr>
				</table>
				<br><br><a href='Nachrichten2.php?Antworte=$pmid'>Antworten</a> - <a href='Nachrichten2.php?Antworte=$pmid&Zitierauch=1'>Antworten & zitieren</a><br><br><hr>";
        } else {
            $Textunten = "Diese Message ist nicht an dich gerichtet!<br>";
        }
    }

    echo "$Textunten";
    echo "<a href='Adminbriefkasten.php'>Nachricht an einen Administrator verfassen</a> - <a href='Nachrichten2.php'>Nachricht verfassen</a><br><br>";
    echo "<br><center>";
    if ($Artnachrichten == "Posteingang" || $Artnachrichten == "") {
        echo "<b>Posteingang</b> - <a href='?Artnachrichten=Postausgang'>Postausgang</a><br><br>";
    } else {
        echo "<a href='?Artnachrichten=Posteingang'>Posteingang</a> - <b>Postausgang</b><br><br>";
    }
    echo "</center><br>";

    echo '<form method="POST" action="Nachrichten.php?Artnachrichten=' . $Artnachrichten . '">';

    if ($Artnachrichten == "Posteingang" || $Artnachrichten == "") {
        echo "<table border='0' bordercolor='#0783F8' cellspacing='0' cellpadding='0' width='80%' width='100%'><tr>";

        if ($Order != "") {
            echo "<td width='15%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "An") {
                echo "<a href='?Artnachrichten=$Artnachrichten&Order=An'><b>Von</b></a>";
            } else {
                echo "<b>Von</b>";
            }
            echo "</td>
				<td width='55%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "Betreff") {
                echo "<a href='?Artnachrichten=$Artnachrichten&Order=Betreff'><b>Betreff</b></a>";
            } else {
                echo "<b>Betreff</b>";
            }
            echo "</td>
				<td width='20%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "") {
                echo "<a href='?Artnachrichten=$Artnachrichten'><b>Datum</b></a>";
            } else {
                echo "<b>Datum</b>";
            }
            echo "</td>

				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Aktion</b></td>
				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Löschen</b></td>
				</tr>";
        }
    }
    else {
        echo "<table border='0' bordercolor='#0783F8' cellspacing='0' cellpadding='0' width='80%'>";

        if ($Order != "") {
            echo "<tr>
				<td width='15%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "An") {
                echo "<a href='?Artnachrichten=$Artnachrichten&Order=An'><b>Von</b></a>";
            } else {
                echo "<b>Von</b>";
            }
            echo "</td>
				<td width='55%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "Betreff") {
                echo "<a href='?Artnachrichten=$Artnachrichten&Order=Betreff'><b>Betreff</b></a>";
            } else {
                echo "<b>Betreff</b>";
            }
            echo "</td>
				<td width='20%' background='/layouts/Uebergang/Oben.png'>";
            if ($Order != "") {
                echo "<a href='?Artnachrichten=$Artnachrichten'><b>Datum</b></a>";
            } else {
                echo "<b>Datum</b>";
            }
            echo "</td>
				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Aktion</b></td>
				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Löschen</b></td>
				</tr>";
        }

    }

    $Nummer = 1;

    if ($Order == "An") {
        $ORDERN = "Name, id DESC";
    } elseif ($Order == "Betreff") {
        $ORDERN = "Betreff, id DESC";
    } else {
        $ORDERN = "Ordner, id DESC";
    }

    if ($Artnachrichten == "Posteingang" || $Artnachrichten == "") {
        $sql = "SELECT * FROM Posteingang WHERE An = '$c_loged' ORDER BY {$ORDERN}";
    } else {
        $sql = "SELECT * FROM Postausgang WHERE Von = '$c_loged' ORDER BY {$ORDERN}";
    }
    $u_dat = mysql_query($sql) or die("Invalid query");

    $Lastordner = "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%";

    while ($row = mysql_fetch_array($u_dat, MYSQL_ASSOC)) {

        if ($Lastordner != $row['Ordner'] and $Order != "An" and $Order != "Betreff") {
            if ($Lastordner != "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%") {
                echo "</table></td></tr></div>";
                echo "<tr>
					<td colspan='5' background='/layouts/Uebergang/Oben2.png'><a href=\"javascript:show('Ordner$row[Ordner]');\">$row[Ordner]</a></td>
					</tr>";
                echo "
					<tr><td colspan='4'>
					<div id='Ordner$row[Ordner]' style='display:none'>
					<table border='0'>
					<tr>
					<td width='15%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "An") {
                    echo "<a href='?Artnachrichten=$Artnachrichten&Order=An'><b>Von</b></a>";
                } else {
                    echo "<b>Von</b>";
                }
                echo "</td>
					<td width='55%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "Betreff") {
                    echo "<a href='?Artnachrichten=$Artnachrichten&Order=Betreff'><b>Betreff</b></a>";
                } else {
                    echo "<b>Betreff</b>";
                }
                echo "</td>
					<td width='20%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "") {
                    echo "<a href='?Artnachrichten=$Artnachrichten'><b>Datum</b></a>";
                } else {
                    echo "<b>Datum</b>";
                }
                echo "</td>

					<td width='5%' background='/layouts/Uebergang/Oben.png'><b>Aktion</b></td>
				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Löschen</b></td>
					</tr>";
                $Lastordner = "$row[Ordner]";
            } else {
                echo "<tr>
					<td colspan='5' background='/layouts/Uebergang/Oben2.png'><a href=\"javascript:show('OrdnerKeinOrdneristdashier');\">Kein Ordner</a></td>
					</tr>";
                echo "
					<tr><td colspan='4'>
					<div id='OrdnerKeinOrdneristdashier' style='display:block'>
					<table border='0'>
					<tr>
					<td width='15%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "An") {
                    echo "<a href='?Artnachrichten=$Artnachrichten&Order=An'><b>Von</b></a>";
                } else {
                    echo "<b>Von</b>";
                }
                echo "</td>
					<td width='55%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "Betreff") {
                    echo "<a href='?Artnachrichten=$Artnachrichten&Order=Betreff'><b>Betreff</b></a>";
                } else {
                    echo "<b>Betreff</b>";
                }
                echo "</td>
					<td width='20%' background='/layouts/Uebergang/Oben.png'>";
                if ($Order != "") {
                    echo "<a href='?Artnachrichten=$Artnachrichten'><b>Datum</b></a>";
                } else {
                    echo "<b>Datum</b>";
                }
                echo "</td>

					<td width='5%' background='/layouts/Uebergang/Oben.png'><b>Aktion</b></td>
				<td width='15%' background='/layouts/Uebergang/Oben.png'><b>Löschen</b></td>
					</tr>";
                $Lastordner = "$row[Ordner]";
            }
        }

        echo "<tr>";
        $pmid = $row["id"];
        $Nummer += 1;
        if ($Nummer == 2) {
            $Farbe  = " bgcolor='#0783F8'";
            $Nummer = 0;
        } else {
            $Farbe = "";
        }

        echo "<td width='25%'>";
        if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
            $Von = $row["Von"];
            $Artnachrichten = "Posteingang";
        } else {
            $Von = $row["An"];
        }
        if ($Von == "System") {
            echo "System";
            if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
                $up = "UPDATE Posteingang SET Name = 'System' WHERE id = '$row[id]'";
            } else {
                $up = "UPDATE Postausgang SET Name = 'System' WHERE id = '$row[id]'";
            }
            mysql_query($up);
        } elseif ($Von == "Adminbriefkasten") {
            echo $Von;
        } else {
            $sql = "SELECT id, name FROM user WHERE id = '$Von'";
            $query = mysql_query($sql) or die("Fehler!1");
            $userobject = mysql_fetch_object($query);
            if (is_object($userobject)) {
                echo "<a href='userpopup.php?usernam=$userobject->name'>$userobject->name</a>";
                if ($row['Name'] == "") {
                    if ($Artnachrichten == "Posteingang" or $Artnachrichten == "") {
                        $up = "UPDATE Posteingang SET Name = '$userobject->name' WHERE id = '$row[id]'";
                    } else {
                        $up = "UPDATE Postausgang SET Name = '$userobject->name' WHERE id = '$row[id]'";
                    }
                    mysql_query($up);
                }
            }
        }
        echo "</td>";
        echo "<td width='25%'>";
        echo "<A href='Nachrichten.php?pmiid=$pmid&Artnachrichten=$Artnachrichten'>";
        if ($row["Gelesen"] == "0") {
            echo "<b>";
        }
        if ($row["Betreff"] == "") {
            echo "Kein Betreff";
        } else {
            echo $row["Betreff"];
        }
        if ($row["Gelesen"] == "0") {
            echo "</b>";
        }
        echo "</a>";
        echo "</td>";
        echo "<td width='25%'>";
        echo $row["Datum"];
        echo "</td>";
        echo "<td width='0'><input type='checkbox' name='Nachricht$pmid' value='1'></td>";
        echo "<td width='0'><input type='submit' value='Löschen'></td>";
        echo "</tr>";
    }
    if ($Lastordner != "%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%") {
        echo "</table></div>";
    }
    echo "<tr><td colspan='5' align='right'>
	<input type='text' name='Ordnername' value='Ordnername'>
	<select name='NachAktionen'>
	<option value='del_msgsngg'>Löschen
	<option value='Ordner'>Ordner zuweisen
	</select><br>
	<input type='submit' value='durchführen'></td></tr>";
    echo "</table></form>";

    echo "<a href='Nachrichten.php?del_all=1&Artnachrichten=$Artnachrichten'>Alle Nachrichten löschen</a>";
} else {
    echo 'Du hast keinen Zugang zu dieser Seite.';
}

get_footer();
