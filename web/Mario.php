<?php

include(__DIR__ . "/../Menus/layout1.inc");

$werteEnd = new tageZuWerte();

echo "<script type=\"text/javascript\" src=\"/Sonstiges/overlib421/overlib.js\"><!-- overLIB (c) Erik Bosrup --></script>
<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div> ";

if ($dorfs2->Clan == "Ningyosenshu Clan" or $dorfs->admin >= 3) {
    if ($Verwalt) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Verwalt' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Puppe = mysql_fetch_object($query);
        echo "<b><u>$Puppe->Name</u></b><br><a href='Mario.php'>Zurück</a><br><br>";

        echo "<table border='0'>";
        echo "<tr>";
        echo "<td align='center'><b>Stärke</b></td>";
        echo "<td align='center'><b>Verteidigung</b></td>";
        echo "<td align='center'><b>Geschwindigkeit</b></td>";
        echo "<td align='center'><b>Haltbarkeit</b></td>";
        echo "</tr>";
        echo "<tr>";
        $Wert = round($dorfs2->PuppeWerte * $Puppe->Strmulti, 2);
        echo "<td align='center'>$Wert</td>";
        $Wert = round($dorfs2->PuppeWerte * $Puppe->Vertmulti, 2);
        echo "<td align='center'>$Wert</td>";
        $Wert = round($dorfs2->PuppeWerte * $Puppe->Gesmulti, 2);
        echo "<td align='center'>$Wert</td>";
        echo "<td align='center'>$Puppe->Haltbarkeit/$Puppe->Haltbarkeitmax</td>";
        echo "</tr>";
        echo "</table>";

        if ($Puppe->Art == 'Zweibeinig') {
            $Wert = 722 / 2 - 325;

            echo "<table border='0'>";
            echo "<tr>";
            echo "<td width='722' height='650' align='center' valign='top'>";
            echo "<div style=\"position:relative;\">";

            echo "<div style=\"position:absolute; left:$Wert" . "px; top:0" . "px; z-index: 1;\" align='center'>";
            echo "<img src='/Bilder/Inventar/Grundlagen/Zweibeinig.png'>";
            echo "</div>";

            $Platzanzahl = 0;

            while ($Platzanzahl <= 29) {
                if ($Platzanzahl == 0) {
                    $OrtName = "Kopf";
                    $Links = 722 / 2 + 5;
                    $Oben = 160;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 1) {
                    $OrtName = "Hals";
                    $Links = 722 / 2 + 5;
                    $Oben = 230;
                    $Art = "2x1";
                }
                if ($Platzanzahl == 2) {
                    $OrtName = "Bauch";
                    $Links = 722 / 2 + 5;
                    $Oben = 330;
                    $Art = "2x2";
                }
                if ($Platzanzahl == 3) {
                    $OrtName = "Taille";
                    $Links = 722 / 2 + 5 - 32;
                    $Oben = 430;
                    $Art = "2x1";
                }
                if ($Platzanzahl == 4) {
                    $OrtName = "Handlinksoben";
                    $Links = 722 / 2 + 165;
                    $Oben = 50;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 5) {
                    $OrtName = "Gelenklinksoben";
                    $Links = 722 / 2 + 150;
                    $Oben = 150;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 6) {
                    $OrtName = "Armlinksoben";
                    $Links = 722 / 2 + 125;
                    $Oben = 210;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 7) {
                    $OrtName = "Handrechtsoben";
                    $Links = 722 / 2 - 165;
                    $Oben = 90;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 8) {
                    $OrtName = "Gelenkrechtsoben";
                    $Links = 722 / 2 - 130;
                    $Oben = 150;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 9) {
                    $OrtName = "Armrechtsoben";
                    $Links = 722 / 2 - 115;
                    $Oben = 210;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 10) {
                    $OrtName = "Handlinksmitte";
                    $Links = 722 / 2 + 275;
                    $Oben = 200;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 11) {
                    $OrtName = "Gelenklinksmitte";
                    $Links = 722 / 2 + 200;
                    $Oben = 250;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 12) {
                    $OrtName = "Armlinksmitte";
                    $Links = 722 / 2 + 125;
                    $Oben = 300;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 13) {
                    $OrtName = "Handrechtsmitte";
                    $Links = 722 / 2 - 260;
                    $Oben = 225;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 14) {
                    $OrtName = "Gelenkrechtsmitte";
                    $Links = 722 / 2 - 180;
                    $Oben = 270;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 15) {
                    $OrtName = "Armrechtsmitte";
                    $Links = 722 / 2 - 115;
                    $Oben = 300;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 16) {
                    $OrtName = "Handlinksunten";
                    $Links = 722 / 2 + 235;
                    $Oben = 475;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 17) {
                    $OrtName = "Gelenklinksunten";
                    $Links = 722 / 2 + 185;
                    $Oben = 450;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 18) {
                    $OrtName = "Armlinksunten";
                    $Links = 722 / 2 + 125;
                    $Oben = 400;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 19) {
                    $OrtName = "Handrechtsunten";
                    $Links = 722 / 2 - 220;
                    $Oben = 500;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 20) {
                    $OrtName = "Gelenkrechtsunten";
                    $Links = 722 / 2 - 165;
                    $Oben = 460;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 21) {
                    $OrtName = "Armrechtsunten";
                    $Links = 722 / 2 - 115;
                    $Oben = 400;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 22) {
                    $OrtName = "Beinlinks";
                    $Links = 722 / 2 + 60;
                    $Oben = 500;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 23) {
                    $OrtName = "Gelenklinks";
                    $Links = 722 / 2 + 50;
                    $Oben = 550;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 24) {
                    $OrtName = "Fußlinks";
                    $Links = 722 / 2 + 75;
                    $Oben = 600;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 25) {
                    $OrtName = "Beinrechts";
                    $Links = 722 / 2 - 40;
                    $Oben = 500;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 26) {
                    $OrtName = "Gelenkrechts";
                    $Links = 722 / 2 - 30;
                    $Oben = 550;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 27) {
                    $OrtName = "Fußrechts";
                    $Links = 722 / 2 - 55;
                    $Oben = 600;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 28) {
                    $OrtName = "Spezial1";
                    $Links = 722 / 2 - 35;
                    $Oben = 50;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 29) {
                    $OrtName = "Spezial2";
                    $Links = 722 / 2 + 45;
                    $Oben = 50;
                    $Art = "1x1";
                }

                if ($Art == "1x1") {
                    $Breite = 65;
                    $Hoehe = 65;
                }
                if ($Art == "1x2") {
                    $Breite = 65;
                    $Hoehe = 130;
                }
                if ($Art == "1x3") {
                    $Breite = 65;
                    $Hoehe = 195;
                }
                if ($Art == "2x1") {
                    $Breite = 130;
                    $Hoehe = 65;
                }
                if ($Art == "2x2") {
                    $Breite = 130;
                    $Hoehe = 130;
                }
                if ($Art == "0x0") {
                    $Breite = 30;
                    $Hoehe = 30;
                    $Art = "1x1";
                }
                if ($Art == "1x0") {
                    $Breite = 65;
                    $Hoehe = 30;
                    $Art = "1x1";
                }
                if ($Art == "0x1") {
                    $Breite = 30;
                    $Hoehe = 65;
                    $Art = "1x1";
                }

                $LinksA = $Links - floor($Breite / 2);
                $ObenA = $Oben - floor($Hoehe / 2);

                $Ortvari = $OrtName;
                echo "<a href='Mario.php?Modifizieren=$Ortvari&Puppe=$Puppe->id'>";
                if ($Puppe->$Ortvari == "N.V.") {
                    $Itemanzeigerdat = "<u><b>$OrtName - Nicht freigeschaltet</u></b>";

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 2;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";

                    $Itemart->BildX = 65;
                    $Itemart->BildX = 65;

                    $DiffX = $Itemart->BildX / $Breite;
                    $DiffY = $Itemart->BildY / $Hoehe;

                    if ($DiffX > $DiffY and $DiffX > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffX);
                        $HoeheItem = floor($Itemart->BildY / $DiffX);
                    } elseif ($DiffY > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffY);
                        $HoeheItem = floor($Itemart->BildY / $DiffY);
                    } else {
                        $BreitItem = $Itemart->BildX;
                        $HoeheItem = $Itemart->BildY;
                    }
                    $PosX = ($Breite - $BreitItem) / 2;
                    $PosX = floor($PosX);
                    $PosY = ($Hoehe - $HoeheItem) / 2;
                    $PosY = floor($PosY);


                    $BreiteA = $BreitItem;
                    $HoeheA = $HoeheItem;

                    $LinksA = $LinksA + $PosX;
                    $ObenA = $ObenA + $PosY;

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 3;\" align='center'>";

                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";

                    echo "<img border='0' src='Bilder/Inventar/Grundlagen/NichtFrei.png' alt='$Itemart->Name' width='$BreiteA' height='$HoeheA'>";

                    echo "</a>";

                    echo "</div>";
                } elseif ($Puppe->$Ortvari != "") {
                    $Teil = $Puppe->$Ortvari;
                    $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Teil' AND Artpuppe = 'Zweibeinig'";
                    $query = mysql_query($sql);
                    $Itemart = mysql_fetch_object($query);
                    $Variablekurz = str_replace(" ", "", $Itemart->Name);
                    $Variablekurz = str_replace("\"", "", $Variablekurz);
                    $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                    $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                    $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                    $Variablekurz = str_replace("ß", "ss", $Variablekurz);


                    $Itemart->BildX = 65;
                    $Itemart->BildX = 65;

                    $DiffX = $Itemart->BildX / $Breite;
                    $DiffY = $Itemart->BildY / $Hoehe;

                    if ($DiffX > $DiffY and $DiffX > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffX);
                        $HoeheItem = floor($Itemart->BildY / $DiffX);
                    } elseif ($DiffY > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffY);
                        $HoeheItem = floor($Itemart->BildY / $DiffY);
                    } else {
                        $BreitItem = $Itemart->BildX;
                        $HoeheItem = $Itemart->BildY;
                    }
                    $PosX = ($Breite - $BreitItem) / 2;
                    $PosX = floor($PosX);
                    $PosY = ($Hoehe - $HoeheItem) / 2;
                    $PosY = floor($PosY);


                    $BreiteA = $BreitItem;
                    $HoeheA = $HoeheItem;

                    $LinksA = $LinksA + $PosX;
                    $ObenA = $ObenA + $PosY;

                    $Itemanzeigerdat = "<u><b>$OrtName - $Itemart->Name</u></b>";

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 1;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";


                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 2;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='$Teil->Bild' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";
                } else {
                    $Itemanzeigerdat = "<u><b>$OrtName</u></b>";
                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 1;\" align='center'>";

                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";

                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";

                    echo "</a>";

                    echo "</div>";
                }

                $Platzanzahl += 1;
            }

            echo "</div>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        }
        if ($Puppe->Art == 'Mehrbeinig') {
            $Wert = 722 / 2 - 325;

            echo "<table border='0'>";
            echo "<tr>";
            echo "<td width='722' height='650' align='center' valign='top'>";
            echo "<div style=\"position:relative;\">";

            echo "<div style=\"position:absolute; left:$Wert" . "px; top:0" . "px; z-index: 1;\" align='center'>";
            echo "<img src='/Bilder/Inventar/Grundlagen/Mehrbeinig.png'>";
            echo "</div>";

            $Platzanzahl = 0;

            while ($Platzanzahl <= 17) {
                if ($Platzanzahl == 0) {
                    $OrtName = "Kopf";
                    $Links = 100;
                    $Oben = 400;
                    $Art = "2x1";
                }
                if ($Platzanzahl == 1) {
                    $OrtName = "Hals";
                    $Links = 225;
                    $Oben = 320;
                    $Art = "2x1";
                }
                if ($Platzanzahl == 2) {
                    $OrtName = "Bauch";
                    $Links = 722 / 2 + 5;
                    $Oben = 270;
                    $Art = "2x2";
                }

                if ($Platzanzahl == 3) {
                    $OrtName = "Handlinksoben";
                    $Links = 722 / 2 - 110;
                    $Oben = 200;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 4) {
                    $OrtName = "Gelenklinksoben";
                    $Links = 722 / 2 - 95;
                    $Oben = 148;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 5) {
                    $OrtName = "Armlinksoben";
                    $Links = 722 / 2 - 40;
                    $Oben = 165;
                    $Art = "1x1";
                }

                if ($Platzanzahl == 6) {
                    $OrtName = "Handrechtsoben";
                    $Links = 722 / 2 - 255;
                    $Oben = 275;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 7) {
                    $OrtName = "Gelenkrechtsoben";
                    $Links = 722 / 2 - 240;
                    $Oben = 223;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 8) {
                    $OrtName = "Armrechtsoben";
                    $Links = 722 / 2 - 185;
                    $Oben = 240;
                    $Art = "1x1";
                }

                if ($Platzanzahl == 9) {
                    $OrtName = "Handlinksmitte";
                    $Links = 722 / 2 + 180;
                    $Oben = 335;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 10) {
                    $OrtName = "Gelenklinksmitte";
                    $Links = 722 / 2 + 165;
                    $Oben = 283;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 11) {
                    $OrtName = "Armlinksmitte";
                    $Links = 722 / 2 + 110;
                    $Oben = 300;
                    $Art = "1x1";
                }

                if ($Platzanzahl == 12) {
                    $OrtName = "Handlinksunten";
                    $Links = 722 / 2 - 15;
                    $Oben = 425;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 13) {
                    $OrtName = "Gelenklinksunten";
                    $Links = 722 / 2 - 30;
                    $Oben = 373;
                    $Art = "0x0";
                }
                if ($Platzanzahl == 14) {
                    $OrtName = "Armlinksunten";
                    $Links = 722 / 2 - 85;
                    $Oben = 390;
                    $Art = "1x1";
                }

                if ($Platzanzahl == 15) {
                    $OrtName = "Spezial1";
                    $Links = 722 / 2 - 35;
                    $Oben = 50;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 16) {
                    $OrtName = "Spezial2";
                    $Links = 722 / 2 + 45;
                    $Oben = 50;
                    $Art = "1x1";
                }
                if ($Platzanzahl == 17) {
                    $OrtName = "Taille";
                    $Links = 722 / 2 + 110;
                    $Oben = 230;
                    $Art = "2x1";
                }

                if ($Art == "1x1") {
                    $Breite = 65;
                    $Hoehe = 65;
                }
                if ($Art == "1x2") {
                    $Breite = 65;
                    $Hoehe = 130;
                }
                if ($Art == "1x3") {
                    $Breite = 65;
                    $Hoehe = 195;
                }
                if ($Art == "2x1") {
                    $Breite = 130;
                    $Hoehe = 65;
                }
                if ($Art == "2x2") {
                    $Breite = 130;
                    $Hoehe = 130;
                }
                if ($Art == "0x0") {
                    $Breite = 30;
                    $Hoehe = 30;
                    $Art = "1x1";
                }
                if ($Art == "1x0") {
                    $Breite = 65;
                    $Hoehe = 30;
                    $Art = "1x1";
                }
                if ($Art == "0x1") {
                    $Breite = 30;
                    $Hoehe = 65;
                    $Art = "1x1";
                }

                $LinksA = $Links - floor($Breite / 2);
                $ObenA = $Oben - floor($Hoehe / 2);

                $Ortvari = $OrtName;
                echo "<a href='Mario.php?Modifizieren=$Ortvari&Puppe=$Puppe->id'>";
                if ($Puppe->$Ortvari == "N.V.") {
                    $Itemanzeigerdat = "<u><b>$OrtName - Nicht freigeschaltet</u></b>";

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 2;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";

                    $Itemart->BildX = 65;
                    $Itemart->BildX = 65;

                    $DiffX = $Itemart->BildX / $Breite;
                    $DiffY = $Itemart->BildY / $Hoehe;

                    if ($DiffX > $DiffY and $DiffX > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffX);
                        $HoeheItem = floor($Itemart->BildY / $DiffX);
                    } elseif ($DiffY > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffY);
                        $HoeheItem = floor($Itemart->BildY / $DiffY);
                    } else {
                        $BreitItem = $Itemart->BildX;
                        $HoeheItem = $Itemart->BildY;
                    }
                    $PosX = ($Breite - $BreitItem) / 2;
                    $PosX = floor($PosX);
                    $PosY = ($Hoehe - $HoeheItem) / 2;
                    $PosY = floor($PosY);


                    $BreiteA = $BreitItem;
                    $HoeheA = $HoeheItem;

                    $LinksA = $LinksA + $PosX;
                    $ObenA = $ObenA + $PosY;

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 3;\" align='center'>";

                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";

                    echo "<img border='0' src='Bilder/Inventar/Grundlagen/NichtFrei.png' alt='$Itemart->Name' width='$BreiteA' height='$HoeheA'>";

                    echo "</a>";

                    echo "</div>";
                } elseif ($Puppe->$Ortvari != "") {
                    $Teil = $Puppe->$Ortvari;
                    $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Teil' AND Artpuppe = 'Zweibeinig'";
                    $query = mysql_query($sql);
                    $Itemart = mysql_fetch_object($query);
                    $Variablekurz = str_replace(" ", "", $Itemart->Name);
                    $Variablekurz = str_replace("\"", "", $Variablekurz);
                    $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                    $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                    $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                    $Variablekurz = str_replace("ß", "ss", $Variablekurz);


                    $Itemart->BildX = 65;
                    $Itemart->BildX = 65;

                    $DiffX = $Itemart->BildX / $Breite;
                    $DiffY = $Itemart->BildY / $Hoehe;

                    if ($DiffX > $DiffY and $DiffX > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffX);
                        $HoeheItem = floor($Itemart->BildY / $DiffX);
                    } elseif ($DiffY > 1) {
                        $BreitItem = floor($Itemart->BildX / $DiffY);
                        $HoeheItem = floor($Itemart->BildY / $DiffY);
                    } else {
                        $BreitItem = $Itemart->BildX;
                        $HoeheItem = $Itemart->BildY;
                    }
                    $PosX = ($Breite - $BreitItem) / 2;
                    $PosX = floor($PosX);
                    $PosY = ($Hoehe - $HoeheItem) / 2;
                    $PosY = floor($PosY);


                    $BreiteA = $BreitItem;
                    $HoeheA = $HoeheItem;

                    $LinksA = $LinksA + $PosX;
                    $ObenA = $ObenA + $PosY;

                    $Itemanzeigerdat = "<u><b>$OrtName - $Itemart->Name</u></b>";

                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 1;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";


                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 2;\" align='center'>";
                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";
                    echo "<img src='$Teil->Bild' width='$Breite' height='$Hoehe'>";
                    echo "</a>";
                    echo "</div>";
                } else {
                    $Itemanzeigerdat = "<u><b>$OrtName</u></b>";
                    echo "<div style=\"position:absolute; left:$LinksA" . "px; top:$ObenA" . "px; z-index: 1;\" align='center'>";

                    echo "<a href=\"?Modifizieren=$OrtName&Puppe=$Puppe->id\" onmouseover=\"return overlib('$Itemanzeigerdat', FGCOLOR, '#$dorfs->Aussenfarbe');\" onmouseout=\"return nd();\">";

                    echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";

                    echo "</a>";

                    echo "</div>";
                }

                $Platzanzahl += 1;
            }

            echo "</div>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        }

        echo "<b>Schäden an der Puppe:</b><table border='0'>";
        $Split = explode("&", $Puppe->Schaden);
        $Zahl = 0;
        while ($Split[$Zahl] != "") {
            $Splitter = explode("%", $Split[$Zahl]);
            $BPoffen = $Splitter[0];
            $BPzu = $Splitter[1];
            $EP = $Splitter[2];
            $GFS = $Splitter[3];
            $Ort = $Splitter[4];
            if ($Ort == "Armlinks") {
                $Ort = "Bein vorne links";
            }
            if ($Ort == "Armrechts") {
                $Ort = "Bein vorne rechts";
            }
            if ($Ort == "Beinlinks") {
                $Ort = "Bein hinten links";
            }
            if ($Ort == "Beinrechts") {
                $Ort = "Bein hinten rechts";
            }
            if ($EP > 0 or $BPoffen > 0) {
                echo "<tr><td><i>$Ort; EP: $EP</i></td></tr>";
            }
            if ($GFS > 0) {
                echo "<tr><td><i><font color='#cc0033'>Verletzung GFS: $Ort</font></i></td></tr>";
            }

            $Zahl += 1;
        }
        echo "</table>";
    } elseif ($Verwaltteilchen) {
        $sql = "SELECT * FROM Item WHERE id = '$Verwaltteilchen' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        if ($Item->id > 0) {
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Item->Puppenart'";
            $query = mysql_query($sql);
            $Itemsk = mysql_fetch_object($query);
            echo "<b>$Itemsk->Name";
            echo "</b><br><img src='Bilder/Inventar/$Itemsk->Bild'><br>";
            if ($Itemsk->Beschreibung != "") {
                echo "<i>$Itemsk->Beschreibung</i><br>";
            }
            echo "<br>";
            echo "Was möchtest du tun?<br>";
            if ($Itemsk->Platzdrin > 0) {
                echo "<a href='Mario.php?Tasche=$Item->id'>Inhalt betrachten</a><br>";
            }
            echo "<a href='Mario.php?Sell=$Item->id'>Verkaufen</a><br>";
            echo "<a href='Mario.php?Betrachtewerkstatt=1'>Nichts</a>";
        }
    } elseif ($Sell) {
        $sql = "SELECT * FROM Item WHERE id = '$Sell' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        if ($Item->id > 0) {
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Item->Puppenart'";
            $query = mysql_query($sql);
            $Itemk = mysql_fetch_object($query);
            if ($Item->Angelegt == "Werkstatt") {
                if ($Item->Von == $c_loged) {
                    echo "<form method='POST' action='Mario.php?verkaufnu=$Item->id'>$Item->Item <select name='Mengeverkauf'>";
                    $Menge = $Item->Menge;
                    while ($Menge > 0) {
                        echo "<option>$Menge";
                        $Menge -= 1;
                    }
                    $Verkaufpreis = $Itemk->Preis;
                    $Verkaufpreis /= 2;
                    $Verkaufpreis = round($Verkaufpreis, 0);
                    echo "</select> mal für $Verkaufpreis Ryô pro Stück<br><input type='submit' value='verkaufen'></form>";
                }
            }
        }
    } elseif ($verkaufnu) {
        $sql = "SELECT * FROM Item WHERE id = '$verkaufnu'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Marionettenteile WHERE id = '$Item->Puppenart'";
        $query = mysql_query($sql);
        $Itemk = mysql_fetch_object($query);
        $Verkaufnetgeht = 0;
        if ($Item->Angelegt == "Werkstatt") {
            if ($Mengeverkauf > 0) {
                if ($Item->Menge >= $Mengeverkauf) {
                    if ($Item->Von == $c_loged) {
                        $Geldgibt = $Itemk->Preis;
                        $Geldgibt /= 2;
                        $Geldgibt = round($Geldgibt, 0);
                        $Geldgibt *= $Mengeverkauf;
                        $up = "UPDATE user SET Geld = Geld+$Geldgibt WHERE id = '$c_loged'";
                        mysql_query($up);
                        $Itemmenge = $Item->Menge;
                        $Itemmenge -= $Mengeverkauf;
                        if ($Itemmenge > 0) {
                            $up = "UPDATE Item SET Menge = '$Itemmenge' WHERE id = '$verkaufnu'";
                            mysql_query($up);

                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->$Item $Item->Menge auf $Itemmenge (Verkauf)', '$Date')";
                            mysql_query($ins);
                        } else {
                            $del = "DELETE FROM Item WHERE id = '$verkaufnu'";
                            mysql_query($del);

                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->$Item $Item->Menge gelöscht (Verkauf)', '$Date')";
                            mysql_query($ins);
                        }
                        $up = "UPDATE Item SET Angelegt = '' WHERE Angelegt = 'Item: $verkaufnu'";
                        mysql_query($up);
                        echo "$Mengeverkauf mal $Itemk->Name für $Geldgibt Ryô verkauft!<br><a href='Mario.php?Betrachtewerkstatt=1'>Zurück</a>";
                    }
                }
            }
        }
    } elseif ($Tasche) {
        $sql = "SELECT * FROM Item WHERE id = '$Tasche' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Tasche = mysql_fetch_object($query);
        if ($Tasche->id > 0) {
            $sql2 = "SELECT * FROM Marionettenteile WHERE id = '$Tasche->Puppenart'";
            $query2 = mysql_query($sql2);
            $Itemsk = mysql_fetch_object($query2);
            $Platzo = 0;
            $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Item: $Tasche->id'";
            $query = mysql_query($sql);
            while ($Platz = mysql_fetch_object($query)) {
                $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                $query2 = mysql_query($sql2);
                $Stack = mysql_fetch_object($query2);
                $Stacksize = $Stack->Platz;
                $Stack = $Stack->Stackmenge;
                if ($Stack > 1) {
                    $Menge = $Platz->Menge / $Stack;
                    $Menge = ceil($Menge);
                    if ($Itemsk->Siegelkunst == 1) {
                        $Menge *= 1;
                    } else {
                        $Menge *= $Stacksize;
                    }
                    $Platzo += $Menge;
                } else {
                    $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                    $query2 = mysql_query($sql2);
                    $Itemsk2 = mysql_fetch_object($query2);
                    if ($Itemsk->Siegelkunst == 1) {
                        $Platzo += $Platz->Menge;
                    } else {
                        $Platzo += $Platz->Menge * $Itemsk2->Platz;
                    }
                }
            }
            $Platze = $Itemsk->Platzdrin - $Platzo;
            echo "<center><b>$Itemsk->Name - $Platzo/$Itemsk->Platzdrin Plätzen belegt</b>";

            echo "<table border='0' cellpadding='0' cellspacing='0'>";
            $New = 1;
            $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Item: $Tasche->id' ORDER BY Gross DESC";
            $query = mysql_query($sql);
            while ($Items = mysql_fetch_object($query)) {
                $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Items->Item'";
                $query2 = mysql_query($sql2);
                $Itemsk = mysql_fetch_object($query2);
                $Anzahl = $Items->Menge;
                while ($Anzahl > 0) {
                    $Stuck += 1;
                    if ($New == 1) {
                        if ($Stuck > 10) {
                            echo "<tr></tr><tr>";
                            $New = 0;
                            $Stuck = $Rowplus + 1;
                            $Rowplus = $Rowplus2;
                            $Rowplus2 = 0;
                        } else {
                            echo "<tr>";
                            $New = 0;
                        }
                    }
                    if ($Itemsk->Platz == 1) {
                        $colspan = 1;
                        $rowspan = 1;
                    } elseif ($Itemsk->Platz == 2) {
                        $Rowplus += 1;
                        $colspan = 1;
                        $rowspan = 2;
                    } elseif ($Itemsk->Platz == 3) {
                        $Rowplus += 1;
                        $colspan = 1;
                        $rowspan = 3;
                        $Rowplus2 += 1;
                    } elseif ($Itemsk->Platz == 4) {
                        $Rowplus += 2;
                        $colspan = 2;
                        $rowspan = 2;
                        $Stuck += 1;
                    }
                    if ($Itemsk->Stackmenge > 0) {
                        if ($Anzahl >= $Itemsk->Stackmenge) {
                            $ZahlanItems = $Itemsk->Stackmenge;
                        } else {
                            $ZahlanItems = $Anzahl;
                        }
                        $Bild = str_replace("Itemzahl", "$ZahlanItems", $Itemsk->Inventarbild);
                        echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\"><a href='Mario.php?VerwaltX=$Items->id'><img border='0' src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a></td>";
                        $Minus = $ZahlanItems;
                    } else {
                        echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\">";
                        echo "<a href='Mario.php?VerwaltX=$Items->id'><img border='0' src='Bilder/Inventar/$Itemsk->Inventarbild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                        echo "</td>";
                        $Minus = 1;
                    }
                    if ($Stuck >= 10) {
                        echo "</tr>";
                        $Stuck = $Rowplus;
                        $Rowplus = $Rowplus2;
                        $Rowplus2 = 0;
                        $New = 1;
                    }
                    $Anzahl -= $Minus;
                }
            }
            while ($Platze > 0) {
                $Stuck += 1;
                if ($New == 1) {
                    if ($Stuck > 10) {
                        echo "<tr></tr><tr>";
                        $New = 0;
                        $Stuck = $Rowplus + 1;
                        $Rowplus = $Rowplus2;
                        $Rowplus2 = 0;
                    } else {
                        echo "<tr>";
                        $New = 0;
                    }
                }
                echo "<td><img border='0' src='Bilder/Inventar/Grundlagen/1x1.png'></td>";
                if ($Stuck >= 10) {
                    echo "</tr>";
                    $Stuck = $Rowplus;
                    $Rowplus = $Rowplus2;
                    $Rowplus2 = 0;
                    $New = 1;
                }
                $Platze -= 1;
            }
            echo "</table><br>";
            $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Tasche->Item'";
            $query2 = mysql_query($sql2);
            $Itemsk = mysql_fetch_object($query2);
            $Beschreibung = nl2br($Itemsk->Beschreibung);
            echo "$Beschreibung<br><br>";
            echo "<a href='Mario.php?reintun2=$Tasche->id'>Gegenstände hinzufügen</a><br>";
            $pos = strpos($Tasche->Angelegt, "Item: ");
            if ($pos === false) {
                echo "<a href='Mario.php?Betrachtewerkstatt=1'>Zurück</a>";
            } else {
                $Taschevorher = str_replace("Item: ", "", $Tasche->Angelegt);
                echo "<a href='Mario.php?Tasche=$Taschevorher'>Zurück</a>";
            }

            echo "</center>";
        }
    } elseif ($VerwaltX) {
        $sql = "SELECT * FROM Item WHERE id = '$VerwaltX' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemsk = mysql_fetch_object($query);
        if ($Item->id > 0) {
            echo "<center>";
            if ($Itemsk->Platzdrin > 0) {
                echo "<a href='Inventar.php?Tasche=$Item->id'>Inhalt dieses Items betrachten</a><br><br>";
            }
            echo "<form method='POST' action='Mario.php?VerwaltX2=$Item->id'>";
            echo "<table border='0'>";
            echo "<tr>";
            echo "<td><b>Item</b>:</td>";
            echo "<td>$Item->Item</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Menge</b>:</td>";
            echo "<td>$Item->Menge Stk.</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Davon</b></td>";
            echo "<td><select name='Mengeraustu'>";
            $Menge = $Item->Menge;
            while ($Menge > 0) {
                echo "<option>$Menge";
                $Menge -= 1;
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'><input type='submit' value='aus der Vorrichtung entfernen'></td>";
            echo "</tr>";
            echo "</table></center>";
        }
    } elseif ($VerwaltX2) {
        $sql = "SELECT * FROM Item WHERE id = '$VerwaltX2' AND Von = '$c_loged' AND Angelegt != ''";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemsk = mysql_fetch_object($query);
        if ($Item->id > 0) {
            if ($Item->Menge < $Mengeraustu or $Mengeraustu < 1) {
            } else {
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Item = '$Item->Item' AND Angelegt = ''";
                $query = mysql_query($sql);
                $Item2 = mysql_fetch_object($query);
                if ($Item2->id > 0 and $Itemsk->NonStack != 1) {
                    if ($Item->Menge == $Mengeraustu) {
                        $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Item2->id'";
                        mysql_query($up);
                        $del = "DELETE FROM Item WHERE id = '$Item->id'";
                        mysql_query($del);
                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item2->Item $Item2->Menge+$Mengeraustu', '$Date')";
                        mysql_query($ins);
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge gelöscht', '$Date')";
                        mysql_query($ins);
                    } else {
                        $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Item2->id'";
                        mysql_query($up);
                        $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Item->id'";
                        mysql_query($up);
                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item2->Item $Item2->Menge+$Mengeraustu', '$Date')";
                        mysql_query($ins);
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge-$Mengeraustu', '$Date')";
                        mysql_query($ins);
                    }
                } else {
                    if ($Item->Menge == $Mengeraustu) {
                        $up = "UPDATE Item SET Angelegt = '' WHERE id = '$Item->id'";
                        mysql_query($up);
                    } else {
                        $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Item->Item', '$Mengeraustu', '', '$Item->Gross', '$Item->Ausdauerhalt', '$Item->Beschrankunghalt', '$Item->Bluthalt')";
                        mysql_query($ins);
                        $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Item->id'";
                        mysql_query($up);
                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge-$Mengeraustu', '$Date')";
                        mysql_query($ins);
                    }
                }
                $Tasche = str_replace("Item: ", "", $Item->Angelegt);
                echo "$Item->Item wurde $Mengeraustu mal aus der Vorrichtung entfernt!<br>
<a href='Mario.php?Betrachtewerkstatt=1'>Zurück</a>";
            }
        }
    } elseif ($reintun2) {
        $sql = "SELECT * FROM Item WHERE id = '$reintun2' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->Item;
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Marionette->Puppenart'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $Platzo = 0;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Item: $Marionette->id'";
                $query = mysql_query($sql);
                while ($Platz = mysql_fetch_object($query)) {
                    $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                    $query2 = mysql_query($sql2);
                    $Stack = mysql_fetch_object($query2);
                    $Stacksize = $Stack->Platz;
                    $Stack = $Stack->Stackmenge;
                    if ($Stack > 1) {
                        $Menge = $Platz->Menge / $Stack;
                        $Menge = ceil($Menge);
                        $Menge *= $Stacksize;
                        $Platzo += $Menge;
                    } else {
                        $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Itemsk2 = mysql_fetch_object($query2);
                        $Platzo += $Platz->Menge * $Itemsk2->Platz;
                    }
                }
                $Platze = $Gegenstand->Platzdrin - $Platzo;
                echo "<b>Gegenstände in die Vorrichtung $Marionette->Item packen:</b><br><br>";
                echo "<table border='0' cellpadding='0' cellspacing='0'>";
                $New = 1;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = '' ORDER BY Gross DESC";
                $query = mysql_query($sql);
                while ($Items = mysql_fetch_object($query)) {
                    $okay = 0;
                    $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Items->Item'";
                    $query2 = mysql_query($sql2);
                    $Itemsk = mysql_fetch_object($query2);
                    $pos = strpos($Gegenstand->Nurrein, "$Items->Item");
                    if ($pos === false) {
                    } else {
                        $okay = 1;
                    }
                    if ($Gegenstand->Nurrein == "Alles") {
                        $okay = 1;
                    }
                    if ($okay == 1) {
                        $Anzahl = $Items->Menge;
                        while ($Anzahl > 0) {
                            $Stuck += 1;
                            if ($New == 1) {
                                if ($Stuck > 10) {
                                    echo "<tr></tr><tr>";
                                    $New = 0;
                                    $Stuck = $Rowplus + 1;
                                    $Rowplus = $Rowplus2;
                                    $Rowplus2 = 0;
                                } else {
                                    echo "<tr>";
                                    $New = 0;
                                }
                            }
                            if ($Itemsk->Platz == 1) {
                                $colspan = 1;
                                $rowspan = 1;
                            } elseif ($Itemsk->Platz == 2) {
                                $Rowplus += 1;
                                $colspan = 1;
                                $rowspan = 2;
                            } elseif ($Itemsk->Platz == 3) {
                                $Rowplus += 1;
                                $colspan = 1;
                                $rowspan = 3;
                                $Rowplus2 += 1;
                            } elseif ($Itemsk->Platz == 4) {
                                $Rowplus += 2;
                                $colspan = 2;
                                $rowspan = 2;
                                $Stuck += 1;
                            }
                            if ($Itemsk->Stackmenge > 0) {
                                if ($Anzahl >= $Itemsk->Stackmenge) {
                                    $ZahlanItems = $Itemsk->Stackmenge;
                                } else {
                                    $ZahlanItems = $Anzahl;
                                }
                                $Bild = str_replace("Itemzahl", "$ZahlanItems", $Itemsk->Inventarbild);
                                echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\"><a href='Mario.php?rein2=$reintun2&Item=$Items->id'><img border='0' src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a></td>";
                                $Minus = $ZahlanItems;
                            } else {
                                echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\">";
                                echo "<a href='Mario.php?rein2=$reintun2&Item=$Items->id'><img border='0' src='Bilder/Inventar/$Itemsk->Inventarbild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                                echo "</td>";
                                $Minus = 1;
                            }
                            if ($Stuck >= 10) {
                                echo "</tr>";
                                $Stuck = $Rowplus;
                                $Rowplus = $Rowplus2;
                                $Rowplus2 = 0;
                                $New = 1;
                            }
                            $Anzahl -= $Minus;
                        }
                    }
                }
                echo "</table><br><a href='Mario.php?Tasche=$reintun'>Zurück</a>";
            }
        }
    } elseif ($rein2) {
        $sql = "SELECT * FROM Item WHERE id = '$rein2' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->Item;
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Marionette->Puppenart'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $sql = "SELECT * FROM Item WHERE id = '$Item' AND Von = '$c_loged'";
                $query = mysql_query($sql);
                $Itemrein = mysql_fetch_object($query);
                $okay = 0;
                $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Itemrein->Item'";
                $query2 = mysql_query($sql2);
                $Itemsk = mysql_fetch_object($query2);
                $okay = 0;
                $pos = strpos($Gegenstand->Nurrein, "$Itemrein->Item");
                if ($pos === false) {
                } else {
                    $okay = 1;
                }
                if ($Gegenstand->Nurrein == "Alles") {
                    $okay = 1;
                }
                if ($okay == 1) {
                    $Platzo = 0;
                    $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Item: $Marionette->id'";
                    $query = mysql_query($sql);
                    while ($Platz = mysql_fetch_object($query)) {
                        $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Stack = mysql_fetch_object($query2);
                        $Stacksize = $Stack->Platz;
                        $Stack = $Stack->Stackmenge;
                        if ($Stack > 1) {
                            $Menge = $Platz->Menge / $Stack;
                            $Menge = ceil($Menge);
                            $Menge *= $Stacksize;
                            $Platzo += $Menge;
                        } else {
                            $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                            $query2 = mysql_query($sql2);
                            $Itemsk2 = mysql_fetch_object($query2);
                            $Platzo += $Platz->Menge * $Itemsk2->Platz;
                        }
                    }
                    $Platze = $Gegenstand->Platzdrin - $Platzo;

                    echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">";
                    echo "<!--
function mogo()
{
var Platzbraucht = eval(document.Verwalt.Mengeraustu.options[document.Verwalt.Mengeraustu.selectedIndex].value);
var Item = \"$Itemsk->Stackmenge\";
if (Item > 0)
{
var Platzbrauchen = Platzbraucht / $Itemsk->Stackmenge;
var Platzbrauchen = Math.ceil(Platzbrauchen);
var Platzbrauchen = Platzbrauchen * $Itemsk->Platz;
}
else
{
var Platzbrauchen = Platzbraucht * $Itemsk->Platz;
}
PlatzinTasche.innerHTML = Platzbrauchen;

}
//--></SCRIPT>";

                    echo "<center><form method='POST' name='Verwalt' action='Mario.php?reinpack2=$rein2&Item=$Item'>";
                    echo "<table border='0'>";
                    echo "<tr>";
                    echo "<td><b>Vorrichtung</b>:</td>";
                    echo "<td>$Gegenstand->Name</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Platz</b>:</td>";
                    echo "<td>$Platze</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Item</b>:</td>";
                    echo "<td>$Itemrein->Item</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Menge</b>:</td>";
                    echo "<td>$Itemrein->Menge Stk.</td>";
                    echo "</tr>";


                    if ($Itemsk->Stackmenge > 0) {
                        $Platz = $Itemrein->Menge / $Itemsk->Stackmenge;
                        $Platz = ceil($Platz);
                        $Platz *= $Itemsk->Platz;
                    } else {
                        $Platz = $Itemrein->Menge * $Itemsk->Platz;
                    }

                    echo "<tr>";
                    echo "<td><b>Platverbrauch</b>:</td>";
                    echo "<td><div id='PlatzinTasche' name='Platzintasche'>$Platz</div></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Davon</b></td>";
                    echo "<td><select name='Mengeraustu' onchange='mogo()'>";
                    $Menge = $Itemrein->Menge;
                    while ($Menge > 0) {
                        echo "<option value='$Menge'>$Menge";
                        $Menge -= 1;
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='2'><input type='submit' value='in die Tasche packen'></td>";
                    echo "</tr>";
                    echo "</table></center>";
                }
            }
        }
    } elseif ($reinpack2) {
        $sql = "SELECT * FROM Item WHERE id = '$reinpack2' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->Item;
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Marionette->Puppenart'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $sql = "SELECT * FROM Item WHERE id = '$Item' AND Von = '$c_loged'";
                $query = mysql_query($sql);
                $Itemrein = mysql_fetch_object($query);
                $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Itemrein->Item'";
                $query2 = mysql_query($sql2);
                $Itemsk = mysql_fetch_object($query2);
                $okay = 0;
                $pos = strpos($Gegenstand->Nurrein, "$Itemrein->Item");
                if ($pos === false) {
                } else {
                    $okay = 1;
                }
                if ($Gegenstand->Nurrein == "Alles") {
                    $okay = 1;
                }
                if ($okay == 1) {
                    $Platzo = 0;
                    $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Item: $Marionette->id'";
                    $query = mysql_query($sql);
                    while ($Platz = mysql_fetch_object($query)) {
                        $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Stack = mysql_fetch_object($query2);
                        $Stacksize = $Stack->Platz;
                        $Stack = $Stack->Stackmenge;
                        if ($Stack > 1) {
                            $Menge = $Platz->Menge / $Stack;
                            $Menge = ceil($Menge);
                            $Menge *= $Stacksize;
                            $Platzo += $Menge;
                        } else {
                            $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                            $query2 = mysql_query($sql2);
                            $Itemsk2 = mysql_fetch_object($query2);
                            $Platzo += $Platz->Menge * $Itemsk2->Platz;
                        }
                    }
                    $Platze = $Gegenstand->Platzdrin - $Platzo;
                    if ($Itemsk->Stackmenge > 0) {
                        $Platz = $Mengeraustu / $Itemsk->Stackmenge;
                        $Platz = ceil($Platz);
                        $Platz *= $Itemsk->Platz;
                    } else {
                        $Platz = $Mengeraustu * $Itemsk->Platz;
                    }
                    if ($Platz > $Platze) {
                        echo "Du hast nicht genug Platz ($Platze) in der Vorrichtung!<br><a href='Mario.php?Puppe=$Marionette->id&Inhalt=$reinpack'>Zurück</a>";
                    } else {
                        if ($Mengeraustu <= $Itemrein->Menge and $Mengeraustu > 0) {
                            $sql = "SELECT * FROM Item WHERE Item = '$Itemrein->Item' AND Von = '$c_loged' AND Angelegt = 'Item: $Marionette->id'";
                            $query = mysql_query($sql);
                            $Itemda = mysql_fetch_object($query);
                            if ($Itemda->id > 0 and $Itemsk->NonStack != 1) {
                                if ($Mengeraustu == $Itemrein->Menge) {
                                    $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Itemda->id'";
                                    mysql_query($up);
                                    $del = "DELETE FROM Item WHERE id = '$Itemrein->id'";
                                    mysql_query($del);
                                    $Date = date("d.m.Y, H:i");
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemda->Item $Itemda->Menge+$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemrein->Item $Itemrein->Menge gelöscht', '$Date')";
                                    mysql_query($ins);
                                } else {
                                    $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Itemda->id'";
                                    mysql_query($up);
                                    $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Itemrein->id'";
                                    mysql_query($up);

                                    $Date = date("d.m.Y, H:i");
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemda->Item $Itemda->Menge+$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemrein->Item $Itemrein->Menge-$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                }
                            } else {
                                if ($Mengeraustu == $Itemrein->Menge) {
                                    $up = "UPDATE Item SET Angelegt = 'Item: $Marionette->id' WHERE id = '$Itemrein->id'";
                                    mysql_query($up);
                                } else {
                                    $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Itemrein->Item', '$Mengeraustu', 'Item: $Marionette->id', '$Itemrein->Gross', '$Itemrein->Ausdauerhalt', '$Itemrein->Beschrankunghalt', '$Itemrein->Bluthalt')";
                                    mysql_query($ins);
                                    $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Itemrein->id'";
                                    mysql_query($up);
                                }
                            }

                            echo "$Itemrein->Item wurde in die Vorrichtung getan!<br><a href='Mario.php?Tasche=$Marionette->id'>Zurück zur Vorrichtung</a><br>
<a href='Mario.php?Betrachtewerkstatt=$Marionette->id'>Zurück zur Übersicht</a>";
                        }
                    }
                }
            }
        }
    } elseif ($Betrachtewerkstatt) {
        echo "<b>Vorhandene Marionettenteile bei dir zu Hause</b><br><br>
<table border='0' cellpadding='0' cellspacing='0'>";
        $New = 1;
        $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' ORDER BY Gross DESC";
        $query = mysql_query($sql);
        while ($Items = mysql_fetch_object($query)) {
            $sql2 = "SELECT * FROM Marionettenteile WHERE id = '$Items->Puppenart'";
            $query2 = mysql_query($sql2);
            $Itemsk = mysql_fetch_object($query2);
            if ($Itemsk->Artpuppe == "Mehrbeinig") {
                $Zahldazu2 = 1;
            } else {
                $Zahldazu2 = 2;
            }
            $Itemsk->Platz = 1;
            $Anzahl = $Items->Menge;
            while ($Anzahl > 0) {
                $Stuck += 1;
                if ($New == 1) {
                    if ($Stuck > 8) {
                        echo "<tr></tr><tr>";
                        $New = 0;
                        $Stuck = $Rowplus + 1;
                        $Rowplus = $Rowplus2;
                        $Rowplus2 = 0;
                    } else {
                        echo "<tr>";
                        $New = 0;
                    }
                }
                if ($Itemsk->Platz == 1) {
                    $colspan = 1;
                    $rowspan = 1;
                } elseif ($Itemsk->Platz == 2) {
                    $Rowplus += 1;
                    $colspan = 1;
                    $rowspan = 2;
                } elseif ($Itemsk->Platz == 3) {
                    $Rowplus += 1;
                    $colspan = 1;
                    $rowspan = 3;
                    $Rowplus2 += 1;
                } elseif ($Itemsk->Platz == 4) {
                    $Rowplus += 2;
                    $colspan = 2;
                    $rowspan = 2;
                    $Stuck += 1;
                }
                if ($Itemsk->Stackmenge > 0) {
                    if ($Anzahl >= $Itemsk->Stackmenge) {
                        $ZahlanItems = $Itemsk->Stackmenge;
                    } else {
                        $ZahlanItems = $Anzahl;
                    }
                    $Bild = str_replace("Itemzahl", "$ZahlanItems", $Itemsk->Bild);


                    $Kurzname = str_replace(" ", "", $Itemsk->Name);
                    $Kurzname = str_replace("\"", "", $Kurzname);
                    $Kurzname = str_replace("ä", "ae", $Kurzname);
                    $Kurzname = str_replace("ö", "oe", $Kurzname);
                    $Kurzname = str_replace("ü", "ue", $Kurzname);
                    $Kurzname = str_replace("ß", "ss", $Kurzname);

                    echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\"><a href='Mario.php?Verwaltteilchen=$Items->id'><img border='0' src='Bilder/Inventar/Item/Puppenbilder/$Zahldazu2$Kurzname/1.png' alt='$Itemsk->Name - $Items->Menge Stk.'></a></td>";
                    $Minus = $ZahlanItems;
                } else {
                    $Kurzname = str_replace(" ", "", $Itemsk->Name);
                    $Kurzname = str_replace("\"", "", $Kurzname);
                    $Kurzname = str_replace("ä", "ae", $Kurzname);
                    $Kurzname = str_replace("ö", "oe", $Kurzname);
                    $Kurzname = str_replace("ü", "ue", $Kurzname);
                    $Kurzname = str_replace("ß", "ss", $Kurzname);

                    echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\">";
                    echo "<a href='Mario.php?Verwaltteilchen=$Items->id'>";
                    echo "<img border='0' src='Bilder/Inventar/Item/Puppenbilder/$Zahldazu2$Kurzname/1.png' alt='$Itemsk->Name - $Items->Menge Stk.'>";
                    echo "</a>";
                    echo "</td>";
                    $Minus = 1;
                }
                if ($Stuck >= 8) {
                    echo "</tr>";
                    $Stuck = $Rowplus;
                    $Rowplus = $Rowplus2;
                    $Rowplus2 = 0;
                    $New = 1;
                }
                $Anzahl -= $Minus;
            }
        }
        echo "</table><br><a href='Mario.php'>Zurück</a></center>";
    } elseif ($Beindran == 1 or $Armdran == 1) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            if ($Marionette->Art == "Zweibeinig") {
                $Kosten = 5000;
                $Training = "Weiteren Arm anbringen";
            } else {
                $Kosten = 7500;
                $Training = "Weiteres Beinpaar anbringen";
            }
            if ($dorfs2->Geld >= $Kosten) {
                $up = "UPDATE user SET Geld = Geld-$Kosten WHERE id = '$c_loged'";
                mysql_query($up);
                $Orts = str_replace("Arm", "", $Ortbau);
                $Orts = str_replace("Gelenk", "", $Orts);
                $Orts = str_replace("Hand", "", $Orts);
                $up = "UPDATE Marionetten SET `Arm$Orts` = '', `Gelenk$Orts` = '',`Hand$Orts` = '' WHERE id = '$Marionette->id' AND Besitzer = '$c_loged'";
                mysql_query($up);
                if ($Marionette->Art == "Zweibeinig") {
                    echo "Du hast einen weiteren Arm angebracht!<br>";
                } else {
                    echo "Du hast ein weiteres Beinpaar angebracht!<br>";
                }
                echo "<a href='Mario.php?Modifizieren=$Ortbau&Puppe=$Marionette->id'>Zurück</a>";
            }
        }
    } elseif ($Abmontieren) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Teil = $Marionette->$Abmontieren;
            echo "Willst du die Vorrichtung \"<b>$Teil</b>\" wirklich abmontieren?<br>
