<?php

include(__DIR__ . "/../Menus/layout1.inc");

$Adminsin = "";
if ($BewertArt == "1") {
#Eigenentwicklung

    $sql = "SELECT * FROM X_Jutsueintrag WHERE id = '$BewertungID' AND Ninja = '$dorfs2->id'";
    $query = mysql_query($sql);
    $BearbeitungdurchNinja = mysql_fetch_object($query);

    $s_sql = "SELECT id, Von FROM X_Posts WHERE Topic = '$BearbeitungdurchNinja->id' AND Von != '$dorfs2->id' AND Von > '0'";
    $s_query = mysql_query($s_sql);
    while ($Posts = mysql_fetch_object($s_query)) {
        $sql = "SELECT CoAdmin, admin FROM userdaten WHERE id = '$Posts->Von'";
        $query = mysql_query($sql);
        $Admin = mysql_fetch_object($query);
        if ($Admin->admin >= 3 or $Admin->CoAdmin > 0) {
            $pos = strpos($Adminsin, "|$Posts->Von|");
            if ($pos === false) {
                $Adminsin = "$Adminsin|$Posts->Von|";
            }
        }
    }
#Eigenentwicklung
}
if ($BewertArt == "2")
{
#Anfragen

    $sql = "SELECT * FROM Anfragen WHERE id = '$BewertungID' AND Ninja LIKE '%|$dorfs2->id|%'";
    $query = mysql_query($sql);
    $BearbeitungdurchNinja = mysql_fetch_object($query);

    $s_sql = "SELECT id, Von FROM Anfragen_Posts WHERE Topic = '$BearbeitungdurchNinja->id' AND Von != '$dorfs2->id' AND Von > '0'";
    $s_query = mysql_query($s_sql);
    while ($Posts = mysql_fetch_object($s_query))
    {
        $sql = "SELECT CoAdmin, admin FROM userdaten WHERE id = '$Posts->Von'";
        $query = mysql_query($sql);
        $Admin = mysql_fetch_object($query);
        if ($Admin->admin >= 3 OR $Admin->CoAdmin > 0)
        {
            $pos = strpos($Adminsin, "|$Posts->Von|");
            if ($pos === false)
            {
                $pos = strpos($BearbeitungdurchNinja->Ninja, "|$Posts->Von|");
                if ($pos2 === false)
                {
                    $Adminsin = "$Adminsin|$Posts->Von|";
                }
            }
        }
    }

#Anfragen
}
if ($BewertArt == "3")
{
#Anfragen

    $BearbeitungdurchNinja->id = 1;
    $Adminsin = "$Adminsin";

#Anfragen
}

echo "<form method='POST' action='?BewertungID=$BewertungID&Art=$BewertArt'>Bitte bewerte hier, die Arbeit der Administratoren bei ";

if ($BewertArt == "1"){echo "deiner Eigenentwicklung $BearbeitungdurchNinja->Topic";}
if ($BewertArt == "2"){echo "deiner Anfrage $BearbeitungdurchNinja->Titel";}
if ($BewertArt == "3"){echo "deiner sonstigen Situation <input type='text' name='sonstSituation'> (z.B. Teamumstellungen)";}
echo "<br><br>";

$Adminsin = str_replace("||", ",", $Adminsin);
$Adminsin = str_replace("|", "", $Adminsin);