Die Werte der Puppe werden jedoch erst beim nächsten Update auf den Neusten Stand gebracht werden.<br>
<a href='Mario.php?Abmonte=$Abmontieren&Puppe=$Puppe'>Ja, dieses Teil abmontieren!</a><br>
<a href='Mario.php?Verwalt=$Puppe'>Nein, dieses Teil dran lassen!</a><br>";
        }
    } elseif ($Abmonte) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Teil = $Marionette->$Abmonte;

            $Ort = "$Abmonte";
            $Orte = strpos($Abmonte, "Hand");
            if ($Orte === false) {
            } else {
                $Ort = "Hand";
            }
            $Orte = strpos($Abmonte, "Fuss");
            if ($Orte === false) {
            } else {
                $Ort = "Fuss";
            }
            $Orte = strpos($Abmonte, "Arm");
            if ($Orte === false) {
            } else {
                $Ort = "Arm";
            }
            $Orte = strpos($Abmonte, "Gelenk");
            if ($Orte === false) {
            } else {
                $Ort = "Gelenk";
            }
            if ($Teil != "" and $Teil != "N.V.") {
                $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Teil' AND Artpuppe = '$Marionette->Art' AND Ort = '$Ort'";
                $query = mysql_query($sql);
                $Teilchen = mysql_fetch_object($query);
                $up = "UPDATE Marionetten SET $Abmonte = '' WHERE id = '$Marionette->id'";
                mysql_query($up);
                $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Puppenart) VALUES ('$c_loged', '$Teilchen->Name', '1', 'Werkstatt', '$Teilchen->id')";
                mysql_query($ins);
                $sql = "SELECT id FROM Item WHERE Von = '$c_loged' AND Item = '$Teilchen->Name' ORDER BY id DESC";
                $query = mysql_query($sql);
                $Teilchens = mysql_fetch_object($query);

                $up = "UPDATE Item SET Angelegt = '' WHERE Angelegt = 'Puppe:$Marionette->id|$Teilchen->id|$Abmonte'";
                mysql_query($up);

                echo "Die Vorrichtung wurde erfolgreich abmontiert!<br><a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
            }
        }
    } elseif ($Modifizieren) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            echo "<u><b>$Marionette->Name</b></u><br>
<a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a><br><br>";
            $AnderStelle = $Marionette->$Modifizieren;
            if ($AnderStelle == "N.V.") {
                if ($Marionette->Art == "Mehrbeinig") {
                    echo "An dieser Stelle könnte ein weiteres Paar Beine angebracht werden.<br>
<a href='Mario.php?Beindran=1&Puppe=$Marionette->id&Ortbau=$Modifizieren'>Ein weiteres Beinpaar für 7500 Ryô anbringen (Dauer: 1 Tag)</a>";
                } else {
                    echo "An dieser Stelle könnte ein weiterer Arm angebracht werden.<br>
<a href='Mario.php?Armdran=1&Puppe=$Marionette->id&Ortbau=$Modifizieren'>Einen weiteren Arm für 5000 Ryô anbringen (Dauer: 1 Tag)</a>";
                }
            } elseif ($AnderStelle != "") {
                $sql = "SELECT * FROM Marionettenteile WHERE Name = '$AnderStelle' AND Artpuppe = '$Marionette->Art'";
                $query = mysql_query($sql);
                $Angebracht = mysql_fetch_object($query);
                echo "Es ist derzeit die Vorrichtung \"<b>$Angebracht->Name</b>\" an dieser Stelle angebracht.<br><i>$Angebracht->Beschreibung</i>";
                if ($Angebracht->Platzdrin > 0) {
                    echo "<br><a href='Mario.php?Inhalt=$Modifizieren&Puppe=$Marionette->id'>Inhalt verwalten</a>";
                }
                echo "<br><a href='Mario.php?Abmontieren=$Modifizieren&Puppe=$Puppe'>Dieses Teil abmontieren</a>";
                #if ($Angebracht->Entfernbar != 1){echo "<br><a href='Mario.php?Entferne=$Modifizieren&Puppe=$Puppe'>Vorrichtung entfernen</a>";}
                echo "<br>Folgende Vorrichtungen könnten ansonsten angebracht werden:<br><br>";
                $AnderStelle2 = str_replace("links", "", $Modifizieren);
                $AnderStelle2 = str_replace("rechts", "", $AnderStelle2);
                $AnderStelle2 = str_replace("mitte", "", $AnderStelle2);
                $AnderStelle2 = str_replace("oben", "", $AnderStelle2);
                $AnderStelle2 = str_replace("unten", "", $AnderStelle2);
                $AnderStelle2 = str_replace("Fu%DF", "Fuss", $AnderStelle2);
                $AnderStelle2 = str_replace("Fuß", "Fuss", $AnderStelle2);
                $sql = "SELECT * FROM Marionettenteile WHERE Artpuppe = '$Marionette->Art' AND Ort = '$AnderStelle2' AND id != '$Angebracht->id'";
                $query = mysql_query($sql);
                while ($Teile = mysql_fetch_object($query)) {
                    if ($Teile->Kaufbar != 1) {
                        $Okay = 0;
                        $split = explode(",", $Teile->Useronly);
                        $Zahl = 0;
                        while ($split[$Zahl] != "") {
                            if ($split[$Zahl] == $dorfs2->name) {
                                $Okay = 1;
                            }
                            $Zahl += 1;
                        }
                    } else {
                        $Okay = 1;
                    }
                    $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Teile->id'";
                    $query2 = mysql_query($sql2);
                    $Teilchen = mysql_fetch_object($query2);
                    if ($Teilchen->id > 0) {
                        $Okay = 1;
                    }
                    if ($Okay == 1) {
                        $Teile->Beschreibung = nl2br($Teile->Beschreibung);

                        echo "<table border='0' width='697' height='250' cellpadding='0' cellspacing='0' background='/layouts/Uebergang/Untergrund.png'>
<form method='POST' action='Mario.php?Kauffur=$Modifizieren&Puppe=$Puppe&Item=$Teile->id'>
<tr>
<td width='15%' align='center'><img src='$Teile->Bild'><br>$Teile->Name</td>
<td width='35%'>&nbsp;$Teile->Beschreibung</td>
<td width='15%'>";

                        if ($Teilchen->id > 0) {
                            $Teile->Preis = 0;
                            echo "<i>Zu Hause vorhanden</i><br>";
                        }

                        #$Teile->Anbau += 1;

                        echo "Kosten: $Teile->Preis Ryô<br>
Anbauzeit: $Teile->Anbau Tag";
                        if ($Teile->Anbau != 1) {
                            echo "e";
                        }
                        echo "<br>";
                        $Geldtogether = $dorfs2->Geld;
                        if ($dorfs2->Training != "" and $Teile->Anbau > 0) {
                            echo "Du trainierst und kannst daher nichts anbauen";
                        } elseif ($Geldtogether < $Teile->Preis) {
                            echo "Zu teuer";
                        } else {
                            echo "<input type='submit' value='Anbringen'>";
                        }
                        echo "</td>
</form></tr>
</table><br><br>";
                    }
                }
            } else {
                echo "An dieser Stelle ist derzeit keine Vorrichtung angebracht. Folgende Vorrichtungen wären an dieser Stelle möglich:<br><br>";
                $AnderStelle2 = str_replace("links", "", $Modifizieren);
                $AnderStelle2 = str_replace("rechts", "", $AnderStelle2);
                $AnderStelle2 = str_replace("mitte", "", $AnderStelle2);
                $AnderStelle2 = str_replace("oben", "", $AnderStelle2);
                $AnderStelle2 = str_replace("unten", "", $AnderStelle2);
                $AnderStelle2 = str_replace("Fu%DF", "Fuss", $AnderStelle2);
                $AnderStelle2 = str_replace("Fuß", "Fuss", $AnderStelle2);
                $sql = "SELECT * FROM Marionettenteile WHERE Artpuppe = '$Marionette->Art' AND Ort = '$AnderStelle2'";
                $query = mysql_query($sql);
                while ($Teile = mysql_fetch_object($query)) {
                    if ($Teile->Kaufbar != 1) {
                        $Okay = 0;
                        $split = explode(",", $Teile->Useronly);
                        $Zahl = 0;
                        while ($split[$Zahl] != "") {
                            if ($split[$Zahl] == $dorfs2->name) {
                                $Okay = 1;
                            }
                            $Zahl += 1;
                        }
                    } else {
                        $Okay = 1;
                    }
                    $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Teile->id'";
                    $query2 = mysql_query($sql2);
                    $Teilchen = mysql_fetch_object($query2);
                    if ($Teilchen->id > 0) {
                        $Okay = 1;
                    }
                    if ($Okay == 1) {
                        $Teile->Beschreibung = nl2br($Teile->Beschreibung);

                        echo "<table border='0' width='697' height='250' cellpadding='0' cellspacing='0' background='/layouts/Uebergang/Untergrund.png'>
<form method='POST' action='Mario.php?Kauffur=$Modifizieren&Puppe=$Puppe&Item=$Teile->id'>
<tr>
<td width='15%' align='center'><img src='Bilder/Inventar/$Teile->Bild'><br>$Teile->Name</td>
<td width='35%'>&nbsp;$Teile->Beschreibung</td>
<td width='15%'>";

                        if ($Teilchen->id > 0) {
                            $Teile->Preis = 0;
                            echo "<i>Zu Hause vorhanden</i><br>";
                        }

                        #$Teile->Anbau += 1;

                        echo "Kosten: $Teile->Preis Ryô<br>
Anbauzeit: $Teile->Anbau Tag";
                        if ($Teile->Anbau != 1) {
                            echo "e";
                        }
                        echo "<br>";
                        $Geldtogether = $dorfs2->Geld;
                        if ($dorfs2->Training != "" and $Teile->Anbau > 0) {
                            echo "Du trainierst und kannst daher nichts anbauen";
                        } elseif ($Geldtogether < $Teile->Preis) {
                            echo "Zu teuer";
                        } else {
                            echo "<input type='submit' value='Anbringen'>";
                        }
                        echo "</td>
</form></tr>
</table><br><br>";
                    }
                }
            }
        }
    } elseif ($Kauffur) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Item' AND Artpuppe = '$Marionette->Art'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);

            if ($Gegenstand->Kaufbar != 1) {
                $Okay = 0;
                $split = explode(",", $Gegenstand->Useronly);
                $Zahl = 0;
                while ($split[$Zahl] != "") {
                    if ($split[$Zahl] == $dorfs2->name) {
                        $Okay = 1;
                    }
                    $Zahl += 1;
                }

                if ($Okay == 0) {
                    $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Gegenstand->id'";
                    $query2 = mysql_query($sql2);
                    $Teilchen = mysql_fetch_object($query2);
                    if ($Teilchen->id > 0) {
                    } else {
                        $Gegenstand->id = 0;
                    }
                }
            }

            if ($Gegenstand->id > 0) {
                $Dranist = $Marionette->$Kauffur;
                if ($Dranist == "") {
                    echo "Möchtest du die Vorrichtung \"<b>$Gegenstand->Name</b>\" wirklich an deine Puppe anbringen?<br>";
                    if ($Teilchen->id > 0) {
                        $Gegenstand->Preis = 0;
                        echo "<i>Du besitzt mindestens eine dieser Vorrichtungen zu Hause.</i><br>";
                    }

                    echo "Das Anbringen würde dich $Gegenstand->Anbau Tag";
                    if ($Anbau == 1) {
                        echo "e";
                    }
                    echo " und $Gegenstand->Preis Ryô kosten.<br>
<a href='Mario.php?Kaufen=$Kauffur&Puppe=$Marionette->id&Gegenstand=$Gegenstand->id'>Ja, Vorrichtung kaufen!</a><br>
<a href='Mario.php?Verwalt=$Marionette->id'>Nein, Vorrichtung nicht kaufen</a>";
                } else {
                    $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Dranist' AND Artpuppe = '$Marionette->Art'";
                    $query = mysql_query($sql);
                    $Gegenstand2 = mysql_fetch_object($query);
                    $Anbringzeit = $Gegenstand->Anbau;
                    echo "An dieser Stelle ist bereits die Vorrichtung \"<b>$Gegenstand2->Name</b>\" angebracht.<br>";
                    $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Gegenstand->id'";
                    $query2 = mysql_query($sql2);
                    $Teilchen = mysql_fetch_object($query2);
                    if ($Teilchen->id > 0) {
                        $Gegenstand->Preis = 0;
                        echo "<i>Du besitzt mindestens eine dieser Vorrichtungen zu Hause.</i><br>";
                    }

                    echo "
Das Anbringen würde damit $Anbringzeit Tag";
                    if ($Anbau == 1) {
                        echo "e";
                    }
                    echo " und $Gegenstand->Preis Ryô kosten.<br>
Möchtest du die Vorrichtung \"<b>$Gegenstand2->Name</b>\" wirklich durch \"<b>$Gegenstand->Name</b>\" austauschen?<br>
<a href='Mario.php?Kaufen=$Kauffur&Puppe=$Marionette->id&Gegenstand=$Gegenstand->id'>Ja, Vorrichtung ersetzen!</a><br>
<a href='Mario.php?Verwalt=$Marionette->id'>Nein, Vorrichtung nicht ersetzen</a>";
                }
            }
        }
    } elseif ($Kaufen) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $sql = "SELECT * FROM Marionettenteile WHERE id = '$Gegenstand' AND Artpuppe = '$Marionette->Art'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);

            if ($Gegenstand->Kaufbar != 1) {
                $Okay = 0;
                $split = explode(",", $Gegenstand->Useronly);
                $Zahl = 0;
                while ($split[$Zahl] != "") {
                    if ($split[$Zahl] == $dorfs2->name) {
                        $Okay = 1;
                    }
                    $Zahl += 1;
                }

                if ($Okay == 0) {
                    $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Gegenstand->id'";
                    $query2 = mysql_query($sql2);
                    $Teilchen = mysql_fetch_object($query2);
                    if ($Teilchen->id > 0) {
                    } else {
                        $Gegenstand->id = 0;
                    }
                }
            }

            if ($Gegenstand->id > 0) {
                $sql2 = "SELECT id FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Werkstatt' AND Puppenart = '$Gegenstand->id'";
                $query2 = mysql_query($sql2);
                $Teilchen = mysql_fetch_object($query2);
                if ($Teilchen->id > 0) {
                    $Gegenstand->Preis = 0;
                }
                if ($Geldduhast >= $dorfs2->Geld) {
                    if ($Gegenstand->Anbau > 0) {
                        if ($Teilchen->id > 0) {
                            $del = "DELETE FROM Item WHERE id = '$Teilchen->id'";
                            mysql_query($del);
                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Teilchen->Item $Teilchen->Menge gelöscht', '$Date')";
                            mysql_query($ins);
                        }
                        $up = "UPDATE user SET Geld = Geld-$Gegenstand->Preis WHERE id = '$c_loged'";
                        mysql_query($up);
                        $sql = "SELECT $Kaufen, Art FROM Marionetten WHERE id = '$Marionette->id'";
                        $query = mysql_query($sql);
                        $Mario = mysql_fetch_object($query);
                        $Gegenstander = $Mario->$Kaufen;
                        $Ort = $Kaufen;
                        $Orte = strpos($Ort, "Hand") ? 'Hand' : (strpos($Ort, "Arm") ? 'Arm' : 'Gelenk');
                        $sql = "SELECT id, Name FROM Marionettenteile WHERE Name = '$Gegenstander' AND Artpuppe = '$Mario->Art' AND Ort = '$Ort'";
                        $query = mysql_query($sql);
                        $Gegenstand2 = mysql_fetch_object($query);
                        $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Puppenart) VALUES ('$c_loged', '$Gegenstand2->Name', '1', 'Werkstatt', '$Gegenstand2->id')";
                        mysql_query($ins);
                        $up = "UPDATE Marionetten SET $Kaufen = '$Gegenstand->Name' WHERE id = '$Marionette->id'";
                        mysql_query($up);

                        echo "Du hast die Vorrichtung angebracht.<br>
<a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
                    } else {
                        if ($Teilchen->id > 0) {
                            $del = "DELETE FROM Item WHERE id = '$Teilchen->id'";
                            mysql_query($del);
                            $Date = date("d.m.Y, H:i");
                            $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Teilchen->Item $Teilchen->Menge gelöscht', '$Date')";
                            mysql_query($ins);
                        }
                        $Kaufen = str_replace("Fu%DFlinks", "Fusslinks", $Kaufen);
                        $Kaufen = str_replace("Fußlinks", "Fusslinks", $Kaufen);
                        $Kaufen = str_replace("Fu%DFrechts", "Fussrechts", $Kaufen);
                        $Kaufen = str_replace("Fußrechts", "Fussrechts", $Kaufen);

                        $up = "UPDATE user SET Geld = Geld-$Preisecht WHERE id = '$c_loged'";
                        mysql_query($up);
                        $up = "UPDATE Marionetten SET Strmulti = Strmulti+$Gegenstand->Stärke WHERE id = '$Marionette->id'";
                        mysql_query($up);
                        $up = "UPDATE Marionetten SET Vertmulti = Vertmulti+$Gegenstand->Verteidigung WHERE id = '$Marionette->id'";
                        mysql_query($up);
                        $up = "UPDATE Marionetten SET Gesmulti = Gesmulti+$Gegenstand->Geschwindigkeit WHERE id = '$Marionette->id'";
                        mysql_query($up);
                        $Itemnun = $Marionette->$Kaufen;
                        $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Itemnun' AND Artpuppe = '$Marionette->Art'";
                        $query = mysql_query($sql);
                        $Gegenstandold = mysql_fetch_object($query);
                        $up = "UPDATE Marionetten SET Strmulti = Strmulti-$Gegenstandold->Stärke WHERE id = '$Marionette->id'";
                        mysql_query($up);
                        $up = "UPDATE Marionetten SET Vertmulti = Vertmulti-$Gegenstandold->Verteidigung WHERE id = '$Marionette->id'";
                        mysql_query($up);
                        $up = "UPDATE Marionetten SET Gesmulti = Gesmulti-$Gegenstandold->Geschwindigkeit WHERE id = '$Marionette->id'";
                        mysql_query($up);

                        $up = "UPDATE Marionetten SET $Kaufen = '$Gegenstand->Name' WHERE id = '$Marionette->id'";
                        mysql_query($up);


                        echo "<b>$Marionette->Name</b> wurde mit der Vorrichtung \"<b>$Gegenstand->Name</b>\" ausgerüstet!<br>
<a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
                    }
                } else {
                    echo "Du kannst dir diese Vorrichtung nicht leisten!<br><a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
                }
            } else {
                echo "Diesen Gegenstand gibt es für die gewählte Puppenart nicht!<br><a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
            }
        } else {
            echo "Diese Puppe gehört nicht dir!<br><a href='Mario.php?Verwalt=$Marionette->id'>Zurück</a>";
        }
    } elseif ($Inhalt) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->$Inhalt;

            $Ort = "$Inhalt";
            $Orte = strpos($Inhalt, "Hand");
            if ($Orte === false) {
            } else {
                $Ort = "Hand";
            }
            $Orte = strpos($Inhalt, "Arm");
            if ($Orte === false) {
            } else {
                $Ort = "Arm";
            }
            $Orte = strpos($Inhalt, "Gelenk");
            if ($Orte === false) {
            } else {
                $Ort = "Gelenk";
            }

            $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Gegenstand' AND Artpuppe = '$Marionette->Art' AND Ort = '$Ort'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $Platzo = 0;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$Inhalt'";
                $query = mysql_query($sql);
                while ($Platz = mysql_fetch_object($query)) {
                    $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                    $query2 = mysql_query($sql2);
                    $Stack = mysql_fetch_object($query2);
                    $Stacksize = $Stack->Platz;
                    $Stack = $Stack->Stackmenge;
                    if ($Stack > 1) {
                        $Menge = $Platz->Menge / $Stack;
                        $Menge = ceil($Menge);
                        $Menge *= $Stacksize;
                        $Platzo += $Menge;
                    } else {
                        $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Itemsk2 = mysql_fetch_object($query2);
                        $Platzo += $Platz->Menge * $Itemsk2->Platz;
                    }
                }
                $Platze = $Gegenstand->Platzdrin - $Platzo;
                echo "<center><b>$Gegenstand->Name - $Platzo/$Gegenstand->Platzdrin Plätzen belegt</b>";
                echo "<table border='0' cellpadding='0' cellspacing='0'>";
                $New = 1;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$Inhalt' ORDER BY Gross DESC";
                $query = mysql_query($sql);
                while ($Items = mysql_fetch_object($query)) {
                    $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Items->Item'";
                    $query2 = mysql_query($sql2);
                    $Itemsk = mysql_fetch_object($query2);
                    $Anzahl = $Items->Menge;
                    while ($Anzahl > 0) {
                        $Stuck += 1;
                        if ($New == 1) {
                            if ($Stuck > 10) {
                                echo "<tr></tr><tr>";
                                $New = 0;
                                $Stuck = $Rowplus + 1;
                                $Rowplus = $Rowplus2;
                                $Rowplus2 = 0;
                            } else {
                                echo "<tr>";
                                $New = 0;
                            }
                        }
                        if ($Itemsk->Platz == 1) {
                            $colspan = 1;
                            $rowspan = 1;
                            $Art = "1x1";
                            $Hoehe = 65;
                            $Breite = 65;
                        } elseif ($Itemsk->Platz == 2) {
                            $Rowplus += 1;
                            $colspan = 1;
                            $rowspan = 2;
                            $Art = "1x2";
                            $Hoehe = 130;
                            $Breite = 65;
                        } elseif ($Itemsk->Platz == 3) {
                            $Rowplus += 1;
                            $colspan = 1;
                            $rowspan = 3;
                            $Rowplus2 += 1;
                            $Art = "1x3";
                            $Hoehe = 195;
                            $Breite = 65;
                        } elseif ($Itemsk->Platz == 4) {
                            $Rowplus += 2;
                            $colspan = 2;
                            $rowspan = 2;
                            $Stuck += 1;
                            $Art = "2x2";
                            $Hoehe = 130;
                            $Breite = 130;
                        }
                        if ($Itemsk->Stackmenge > 0) {
                            if ($Anzahl >= $Itemsk->Stackmenge) {
                                $ZahlanItems = $Itemsk->Stackmenge;
                            } else {
                                $ZahlanItems = $Anzahl;
                            }
                            $Variablekurz = str_replace(" ", "", $Itemsk->Name);
                            $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                            $Variablekurz = str_replace("\"", "", $Variablekurz);
                            $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                            $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                            $Variablekurz = str_replace("ß", "ss", $Variablekurz);
                            $Bild = "Item/Itembilder/$Variablekurz/1.png";
                            echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\" width='$Breite' height='$Hoehe' align='center' valign='top'>";

                            echo "<div style=\"position:relative;\">";

                            echo "<div style=\"position:absolute; left:0" . "px; top:0" . "px; z-index: 1;\" align='center'>";
                            echo "<a href='Mario.php?VerwaltV=$Items->id'>";
                            echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                            echo "</a>";
                            echo "</div>";

                            $DiffX = $Itemsk->BildX / $Breite;
                            $DiffY = $Itemsk->BildY / $Hoehe;

                            if ($DiffX > $DiffY and $DiffX > 1) {
                                $BreitItem = floor($Itemsk->BildX / $DiffX);
                                $HoeheItem = floor($Itemsk->BildY / $DiffX);
                            } elseif ($DiffY > 1) {
                                $BreitItem = floor($Itemsk->BildX / $DiffY);
                                $HoeheItem = floor($Itemsk->BildY / $DiffY);
                            } else {
                                $BreitItem = $Itemsk->BildX;
                                $HoeheItem = $Itemsk->BildY;
                            }
                            $PosX = ($Breite - $BreitItem) / 2;
                            $PosX = floor($PosX);
                            $PosY = ($Hoehe - $HoeheItem) / 2;
                            $PosY = floor($PosY);

                            echo "<div style=\"position:absolute; left:$PosX" . "px; top:$PosY" . "px; z-index: 2;\" align='center'>";
                            echo "<a href='Mario.php?VerwaltV=$Items->id'>
<img width='$BreitItem' height='$HoeheItem'";

                            if ($Items->FarbeRahmen == "") {
                                echo "border='0'";
                            } else {
                                echo "style='border:3px solid $Items->FarbeRahmen'";
                            }

                            echo " src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                            echo "</div>";

                            if ($ZahlanItems > 1) {
                                echo "<div style=\"position:absolute; left:5" . "px; top:5" . "px; z-index: 3;\" align='center'>";
                                echo "<b><font color='#cc0033'>x$ZahlanItems</font></b>";
                                echo "</div>";
                            }

                            echo "</div>";

                            echo "</td>";
                            $Minus = $ZahlanItems;
                        } else {
                            $Variablekurz = str_replace(" ", "", $Itemsk->Name);
                            $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                            $Variablekurz = str_replace("\"", "", $Variablekurz);
                            $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                            $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                            $Variablekurz = str_replace("ß", "ss", $Variablekurz);
                            $Bild = "Item/Itembilder/$Variablekurz/1.png";
                            echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\" width='$Breite' height='$Hoehe' align='center' valign='top'>";

                            echo "<div style=\"position:relative;\">";

                            echo "<div style=\"position:absolute; left:0" . "px; top:0" . "px; z-index: 1;\" align='center'>";
                            echo "<a href='Mario.php?VerwaltV=$Items->id'>";
                            echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                            echo "</a>";
                            echo "</div>";

                            $DiffX = $Itemsk->BildX / $Breite;
                            $DiffY = $Itemsk->BildY / $Hoehe;

                            if ($DiffX > $DiffY and $DiffX > 1) {
                                $BreitItem = floor($Itemsk->BildX / $DiffX);
                                $HoeheItem = floor($Itemsk->BildY / $DiffX);
                            } elseif ($DiffY > 1) {
                                $BreitItem = floor($Itemsk->BildX / $DiffY);
                                $HoeheItem = floor($Itemsk->BildY / $DiffY);
                            } else {
                                $BreitItem = $Itemsk->BildX;
                                $HoeheItem = $Itemsk->BildY;
                            }
                            $PosX = ($Breite - $BreitItem) / 2;
                            $PosX = floor($PosX);
                            $PosY = ($Hoehe - $HoeheItem) / 2;
                            $PosY = floor($PosY);

                            echo "<div style=\"position:absolute; left:$PosX" . "px; top:$PosY" . "px; z-index: 2;\" align='center'>";
                            echo "<a href='Mario.php?VerwaltV=$Items->id'>
<img width='$BreitItem' height='$HoeheItem'";

                            if ($Items->FarbeRahmen == "") {
                                echo "border='0'";
                            } else {
                                echo "style='border:3px solid $Items->FarbeRahmen'";
                            }

                            echo " src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                            echo "</div>";

                            echo "</div>";

                            echo "</td>";
                            $Minus = 1;
                        }
                        if ($Stuck >= 10) {
                            echo "</tr>";
                            $Stuck = $Rowplus;
                            $Rowplus = $Rowplus2;
                            $Rowplus2 = 0;
                            $New = 1;
                        }
                        $Anzahl -= $Minus;
                    }
                }
                while ($Platze > 0) {
                    $Stuck += 1;
                    if ($New == 1) {
                        if ($Stuck > 10) {
                            echo "<tr></tr><tr>";
                            $New = 0;
                            $Stuck = $Rowplus + 1;
                            $Rowplus = $Rowplus2;
                            $Rowplus2 = 0;
                        } else {
                            echo "<tr>";
                            $New = 0;
                        }
                    }
                    echo "<td><img border='0' src='Bilder/Inventar/Grundlagen/1x1.png'></td>";
                    if ($Stuck >= 10) {
                        echo "</tr>";
                        $Stuck = $Rowplus;
                        $Rowplus = $Rowplus2;
                        $Rowplus2 = 0;
                        $New = 1;
                    }
                    $Platze -= 1;
                }
                echo "</table><br>";
                echo "<a href='Mario.php?reintun=$Inhalt&Puppe=$Marionette->id'>Gegenstände hinzufügen</a><br>";
                echo "<a href='Mario.php?Modifizieren=$Inhalt&Puppe=$Marionette->id'>Zurück</a><br>";
            }
        }
    } elseif ($reintun) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->$reintun;

            $Ort = "$reintun";
            $Orte = strpos($reintun, "Hand");
            if ($Orte === false) {
            } else {
                $Ort = "Hand";
            }
            $Orte = strpos($reintun, "Arm");
            if ($Orte === false) {
            } else {
                $Ort = "Arm";
            }
            $Orte = strpos($reintun, "Gelenk");
            if ($Orte === false) {
            } else {
                $Ort = "Gelenk";
            }

            $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Gegenstand' AND Artpuppe = '$Marionette->Art' AND Ort = '$Ort'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $Platzo = 0;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$reintun'";
                $query = mysql_query($sql);
                while ($Platz = mysql_fetch_object($query)) {
                    $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                    $query2 = mysql_query($sql2);
                    $Stack = mysql_fetch_object($query2);
                    $Stacksize = $Stack->Platz;
                    $Stack = $Stack->Stackmenge;
                    if ($Stack > 1) {
                        $Menge = $Platz->Menge / $Stack;
                        $Menge = ceil($Menge);
                        $Menge *= $Stacksize;
                        $Platzo += $Menge;
                    } else {
                        $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Itemsk2 = mysql_fetch_object($query2);
                        $Platzo += $Platz->Menge * $Itemsk2->Platz;
                    }
                }
                $Platze = $Gegenstand->Platzdrin - $Platzo;
                echo "<b>Gegenstände in die Vorrichtung packen:</b><br><br>";
                echo "<table border='0' cellpadding='0' cellspacing='0'>";
                $New = 1;
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = '' ORDER BY Gross DESC";
                $query = mysql_query($sql);
                while ($Items = mysql_fetch_object($query)) {
                    $okay = 0;
                    $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Items->Item'";
                    $query2 = mysql_query($sql2);
                    $Itemsk = mysql_fetch_object($query2);
                    $pos = strpos($Gegenstand->Nurrein, "$Items->Item");
                    if ($pos === false) {
                    } else {
                        $okay = 1;
                    }
                    if ($Gegenstand->Nurrein == "Alles") {
                        $okay = 1;
                    }
                    if ($okay == 1) {
                        $Anzahl = $Items->Menge;
                        while ($Anzahl > 0) {
                            $Stuck += 1;
                            if ($New == 1) {
                                if ($Stuck > 10) {
                                    echo "<tr></tr><tr>";
                                    $New = 0;
                                    $Stuck = $Rowplus + 1;
                                    $Rowplus = $Rowplus2;
                                    $Rowplus2 = 0;
                                } else {
                                    echo "<tr>";
                                    $New = 0;
                                }
                            }
                            if ($Itemsk->Platz == 1) {
                                $colspan = 1;
                                $rowspan = 1;
                                $Art = "1x1";
                                $Hoehe = 65;
                                $Breite = 65;
                            } elseif ($Itemsk->Platz == 2) {
                                $Rowplus += 1;
                                $colspan = 1;
                                $rowspan = 2;
                                $Art = "1x2";
                                $Hoehe = 130;
                                $Breite = 65;
                            } elseif ($Itemsk->Platz == 3) {
                                $Rowplus += 1;
                                $colspan = 1;
                                $rowspan = 3;
                                $Rowplus2 += 1;
                                $Art = "1x3";
                                $Hoehe = 195;
                                $Breite = 65;
                            } elseif ($Itemsk->Platz == 4) {
                                $Rowplus += 2;
                                $colspan = 2;
                                $rowspan = 2;
                                $Stuck += 1;
                                $Art = "2x2";
                                $Hoehe = 130;
                                $Breite = 130;
                            }
                            if ($Itemsk->Stackmenge > 0) {
                                if ($Anzahl >= $Itemsk->Stackmenge) {
                                    $ZahlanItems = $Itemsk->Stackmenge;
                                } else {
                                    $ZahlanItems = $Anzahl;
                                }
                                $Variablekurz = str_replace(" ", "", $Itemsk->Name);
                                $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                                $Variablekurz = str_replace("\"", "", $Variablekurz);
                                $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                                $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                                $Variablekurz = str_replace("ß", "ss", $Variablekurz);
                                $Bild = "Item/Itembilder/$Variablekurz/1.png";
                                echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\" width='$Breite' height='$Hoehe' align='center' valign='top'>";

                                echo "<div style=\"position:relative;\">";

                                echo "<div style=\"position:absolute; left:0" . "px; top:0" . "px; z-index: 1;\" align='center'>";
                                echo "<a href='Mario.php?rein=$reintun&Puppe=$Marionette->id&Item=$Items->id'>";
                                echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                                echo "</a>";
                                echo "</div>";

                                $DiffX = $Itemsk->BildX / $Breite;
                                $DiffY = $Itemsk->BildY / $Hoehe;

                                if ($DiffX > $DiffY and $DiffX > 1) {
                                    $BreitItem = floor($Itemsk->BildX / $DiffX);
                                    $HoeheItem = floor($Itemsk->BildY / $DiffX);
                                } elseif ($DiffY > 1) {
                                    $BreitItem = floor($Itemsk->BildX / $DiffY);
                                    $HoeheItem = floor($Itemsk->BildY / $DiffY);
                                } else {
                                    $BreitItem = $Itemsk->BildX;
                                    $HoeheItem = $Itemsk->BildY;
                                }
                                $PosX = ($Breite - $BreitItem) / 2;
                                $PosX = floor($PosX);
                                $PosY = ($Hoehe - $HoeheItem) / 2;
                                $PosY = floor($PosY);

                                echo "<div style=\"position:absolute; left:$PosX" . "px; top:$PosY" . "px; z-index: 2;\" align='center'>";
                                echo "<a href='Mario.php?rein=$reintun&Puppe=$Marionette->id&Item=$Items->id'>
<img width='$BreitItem' height='$HoeheItem'";

                                if ($Items->FarbeRahmen == "") {
                                    echo "border='0'";
                                } else {
                                    echo "style='border:3px solid $Items->FarbeRahmen'";
                                }

                                echo " src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                                echo "</div>";

                                if ($ZahlanItems > 1) {
                                    echo "<div style=\"position:absolute; left:5" . "px; top:5" . "px; z-index: 3;\" align='center'>";
                                    echo "<b><font color='#cc0033'>x$ZahlanItems</font></b>";
                                    echo "</div>";
                                }

                                echo "</div>";

                                echo "</td>";
                                $Minus = $ZahlanItems;
                            } else {
                                $Variablekurz = str_replace(" ", "", $Itemsk->Name);
                                $Variablekurz = str_replace("ä", "ae", $Variablekurz);
                                $Variablekurz = str_replace("\"", "", $Variablekurz);
                                $Variablekurz = str_replace("ö", "oe", $Variablekurz);
                                $Variablekurz = str_replace("ü", "ue", $Variablekurz);
                                $Variablekurz = str_replace("ß", "ss", $Variablekurz);
                                $Bild = "Item/Itembilder/$Variablekurz/1.png";
                                echo "<td colspan=\"$colspan\" rowspan=\"$rowspan\" width='$Breite' height='$Hoehe' align='center' valign='top'>";

                                echo "<div style=\"position:relative;\">";

                                echo "<div style=\"position:absolute; left:0" . "px; top:0" . "px; z-index: 1;\" align='center'>";
                                echo "<a href='Mario.php?rein=$reintun&Puppe=$Marionette->id&Item=$Items->id'>";
                                echo "<img src='/Bilder/Inventar/Grundlagen/$Art" . ".png' width='$Breite' height='$Hoehe'>";
                                echo "</a>";
                                echo "</div>";

                                $DiffX = $Itemsk->BildX / $Breite;
                                $DiffY = $Itemsk->BildY / $Hoehe;

                                if ($DiffX > $DiffY and $DiffX > 1) {
                                    $BreitItem = floor($Itemsk->BildX / $DiffX);
                                    $HoeheItem = floor($Itemsk->BildY / $DiffX);
                                } elseif ($DiffY > 1) {
                                    $BreitItem = floor($Itemsk->BildX / $DiffY);
                                    $HoeheItem = floor($Itemsk->BildY / $DiffY);
                                } else {
                                    $BreitItem = $Itemsk->BildX;
                                    $HoeheItem = $Itemsk->BildY;
                                }
                                $PosX = ($Breite - $BreitItem) / 2;
                                $PosX = floor($PosX);
                                $PosY = ($Hoehe - $HoeheItem) / 2;
                                $PosY = floor($PosY);

                                echo "<div style=\"position:absolute; left:$PosX" . "px; top:$PosY" . "px; z-index: 2;\" align='center'>";
                                echo "<a href='Mario.php?rein=$reintun&Puppe=$Marionette->id&Item=$Items->id'>
<img width='$BreitItem' height='$HoeheItem'";

                                if ($Items->FarbeRahmen == "") {
                                    echo "border='0'";
                                } else {
                                    echo "style='border:3px solid $Items->FarbeRahmen'";
                                }

                                echo " src='Bilder/Inventar/$Bild' alt='$Itemsk->Name - $Items->Menge Stk.'></a>";
                                echo "</div>";

                                echo "</div>";

                                echo "</td>";
                                $Minus = 1;
                            }
                            if ($Stuck >= 10) {
                                echo "</tr>";
                                $Stuck = $Rowplus;
                                $Rowplus = $Rowplus2;
                                $Rowplus2 = 0;
                                $New = 1;
                            }
                            $Anzahl -= $Minus;
                        }
                    }
                }
                echo "</table><br><a href='Mario.php?Puppe=$Marionette->id&Inhalt=$reintun'>Zurück</a>";
            }
        }
    } elseif ($rein) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->$rein;

            $Ort = "$rein";
            $Orte = strpos($rein, "Hand");
            if ($Orte === false) {
            } else {
                $Ort = "Hand";
            }
            $Orte = strpos($rein, "Arm");
            if ($Orte === false) {
            } else {
                $Ort = "Arm";
            }
            $Orte = strpos($rein, "Gelenk");
            if ($Orte === false) {
            } else {
                $Ort = "Gelenk";
            }

            $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Gegenstand' AND Artpuppe = '$Marionette->Art' AND Ort = '$Ort'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $sql = "SELECT * FROM Item WHERE id = '$Item' AND Von = '$c_loged'";
                $query = mysql_query($sql);
                $Itemrein = mysql_fetch_object($query);
                $okay = 0;
                $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Itemrein->Item'";
                $query2 = mysql_query($sql2);
                $Itemsk = mysql_fetch_object($query2);
                $okay = 0;
                $pos = strpos($Gegenstand->Nurrein, "$Itemrein->Item");
                if ($pos === false) {
                } else {
                    $okay = 1;
                }
                if ($Gegenstand->Nurrein == "Alles") {
                    $okay = 1;
                }
                if ($okay == 1) {
                    $Platzo = 0;
                    $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$rein'";
                    $query = mysql_query($sql);
                    while ($Platz = mysql_fetch_object($query)) {
                        $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Stack = mysql_fetch_object($query2);
                        $Stacksize = $Stack->Platz;
                        $Stack = $Stack->Stackmenge;
                        if ($Stack > 1) {
                            $Menge = $Platz->Menge / $Stack;
                            $Menge = ceil($Menge);
                            $Menge *= $Stacksize;
                            $Platzo += $Menge;
                        } else {
                            $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                            $query2 = mysql_query($sql2);
                            $Itemsk2 = mysql_fetch_object($query2);
                            $Platzo += $Platz->Menge * $Itemsk2->Platz;
                        }
                    }
                    $Platze = $Gegenstand->Platzdrin - $Platzo;

                    echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">";
                    echo "<!--
function mogo()
{
var Platzbraucht = eval(document.Verwalt.Mengeraustu.options[document.Verwalt.Mengeraustu.selectedIndex].value);
var Item = \"$Itemsk->Stackmenge\";
if (Item > 0)
{
var Platzbrauchen = Platzbraucht / $Itemsk->Stackmenge;
var Platzbrauchen = Math.ceil(Platzbrauchen);
var Platzbrauchen = Platzbrauchen * $Itemsk->Platz;
}
else
{
var Platzbrauchen = Platzbraucht * $Itemsk->Platz;
}
PlatzinTasche.innerHTML = Platzbrauchen;

}
//--></SCRIPT>";

                    echo "<center><form method='POST' name='Verwalt' action='Mario.php?reinpack=$rein&Item=$Item&Puppe=$Marionette->id'>";
                    echo "<table border='0'>";
                    echo "<tr>";
                    echo "<td><b>Vorrichtung</b>:</td>";
                    echo "<td>$Gegenstand->Name</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Platz</b>:</td>";
                    echo "<td>$Platze</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Item</b>:</td>";
                    echo "<td>$Itemrein->Item</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Menge</b>:</td>";
                    echo "<td>$Itemrein->Menge Stk.</td>";
                    echo "</tr>";


                    if ($Itemsk->Stackmenge > 0) {
                        $Platz = $Itemrein->Menge / $Itemsk->Stackmenge;
                        $Platz = ceil($Platz);
                        $Platz *= $Itemsk->Platz;
                    } else {
                        $Platz = $Itemrein->Menge * $Itemsk->Platz;
                    }

                    echo "<tr>";
                    echo "<td><b>Platverbrauch</b>:</td>";
                    echo "<td><div id='PlatzinTasche' name='Platzintasche'>$Platz</div></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Davon</b></td>";
                    echo "<td><select name='Mengeraustu' onchange='mogo()'>";
                    $Menge = $Itemrein->Menge;
                    while ($Menge > 0) {
                        echo "<option value='$Menge'>$Menge";
                        $Menge -= 1;
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='2'><input type='submit' value='in die Tasche packen'></td>";
                    echo "</tr>";
                    echo "</table></center>";
                }
            }
        }
    } elseif ($reinpack) {
        $sql = "SELECT * FROM Marionetten WHERE id = '$Puppe' AND Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            $Gegenstand = $Marionette->$reinpack;
            $Ort = "$reinpack";
            $Orte = strpos($reinpack, "Hand");
            if ($Orte === false) {
            } else {
                $Ort = "Hand";
            }
            $Orte = strpos($reinpack, "Arm");
            if ($Orte === false) {
            } else {
                $Ort = "Arm";
            }
            $Orte = strpos($reinpack, "Gelenk");
            if ($Orte === false) {
            } else {
                $Ort = "Gelenk";
            }

            $sql = "SELECT * FROM Marionettenteile WHERE Name = '$Gegenstand' AND Artpuppe = '$Marionette->Art' AND Ort = '$Ort'";
            $query = mysql_query($sql);
            $Gegenstand = mysql_fetch_object($query);
            if ($Gegenstand->id > 0 and $Gegenstand->Platzdrin > 0) {
                $sql = "SELECT * FROM Item WHERE id = '$Item' AND Von = '$c_loged'";
                $query = mysql_query($sql);
                $Itemrein = mysql_fetch_object($query);
                $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Itemrein->Item'";
                $query2 = mysql_query($sql2);
                $Itemsk = mysql_fetch_object($query2);
                $okay = 0;
                $pos = strpos($Gegenstand->Nurrein, "$Itemrein->Item");
                if ($pos === false) {
                } else {
                    $okay = 1;
                }
                if ($Gegenstand->Nurrein == "Alles") {
                    $okay = 1;
                }
                if ($okay == 1) {
                    $Platzo = 0;
                    $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$reinpack'";
                    $query = mysql_query($sql);
                    while ($Platz = mysql_fetch_object($query)) {
                        $sql2 = "SELECT Stackmenge, Platz FROM Itemsk WHERE Name = '$Platz->Item'";
                        $query2 = mysql_query($sql2);
                        $Stack = mysql_fetch_object($query2);
                        $Stacksize = $Stack->Platz;
                        $Stack = $Stack->Stackmenge;
                        if ($Stack > 1) {
                            $Menge = $Platz->Menge / $Stack;
                            $Menge = ceil($Menge);
                            $Menge *= $Stacksize;
                            $Platzo += $Menge;
                        } else {
                            $sql2 = "SELECT * FROM Itemsk WHERE Name = '$Platz->Item'";
                            $query2 = mysql_query($sql2);
                            $Itemsk2 = mysql_fetch_object($query2);
                            $Platzo += $Platz->Menge * $Itemsk2->Platz;
                        }
                    }
                    $Platze = $Gegenstand->Platzdrin - $Platzo;
                    if ($Itemsk->Stackmenge > 0) {
                        $Platz = $Mengeraustu / $Itemsk->Stackmenge;
                        $Platz = ceil($Platz);
                        $Platz *= $Itemsk->PLatz;
                    } else {
                        $Platz = $Mengeraustu * $Itemsk->Platz;
                    }
                    if ($Platz > $Platze) {
                        echo "Du hast nicht genug Platz ($Platze) in der Vorrichtung!<br><a href='Mario.php?Puppe=$Marionette->id&Inhalt=$reinpack'>Zurück</a>";
                    } else {
                        if ($Mengeraustu <= $Itemrein->Menge and $Mengeraustu > 0) {
                            $sql = "SELECT * FROM Item WHERE Item = '$Itemrein->Item' AND Von = '$c_loged' AND Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$reinpack'";
                            $query = mysql_query($sql);
                            $Itemda = mysql_fetch_object($query);
                            if ($Itemda->id > 0 and $Itemsk->NonStack != 1) {
                                if ($Mengeraustu == $Itemrein->Menge) {
                                    $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Itemda->id'";
                                    mysql_query($up);
                                    $del = "DELETE FROM Item WHERE id = '$Itemrein->id'";
                                    mysql_query($del);
                                    $Date = date("d.m.Y, H:i");
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemda->Item $Itemda->Menge+$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemrein->Item $Itemrein->Menge gelöscht', '$Date')";
                                    mysql_query($ins);
                                } else {
                                    $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Itemda->id'";
                                    mysql_query($up);
                                    $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Itemrein->id'";
                                    mysql_query($up);

                                    $Date = date("d.m.Y, H:i");
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemda->Item $Itemda->Menge+$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                    $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Itemrein->Item $Itemrein->Menge-$Mengeraustu', '$Date')";
                                    mysql_query($ins);
                                }
                            } else {
                                if ($Mengeraustu == $Itemrein->Menge) {
                                    $up = "UPDATE Item SET Angelegt = 'Puppe:$Marionette->id|$Gegenstand->id|$reinpack' WHERE id = '$Itemrein->id'";
                                    mysql_query($up);
                                } else {
                                    $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Itemrein->Item', '$Mengeraustu', 'Puppe:$Marionette->id|$Gegenstand->id|$reinpack', '$Itemrein->Gross', '$Itemrein->Ausdauerhalt', '$Itemrein->Beschrankunghalt', '$Itemrein->Bluthalt')";
                                    mysql_query($ins);
                                    $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Itemrein->id'";
                                    mysql_query($up);
                                }
                            }

                            echo "$Itemrein->Item wurde in die Vorrichtung getan!<br><a href='Mario.php?Puppe=$Marionette->id&Inhalt=$reinpack'>Zurück zur Vorrichtung</a><br>
<a href='Mario.php?Verwalt=$Marionette->id'>Zurück zur Puppe</a>";
                        }
                    }
                }
            }
        }
    } elseif ($VerwaltV) {
        $sql = "SELECT * FROM Item WHERE id = '$VerwaltV' AND Von = '$c_loged'";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemsk = mysql_fetch_object($query);
        if ($Item->id > 0) {
            echo "<center>";
            if ($Itemsk->Platzdrin > 0) {
                echo "<a href='Inventar.php?Tasche=$Item->id'>Inhalt dieses Items betrachten</a><br><br>";
            }
            echo "<form method='POST' action='Mario.php?VerwaltV2=$Item->id'>";
            echo "<table border='0'>";
            echo "<tr>";
            echo "<td><b>Item</b>:</td>";
            echo "<td>$Item->Item</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Menge</b>:</td>";
            echo "<td>$Item->Menge Stk.</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Davon</b></td>";
            echo "<td><select name='Mengeraustu'>";
            $Menge = $Item->Menge;
            while ($Menge > 0) {
                echo "<option>$Menge";
                $Menge -= 1;
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2'><input type='submit' value='aus der Vorrichtung entfernen'></td>";
            echo "</tr>";
            echo "</table></center>";
        }
    } elseif ($VerwaltV2) {
        $sql = "SELECT * FROM Item WHERE id = '$VerwaltV2' AND Von = '$c_loged' AND Angelegt != ''";
        $query = mysql_query($sql);
        $Item = mysql_fetch_object($query);
        $sql = "SELECT * FROM Itemsk WHERE Name = '$Item->Item'";
        $query = mysql_query($sql);
        $Itemsk = mysql_fetch_object($query);
        if ($Item->id > 0) {
            if ($Item->Menge < $Mengeraustu or $Mengeraustu < 1) {
            } else {
                $sql = "SELECT * FROM Item WHERE Von = '$c_loged' AND Item = '$Item->Item' AND Angelegt = ''";
                $query = mysql_query($sql);
                $Item2 = mysql_fetch_object($query);
                if ($Item2->id > 0 and $Itemsk->NonStack != 1) {
                    if ($Item->Menge == $Mengeraustu) {
                        $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Item2->id'";
                        mysql_query($up);
                        $del = "DELETE FROM Item WHERE id = '$Item->id'";
                        mysql_query($del);

                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item2->Item $Item2->Menge+$Mengeraustu', '$Date')";
                        mysql_query($ins);
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge gelöscht', '$Date')";
                        mysql_query($ins);
                    } else {
                        $up = "UPDATE Item SET Menge = Menge+$Mengeraustu WHERE id = '$Item2->id'";
                        mysql_query($up);
                        $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Item->id'";
                        mysql_query($up);

                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item2->Item $Item2->Menge+$Mengeraustu', '$Date')";
                        mysql_query($ins);
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge-$Mengeraustu', '$Date')";
                        mysql_query($ins);
                    }
                } else {
                    if ($Item->Menge == $Mengeraustu) {
                        $up = "UPDATE Item SET Angelegt = '' WHERE id = '$Item->id'";
                        mysql_query($up);
                    } else {
                        $ins = "INSERT INTO Item (Von, Item, Menge, Angelegt, Gross, Ausdauerhalt, Beschrankunghalt, Bluthalt) VALUES ('$c_loged', '$Item->Item', '$Mengeraustu', '', '$Item->Gross', '$Item->Ausdauerhalt', '$Item->Beschrankunghalt', '$Item->Bluthalt')";
                        mysql_query($ins);
                        $up = "UPDATE Item SET Menge = Menge-$Mengeraustu WHERE id = '$Item->id'";
                        mysql_query($up);
                        $Date = date("d.m.Y, H:i");
                        $ins = "INSERT INTO Itemlog (User, Text, Datum) VALUES ('$dorfs2->id', '$Item->Item $Item->Menge-$Mengeraustu', '$Date')";
                        mysql_query($ins);
                    }
                }
                $Tasche = str_replace("Item: ", "", $Item->Angelegt);
                echo "$Item->Item wurde $Mengeraustu mal aus der Vorrichtung entfernt!<br>
<a href='Mario.php'>Zurück</a>";
            }
        }
    } elseif ($Training) {
        echo "<b>Grundwerte als Puppenspieler:</b><br><a href='Mario.php'>Zurück</a><br>";
        echo "<table border='0' width='90%'>";
        echo "<tr><td colspan='3'>";
        echo "<table border='0' width='100%'>";
        echo "<tr>";
        echo "<td width='25%' align='center'><b>Werte-Durchschnitt</b></td>";
        echo "<td width='25%' align='center'><b>Haltbarkeits-Pool</b></td>";
        echo "<td width='25%' align='center'><b>Maximal nutzbare Puppen</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td align='center'>$dorfs2->PuppeWerte</td>";
        echo "<td align='center'>$dorfs2->PuppenHaltbarkeit</td>";
        echo "<td align='center'>$dorfs2->Niveau</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td align='center' colspan='4'><b>Maximal nutzbare Vorrichtungen/Runde</b></td>";
        echo "</tr>";
        echo "<tr>";
        $MaxVorrichtungen = $dorfs2->Niveau;
        echo "<td align='center' colspan='4'>$MaxVorrichtungen</td>";
        echo "</tr>";


        echo "</table></tr></table>";

        echo "<br><b>Training</b>";

        $Bonus = 1;
        if ($dorfs2->Bonustage > 0) {
            $Bonus = 2.5;
            $sqls = "SELECT * FROM allgdata WHERE id = '1'";
            $query = mysql_query($sqls);
            $Datas = mysql_fetch_object($query);

            echo "<b>Dir stehen noch <u>$dorfs2->Bonustage</u> Bonustag(e) mit 250% Trainingswirkung zu!</b><br><br>";
        }

        echo " <form name='form3' method='post' action='Mario.php?Train=1'>
<table width='80%'  border='0'>
<tr>
<td width='30%' height='26'><b>Puppenspieler Training: Werte</b></td>
<td width='30%'>Erhöht den Werte-Durchschnitt deiner Puppen um ";
        if ($dorfs2->Niveau < 3) {
            $WertTrain = 1.5;
        } elseif ($dorfs2->Niveau < 4) {
            $WertTrain = 1.75;
        } else {
            $WertTrain = 2;
        }
        $WertTrain = $werteEnd->puppenWert($dorfs2->Niveau, 1);

        if ($Bonus > 1) {
            $WertTrain *= 2.5;
            echo "(<i>Bonus</i>) ";
        }
        $WertTrain = round($WertTrain, 2);
        echo "$WertTrain";
        echo "</td>
<td width='30%'><select name='Dauer' id='Dauer'>
";
        $Menge = 20;
        while ($Menge != 0) {
            echo "<option selected>$Menge";
            $Menge -= 1;
        }
        echo "
</select>
Tag(e)
<input type='submit' name='Submit' value='Trainieren'>
<br>Oder bis Wert <input type='text' name='Trainingbiswert' value='$dorfs2->PuppeWerte' size='3'><input type='checkbox' value='1' name='JaBiswerttrainieren'>
<input name='hiddenField' type='hidden' value='Puppenspieler Training: Werte'></td>
</tr>
</table>
</form>";

        echo "
<form name='form6' method='post' action='Mario.php?Train=1'>
<table width='80%'  border='0'>
<tr>
<td width='30%' height='26'><b>Panzerungen herstellen</b></td>
<td width='30%'>Erhöht den Haltbarkeits-Pool für deine Puppen um ";
        $WertTrain = $werteEnd->puppeVerstaerken($dorfs2->Niveau, 1);
        if ($Bonus > 1) {
            $WertTrain *= 2.5;
            echo "(<i>Bonus</i>) ";
        }
        echo "$WertTrain";
        echo "</td>
<td width='30%'><select name='Dauer' id='Dauer'>
";
        $Menge = 20;
        while ($Menge != 0) {
            echo "<option selected>$Menge";
            $Menge -= 1;
        }
        echo "
</select>
Tag(e)
<br>
<input type='submit' name='Submit' value='Trainieren'>
<br>Oder bis Wert <input type='text' name='Trainingbiswert' value='$dorfs2->PuppenHaltbarkeit' size='3'><input type='checkbox' value='1' name='JaBiswerttrainieren'>
<input name='hiddenField' type='hidden' value='Panzerungen herstellen'></td>
</tr>
</table>
</form>";
        $sql = "SELECT id FROM Marionetten WHERE Besitzer = '$c_loged' AND Haltbarkeit < Haltbarkeitmax";
        $query = mysql_query($sql);
        $Marionette = mysql_fetch_object($query);
        if ($Marionette->id > 0) {
            echo "
<form name='form7' method='post' action='?Train=1'>
<table width='80%'  border='0'>
<tr>
<td width='30%' height='26'><b>Puppe reparieren</b></td>
<td width='30%'>Repariert die Puppe komplett.";
            echo "</td>
<td width='30%'>
<select name='PuppenehmPuppereparieren'>";
            $sql = "SELECT id, Name, Haltbarkeit, Haltbarkeitmax FROM Marionetten WHERE Besitzer = '$c_loged' AND Haltbarkeit < Haltbarkeitmax";
            $query = mysql_query($sql);
            while ($Marionette = mysql_fetch_object($query)) {
                $kosten = (1 - ($Marionette->Haltbarkeit / $Marionette->Haltbarkeitmax)) * 15000;
                echo "<option value='$Marionette->id'>$Marionette->Name ($Marionette->Haltbarkeit/$Marionette->Haltbarkeitmax Kosten: $kosten)";
            }
            echo "</select>
<input type='submit' name='Submit' value='reparieren'>
<input name='hiddenField' type='hidden' value='Puppe reparieren'></td>
</tr>
</table>
</form>";
        }
    } elseif ($hiddenField == 'Puppe reparieren') {
        $kosten = (1 - ($Marionette->Haltbarkeit / $Marionette->Haltbarkeitmax)) * 15000;
        $up = "UPDATE user SET Geld = `Geld`-$kosten WHERE id = '$dorfs->id'";
        mysql_query($up) or die('Fehler beim Bezahlen der Reparatur');
        $up = "UPDATE Marionetten SET Haltbarkeit = `Haltbarkeitmax` WHERE id = '$PuppenehmPuppereparieren' AND Besitzer = '$dorfs->id'";
        mysql_query($up) or die('Fehler beim Reparieren der Puppe');
    } elseif ($Train) {
        $hiddenField = htmlentities($hiddenField);
        $Traininggeht = "|Puppenspieler Training: Werte||Panzerungen herstellen||Puppe verst&auml;rken|";
        $pos = strpos($Traininggeht, "|$hiddenField|");
        if ($pos === false) {
            echo "Du kannst das Training \"$hiddenField\" nicht absolvieren!";
        } else {
            $Puppenehm = "Puppenehm$hiddenField";
            $Puppenehm = str_replace("&auml;", "a", $Puppenehm);
            $Puppenehm = str_replace(" ", "", $Puppenehm);
            $Puppenehm = $$Puppenehm;
            if ($Dauer > 0) {
                if ($dorfs2->Training == "") {
                    $up = "UPDATE user SET Training = '$hiddenField' WHERE id = '$c_loged'";
                    mysql_query($up);

                    if ($_POST['Trainingbiswert'] > 0 and $_POST['JaBiswerttrainieren'] == 1) {
                        $_POST['Trainingbiswert'] = htmlentities($_POST['Trainingbiswert']);
                        $aendern = "UPDATE user set Dauer = '50' WHERE id = '$c_loged'";
                        mysql_query($aendern) or die("Fehler beim eintragen des Datums!");
                        $aendern = "UPDATE user set Biswert = '$_POST[Trainingbiswert]' WHERE id = '$c_loged'";
                        mysql_query($aendern) or die("Fehler beim eintragen des Datums!");
                    } else {
                        $aendern = "UPDATE user set Dauer = '$Dauer' WHERE id = '$c_loged'";
                        mysql_query($aendern) or die("Fehler beim eintragen des Datums!");
                        $aendern = "UPDATE user set Biswert = '' WHERE id = '$c_loged'";
                        mysql_query($aendern) or die("Fehler beim eintragen des Datums!");
                    }
                    if ($hiddenField == "Puppe verst&auml;rken") {
                        $up = "UPDATE user SET Trainingsadi = '$Puppenehm' WHERE id = '$c_loged'";
                        mysql_query($up);
                    }
                    echo "Du trainierst jetzt \"$hiddenField\" für $Dauer Tag(e)<br>
<a href='Mario.php?Training=1'>Zurück</a>";
                } else {
                    echo "Du hast bisher noch ein Training eingestellt, breche dieses erst ab, oder beende es, bevor du ein neues Training einstellst
<br><a href='Mario.php?Training=1'>Zurück</a>";
                }
            }
        }
    } elseif ($umbenenn) {
        $sql = "SELECT id, Name FROM Marionetten WHERE Besitzer = '$c_loged' AND id = '$umbenenn'";
        $query = mysql_query($sql);
        $Puppe = mysql_fetch_object($query);
        echo "<form method='POST' action='Mario.php?umbenennen=$Puppe->id'>Wie soll die Puppe von jetzt an heißen?<br>
<input type='text' value='$Puppe->Name' name='PName'><br>
<input type='submit' value='Umbenennen'></form>";
    } elseif ($umbenennen) {
        $PName = htmlentities($PName);
        $up = "UPDATE Marionetten SET Name = '$PName' WHERE id = '$umbenennen' AND Besitzer = '$c_loged'";
        mysql_query($up);
        echo "Puppe wurde erfolgreich umbenannt!<br>
<a href='Mario.php'>Zurück</a>";
    } elseif ($NeuePuppe) {
        echo "<form method='POST' action='Mario.php?NeueP=1'>
<b>Name:</b> <input type='text' name='PName' value='Puppe'><br>
<b>Typ:</b> <select name='PTyp'><option>Zweibeinig<option>Mehrbeinig</select><br>
<b>Kosten:</b> 15000 Ryô<br>
<b>Dauer:</b> 2 Tage
<input type='submit' value='Neue Puppe bauen'></form>";
    } elseif ($NeueP) {
        $Kosten = 15000;
        if ($dorfs2->Geld >= $Kosten) {
            $up = "UPDATE user SET Geld = Geld-$Kosten WHERE id = '$c_loged'";
            mysql_query($up);
            if ($Art == "Zweibeinig") {
                $insert = "('$PName', '$PTyp', '$c_loged', '', '', 'N.V.', 'N.V.', '', 'N.V.', 'N.V.', 'Kugelgelenk', 'N.V.', 'N.V.', '', 'N.V.', 'N.V.', '', 'N.V.', 'N.V.', 'Kugelgelenk', 'N.V.', 'N.V.', '', '', '', '1', '1', '1', 5, 5, '', 'Standartbauweise', 'Kugelgelenk', 'Kugelgelenk', '2')";
            } else {
                $insert = "('$PName', '$PTyp', '$c_loged', '', '', 'N.V.', 'N.V.', '', 'N.V.', 'N.V.', 'Kugelgelenk', 'N.V.', 'N.V.', '', '', '', '', '', '', 'Kugelgelenk', '', '', '', '', '', '1', '1', '1', 5, 5, '', 'Standartbauweise', '', '', '2')";
            }
            $ins = "INSERT INTO `Marionetten` (`Name`, `Art`, `Besitzer`, `Kopf`, `Handlinksoben`, `Handlinksmitte`, `Handlinksunten`, `Armlinksoben`, `Armlinksmitte`, `Armlinksunten`, `Gelenklinksoben`, `Gelenklinksmitte`, `Gelenklinksunten`, `Handrechtsoben`, `Handrechtsmitte`, `Handrechtsunten`, `Armrechtsoben`, `Armrechtsmitte`, `Armrechtsunten`, `Gelenkrechtsoben`, `Gelenkrechtsmitte`, `Gelenkrechtsunten`, `Hals`, `Bauch`, `Taille`, `Strmulti`, `Vertmulti`, `Gesmulti`, `Haltbarkeit`, `Haltbarkeitmax`, `Spezial1`, `Spezial2`, `Gelenklinks`, `Gelenkrechts`, `Arme`) VALUES" . $insert;
            mysql_query($ins) or die("Error1");
            echo "Du hast eine neue Puppe gebaut!<br>";
        } else {
            echo "Du hast nicht genug Geld!<br>";
        }
    } else {
        echo "<u><b>Übersicht für Puppenspieler</b></u><br><br>";
        if ($dorfs2->PuppeWerte < 1) {
            $dorfs2->PuppeWerte = 10;
            $up = "UPDATE user SET PuppeWerte = '10' WHERE id = '$c_loged'";
            mysql_query($up);
        }
        echo "<table border='0' width='90%'>";
        echo "<tr><td colspan='3'>";
        echo "<table border='0' width='100%'>";

        echo "<tr>";
        echo "<td width='25%' align='center'><b>Werte-Durchschnitt</b></td>";
        echo "<td width='25%' align='center'><b>Haltbarkeits-Pool</b></td>";
        echo "<td width='25%' align='center'><b>Maximal nutzbare Puppen</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td align='center'>$dorfs2->PuppeWerte</td>";
        echo "<td align='center'>$dorfs2->PuppenHaltbarkeit</td>";
        echo "<td align='center'>$dorfs2->Niveau</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td align='center' colspan='4'><b>Maximal nutzbare Vorrichtungen/Runde</b></td>";
        echo "</tr>";
        echo "<tr>";
        $MaxVorrichtungen = $dorfs2->Niveau;
        echo "<td align='center' colspan='4'>$MaxVorrichtungen</td>";
        echo "</tr>";

        echo "</table>
<i>Zu den angegebenen Vorrichtungen zählen auch Attacken mit Armen (auch wenn an diese nichts angebracht ist). Die Puppe kann sich außerdem noch normal bewegen.</i>";
        echo "</td></tr>";
        echo "</table><br>
<a href='?Training=1'>Puppen-Training</a> - <a href='Mario.php?Betrachtewerkstatt=1'>Marionettenteile zu Hause</a><br><br>
<b>Deine Puppen:</b><br><br>";

        if ($changePuppen == 1) {
            $maxWerteSumme = $dorfs2->PuppeWerte * 3;
            $HaltbarkeitSumme = 0;
            $MaxWerte = floor($dorfs2->PuppeWerte * 1.333);
            $MinWerte = floor($dorfs2->PuppeWerte * 0.666);

            $Okay = 1;

            $sql = "SELECT * FROM Marionetten WHERE Besitzer = '$dorfs->id'";
            $query = mysql_query($sql);
            while ($Puppe = mysql_fetch_object($query)) {
                $Haltbarkeit = $Puppe->Haltbarkeit;
                $Haltbarkeitmax = $Puppe->Haltbarkeitmax;
                $MinHaltbarkeit = $Haltbarkeitmax - $Haltbarkeit;
                if ($MinHaltbarkeit < 5) {
                    $MinHaltbarkeit = 5;
                }

                $StarkePuppe = "StarkeWert$Puppe->id";
                $StarkePuppe = $$StarkePuppe;
                $StarkePuppe = round($StarkePuppe, 2);
                $VerteidigungPuppe = "VerteidigungWert$Puppe->id";
                $VerteidigungPuppe = $$VerteidigungPuppe;
                $VerteidigungPuppe = round($VerteidigungPuppe, 2);
                $GeschwindigkeitPuppe = "GeschwindigkeitWert$Puppe->id";
                $GeschwindigkeitPuppe = $$GeschwindigkeitPuppe;
                $GeschwindigkeitPuppe = round($GeschwindigkeitPuppe, 2);
                $SummeWerte = $StarkePuppe + $VerteidigungPuppe + $GeschwindigkeitPuppe;

                if ($SummeWerte > $maxWerteSumme) {
                    echo "Summe der Werte von $Puppe->Name liegen zu hoch ($SummeWerte, Maximum: $maxWerteSumme)!<br>";
                    $Okay = 0;
                }
                if ($StarkePuppe > $MaxWerte) {
                    echo "Stärke von $Puppe->Name liegt über dem Maximum von $MaxWerte!<br>";
                    $Okay = 0;
                }
                if ($VerteidigungPuppe > $MaxWerte) {
                    echo "Verteidigung von $Puppe->Name liegt über dem Maximum von $MaxWerte!<br>";
                    $Okay = 0;
                }
                if ($GeschwindigkeitPuppe > $MaxWerte) {
                    echo "Geschwindigkeit von $Puppe->Name liegt über dem Maximum von $MaxWerte!<br>";
                    $Okay = 0;
                }
                if ($StarkePuppe < $MinWerte) {
                    echo "Stärke von $Puppe->Name liegt unter dem Minimum von $MinWerte!<br>";
                    $Okay = 0;
                }
                if ($VerteidigungPuppe < $MinWerte) {
                    echo "Verteidigung von $Puppe->Name liegt unter dem Minimum von $MinWerte!<br>";
                    $Okay = 0;
                }
                if ($GeschwindigkeitPuppe < $MinWerte) {
                    echo "Geschwindigkeit von $Puppe->Name liegt unter dem Minimum von $MinWerte!<br>";
                    $Okay = 0;
                }

                $HaltbarkeitPuppe = "HaltbarkeitWert$Puppe->id";
                $HaltbarkeitPuppe = $$HaltbarkeitPuppe;
                $HaltbarkeitPuppe = round($HaltbarkeitPuppe, 0);
                $HaltbarkeitSumme += $HaltbarkeitPuppe;

                if ($HaltbarkeitPuppe < $MinHaltbarkeit) {
                    echo "Haltbarkeit von $Puppe->Name liegt unter dem Minimum von $MinHaltbarkeit!<br>";
                    $Okay = 0;
                }
            }

            if ($HaltbarkeitSumme > $dorfs2->PuppenHaltbarkeit) {
                echo "Die Summe der Haltbarkeiten deiner Puppen überschreitet das Maximum von $dorfs2->PuppenHaltbarkeit!<br>";
                $Okay = 0;
            }

            if ($Okay == 1) {
                $UberschussHaltbarkeit = $dorfs2->PuppenHaltbarkeit - $HaltbarkeitSumme;

                $sql = "SELECT * FROM Marionetten WHERE Besitzer = '$dorfs->id'";
                $query = mysql_query($sql);
                while ($Puppe = mysql_fetch_object($query)) {
                    $Haltbarkeit = $Puppe->Haltbarkeit;
                    $Haltbarkeitmax = $Puppe->Haltbarkeitmax;
                    $MinHaltbarkeit = $Haltbarkeitmax - $Haltbarkeit;
                    if ($MinHaltbarkeit < 5) {
                        $MinHaltbarkeit = 5;
                    }

                    $StarkePuppe = "StarkeWert$Puppe->id";
                    $StarkePuppe = $$StarkePuppe;
                    $StarkePuppe = round($StarkePuppe, 2);
                    $VerteidigungPuppe = "VerteidigungWert$Puppe->id";
                    $VerteidigungPuppe = $$VerteidigungPuppe;
                    $VerteidigungPuppe = round($VerteidigungPuppe, 2);
                    $GeschwindigkeitPuppe = "GeschwindigkeitWert$Puppe->id";
                    $GeschwindigkeitPuppe = $$GeschwindigkeitPuppe;
                    $GeschwindigkeitPuppe = round($GeschwindigkeitPuppe, 2);
                    $SummeWerte = $StarkePuppe + $VerteidigungPuppe + $GeschwindigkeitPuppe;

                    $Starke = round($StarkePuppe / ($SummeWerte / 3), 10);
                    $Verteidigung = round($VerteidigungPuppe / ($SummeWerte / 3), 10);
                    $Geschwindigkeit = round($GeschwindigkeitPuppe / ($SummeWerte / 3), 10);

                    $HaltbarkeitPuppe = "HaltbarkeitWert$Puppe->id";
                    $HaltbarkeitPuppe = $$HaltbarkeitPuppe;
                    $HaltbarkeitPuppe = round($HaltbarkeitPuppe, 0);

                    if ($UberschussHaltbarkeit > 0) {
                        $Anteilzusatz = $UberschussHaltbarkeit * ($HaltbarkeitPuppe / $HaltbarkeitSumme);
                        $Anteilzusatz = floor($Anteilzusatz);
                        $HaltbarkeitPuppe += $Anteilzusatz;
                    }

                    $HaltbarkeitDifferenz = $Puppe->Haltbarkeitmax - $HaltbarkeitPuppe;

                    $Haltbarkeit -= $HaltbarkeitDifferenz;

                    $up = "UPDATE Marionetten SET Strmulti = '$Starke' WHERE id = '$Puppe->id'";
                    mysql_query($up);
                    $up = "UPDATE Marionetten SET Vertmulti = '$Verteidigung' WHERE id = '$Puppe->id'";
                    mysql_query($up);
                    $up = "UPDATE Marionetten SET Gesmulti = '$Geschwindigkeit' WHERE id = '$Puppe->id'";
                    mysql_query($up);
                    $up = "UPDATE Marionetten SET Haltbarkeit = '$Haltbarkeit' WHERE id = '$Puppe->id'";
                    mysql_query($up);
                    $up = "UPDATE Marionetten SET Haltbarkeitmax = '$HaltbarkeitPuppe' WHERE id = '$Puppe->id'";
                    mysql_query($up);
                }

                echo "Neue Verteilung an Werten und Haltbarkeit ist erfolgt. Das Script hat möglicherweise nicht verteilte Wertepunkte oder Haltbarkeit automatisch auf deine Puppen verteilt,
damit keine oder möglichst wenige Punkte über bleiben.<br><br>";
            } else {
                echo "<br>";
            }
        }

        echo "<form id='PuppenRechner' name='PuppenRechner' method='POST' action='?changePuppen=1'>";
        echo "<table border='0' width='100%'>";
        $Puppenhaste = "";
        $Haltbarkeiten = "";
        $SummeHaltbarkeit = 0;
        $sql = "SELECT * FROM Marionetten WHERE Besitzer = '$c_loged'";
        $query = mysql_query($sql);
        while ($Puppe = mysql_fetch_object($query)) {
            $Puppenhaste = "$Puppenhaste" . "$Puppe->id&";
            $Haltbarkeiten = "$Haltbarkeiten" . "$Puppe->Haltbarkeit&";
            $HaltbarkeitenMax = "$HaltbarkeitenMax" . "$Puppe->Haltbarkeitmax&";
            echo "<tr>";
            echo "<td colspan='6' background='/layouts/Uebergang/Oben.png'><a href='Mario.php?umbenenn=$Puppe->id'><b>$Puppe->Name</b></a> - $Puppe->Art - <a href='Mario.php?Verwalt=$Puppe->id'>Vorrichtungen</a>";
            echo "</tr>";
            echo "<tr>";
            echo "<td width='20%' align='center'><b>Stärke</b></td>";
            echo "<td width='20%' align='center'><b>Verteidigung</b></td>";
            echo "<td width='20%' align='center'><b>Geschwindigkeit</b></td>";
            echo "<td width='20%' align='center'><b>Summe</b></td>";
            echo "<td width='20%' align='center'><b>Haltbarkeit</b></td>";
            echo "</tr>";
            echo "<tr>";
            $Wert = round($Puppe->Strmulti * $dorfs2->PuppeWerte, 2);
            echo "<td width='20%' align='center'><input type='text' name='StarkeWert$Puppe->id' onkeyup=\"javascript:Berechnung()\" size='4' value='$Wert'><div id='Starkeinfo$Puppe->id'>ok</div></td>";
            $Wert = round($Puppe->Vertmulti * $dorfs2->PuppeWerte, 2);
            echo "<td width='20%' align='center'><input type='text' name='VerteidigungWert$Puppe->id' onkeyup=\"javascript:Berechnung()\" size='4' value='$Wert'><div id='Verteidigunginfo$Puppe->id'>ok</div></td>";
            $Wert = round($Puppe->Gesmulti * $dorfs2->PuppeWerte, 2);
            echo "<td width='20%' align='center'><input type='text' name='GeschwindigkeitWert$Puppe->id' onkeyup=\"javascript:Berechnung()\" size='4' value='$Wert'><div id='Geschwindigkeitinfo$Puppe->id'>ok</div></td>";
            $Summe = round($Puppe->Strmulti * $dorfs2->PuppeWerte + $Puppe->Vertmulti * $dorfs2->PuppeWerte + $Puppe->Gesmulti * $dorfs2->PuppeWerte,
                2);
            echo "<td width='20%' align='center'><div id='Summe$Puppe->id'>$Summe</div><div id='SummeInfo$Puppe->id'>ok</div></td>";
            $MaxWerte = floor($dorfs2->PuppeWerte * 1.333);
            $MinWerte = floor($dorfs2->PuppeWerte * 0.666);
            $Wert = $Puppe->Haltbarkeitmax;
            echo "<td width='20%' align='center'>$Puppe->Haltbarkeit/<input type='text' onkeyup=\"javascript:Berechnung()\" name='HaltbarkeitWert$Puppe->id' size='4' value='$Wert'><div id='Haltbarkeitinfo$Puppe->id'>ok</div></td>";
            echo "</tr>";
            $SummeHaltbarkeit += $Puppe->Haltbarkeitmax;

            echo "</tr>";
        }
        echo "<tr>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'><input type='submit' value='Puppenwerte ändern'></td>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'><b>Summe</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'></td>";
        echo "<td width='20%' align='center'><div id='HaltbarkeitSummen'>$SummeHaltbarkeit</div>
<div id='HaltbarkeitInfo'>ok</div></td>";
        echo "</tr>";
        echo "</table>";

        echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">
<!--
function Berechnung()
{
var MaxWerteSumme = $dorfs2->PuppeWerte * 3;
var HaltbarkeitSumme = 0;
HaltbarkeitInfo.innerHTML = \"ok\";
";

        $Puppenzahl = 0;
        $split = explode("&", $Puppenhaste);
        $Haltbarkeitens = explode("&", $Haltbarkeiten);
        $HaltbarkeitenMaxs = explode("&", $HaltbarkeitenMax);
        while ($split[$Puppenzahl] != "") {
            $Haltbarkeit = $Haltbarkeitens[$Puppenzahl];
            $Haltbarkeitmax = $HaltbarkeitenMaxs[$Puppenzahl];
            $MinHaltbarkeit = $Haltbarkeitmax - $Haltbarkeit;
            if ($MinHaltbarkeit < 5) {
                $MinHaltbarkeit = 5;
            }

            echo "
Starkeinfo$split[$Puppenzahl].innerHTML = \"ok\";
Verteidigunginfo$split[$Puppenzahl].innerHTML = \"ok\";
Geschwindigkeitinfo$split[$Puppenzahl].innerHTML = \"ok\";
SummeInfo$split[$Puppenzahl].innerHTML = \"ok\";

var StarkePuppe = eval(document.PuppenRechner.StarkeWert$split[$Puppenzahl].value);
var VerteidigungPuppe = eval(document.PuppenRechner.VerteidigungWert$split[$Puppenzahl].value);
var GeschwindigkeitPuppe = eval(document.PuppenRechner.GeschwindigkeitWert$split[$Puppenzahl].value);
var SummeWerte = StarkePuppe + VerteidigungPuppe + GeschwindigkeitPuppe;
var SummeWerte = SummeWerte * 100;
var SummeWerte = Math.round(SummeWerte);
var SummeWerte = SummeWerte / 100;
Summe$split[$Puppenzahl].innerHTML = SummeWerte;
if (SummeWerte > MaxWerteSumme)
{
SummeInfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>max $Summe</font>\";
}
if (StarkePuppe > $MaxWerte)
{Starkeinfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>max $MaxWerte</font>\";}
if (VerteidigungPuppe > $MaxWerte)
{Verteidigunginfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>max $MaxWerte</font>\";}
if (GeschwindigkeitPuppe > $MaxWerte)
{Geschwindigkeitinfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>max $MaxWerte</font>\";}
if (StarkePuppe < $MinWerte)
{Starkeinfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>min $MinWerte</font>\";}
if (VerteidigungPuppe < $MinWerte)
{Verteidigunginfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>min $MinWerte</font>\";}
if (GeschwindigkeitPuppe < $MinWerte)
{Geschwindigkeitinfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>min $MinWerte</font>\";}

var HaltbarkeitPuppe = eval(document.PuppenRechner.HaltbarkeitWert$split[$Puppenzahl].value);

var HaltbarkeitSumme = HaltbarkeitSumme + HaltbarkeitPuppe;

if (HaltbarkeitPuppe < $MinHaltbarkeit)
{
Haltbarkeitinfo$split[$Puppenzahl].innerHTML = \"<font color='#cc0033'>min $MinHaltbarkeit</font>\";
}
else
{
Haltbarkeitinfo$split[$Puppenzahl].innerHTML = \"ok\";
}


";

            $Puppenzahl += 1;
        }

        echo "
HaltbarkeitSummen.innerHTML = HaltbarkeitSumme;

if (HaltbarkeitSumme > $dorfs2->PuppenHaltbarkeit)
{
HaltbarkeitInfo.innerHTML = \"<font color='#cc0033'>max $dorfs2->PuppenHaltbarkeit</font>\";
}";


        echo "}
//--></SCRIPT>";

        echo "<a href='Mario.php?NeuePuppe=1'>Neue Puppe bauen</a>";
    }
}

get_footer();