$split = explode(",", $Adminsin);
$Zahl = 0;
while ($split[$Zahl] != "")
{
    $sql = "SELECT id, name FROM user WHERE id = '$split[$Zahl]'";
    $query = mysql_query($sql);
    $Admin = mysql_fetch_object($query);

    echo "<table border='0' width='95%'>
        <tr>
        <td background='/layouts/Uebergang/Oben.png'><b><u>$Admin->name</u></b></td>
        </tr>

        <tr>
        <td background='/layouts/Uebergang/Oben2.png'><b>Freundlichkeit</b></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'><i>Hat $Admin->name einen angemessenen Ton angeschlagen oder war ist er unfreundlich und harsch gewesen? (0P = sehr unfreundlich, 10P = sehr freundlich)</i></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'>
        <table border='0'>
        <tr>
        <td width='9%' align='center'>10</td><td width='9%' align='center'>9</td><td width='9%' align='center'>8</td><td width='9%' align='center'>7</td><td width='9%' align='center'>6</td><td width='9%' align='center'>5</td><td width='9%' align='center'>4</td>
        <td width='9%' align='center'>3</td><td width='9%' align='center'>2</td><td width='9%' align='center'>1</td><td width='9%' align='center'>0</td>
        </tr>
        <tr>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='10'></td><td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='9'></td>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='8'></td><td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='7'></td>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='6'></td><td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='5'></td>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='4'></td><td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='3'></td>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='2'></td><td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='1'></td>
        <td width='9%' align='center'><input type='radio' name='Freundlichkeit$Admin->id' value='0'></td>
        </tr>
        </table>
        </td>
        </tr>

        <tr>
        <td background='/layouts/Uebergang/Oben2.png'><b>Begründungen und Alternativen</b></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'><i>Hat $Admin->name seine Entscheidungen und Meinungen begründet oder hat er Erklärungen und Begründungen ausgelassen? Hat er außerdem Alternativvorschläge gegeben (falls möglich), wenn er etwas abgelehnt hat? (0P = keine Begründungen und Alternativen, 10P = Ausführliche Begründungen und Alternativen)</i></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'>
        <table border='0'>
        <tr>
        <td width='9%' align='center'>10</td><td width='9%' align='center'>9</td><td width='9%' align='center'>8</td><td width='9%' align='center'>7</td><td width='9%' align='center'>6</td><td width='9%' align='center'>5</td><td width='9%' align='center'>4</td>
        <td width='9%' align='center'>3</td><td width='9%' align='center'>2</td><td width='9%' align='center'>1</td><td width='9%' align='center'>0</td>
        </tr>
        <tr>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='10'></td><td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='9'></td>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='8'></td><td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='7'></td>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='6'></td><td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='5'></td>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='4'></td><td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='3'></td>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='2'></td><td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='1'></td>
        <td width='9%' align='center'><input type='radio' name='Erklärungen$Admin->id' value='0'></td>
        </tr>
        </table>
        </td>
        </tr>

        <tr>
        <td background='/layouts/Uebergang/Oben2.png'><b>Beteiligung</b></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'><i>Hat $Admin->name sich zeitnah geäußert? Musste lange auf $Admin->name gewartet werden? Hat sich $Admin->name regelmäßig an der Diskussion beteiligt? (0P = kaum Beteiligung, 10P = gute Beteiligung)</i></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'>
        <table border='0'>
        <tr>
        <td width='9%' align='center'>10</td><td width='9%' align='center'>9</td><td width='9%' align='center'>8</td><td width='9%' align='center'>7</td><td width='9%' align='center'>6</td><td width='9%' align='center'>5</td><td width='9%' align='center'>4</td>
        <td width='9%' align='center'>3</td><td width='9%' align='center'>2</td><td width='9%' align='center'>1</td><td width='9%' align='center'>0</td>
        </tr>
        <tr>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='10'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='9'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='8'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='7'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='6'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='5'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='4'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='3'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='2'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='1'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='0'></td>
        </tr>
        </table>
        </td>
        </tr>

        <tr>
        <td background='/layouts/Uebergang/Oben2.png'><b>Allgemeine Zufriedenheit</b></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'><i>Wie zufrieden bist du mit der Arbeit von $Admin->name in diesem Falle gewesen? (0P = Sehr unzufrieden, 10P = Vollkommen zufrieden)</i></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'>
        <table border='0'>
        <tr>
        <td width='9%' align='center'>10</td><td width='9%' align='center'>9</td><td width='9%' align='center'>8</td><td width='9%' align='center'>7</td><td width='9%' align='center'>6</td><td width='9%' align='center'>5</td><td width='9%' align='center'>4</td>
        <td width='9%' align='center'>3</td><td width='9%' align='center'>2</td><td width='9%' align='center'>1</td><td width='9%' align='center'>0</td>
        </tr>
        <tr>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='10'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='9'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='8'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='7'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='6'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='5'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='4'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='3'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='2'></td><td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='1'></td>
        <td width='9%' align='center'><input type='radio' name='Aktivität$Admin->id' value='0'></td>
        </tr>
        </table>
        </td>
        </tr>

        <tr>
        <td background='/layouts/Uebergang/Oben2.png'><b>Kommentar zu $Admin->name</b></td>
        </tr>
        <tr>
        <td background='/layouts/Uebergang/Untergrund.png'><textarea name='Kommentar$Admin->id' rows='6' cols='70'></textarea></td>
        </tr>

        </table><br><br>
        ";
    $Zahl += 1;
}
echo "<table border='0' width='95%'>
<tr>
<td background='/layouts/Uebergang/Oben2.png'><b>Zufriedenheit mit dem Ergebnis</b></td>
</tr>
<tr>
<td background='/layouts/Uebergang/Untergrund.png'><i>Wie zufrieden bist du mit dem Endergebnis? (0P = Sehr unzufrieden, 10P = Vollkommen zufrieden)</i></td>
</tr>
<tr>
<td background='/layouts/Uebergang/Untergrund.png'>
<table border='0'>
<tr>
<td width='9%' align='center'>10</td><td width='9%' align='center'>9</td><td width='9%' align='center'>8</td><td width='9%' align='center'>7</td><td width='9%' align='center'>6</td><td width='9%' align='center'>5</td><td width='9%' align='center'>4</td>
<td width='9%' align='center'>3</td><td width='9%' align='center'>2</td><td width='9%' align='center'>1</td><td width='9%' align='center'>0</td>
</tr>
<tr>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='10'></td><td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='9'></td>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='8'></td><td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='7'></td>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='6'></td><td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='5'></td>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='4'></td><td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='3'></td>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='2'></td><td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='1'></td>
<td width='9%' align='center'><input type='radio' name='Gesamtzufriedenheit' value='0'></td>
</tr>
</table>
</td>

</tr>
</table>";

get_footer();
