<?php

final class Anfragen
{
    private array $villages = [
        'Konoha',
        'Kusa',
        'Suna',
        'Kumo',
        'Taki',
        'Iwa',
        'Ame',
    ];

    public function NeueEntwicklung(UserData $dorfs): void
    {
        $dbc = nrpg_get_database();
        echo "Soll diese Anfragen auch für weitere Spieler gelten? Sie gilt bisher für:<br>";

        $Relevante = filter_input(INPUT_GET, 'Relevante');

        if (isset($_GET['Plep']) && $_GET['Plep'] == 1) {
            $query = $dbc->prepare("SELECT id, name FROM user WHERE name = :name");
            $query->bindValue(':name', $_POST['Zufungung']);
            $query->execute();
            $Nin = $query->fetchObject();

            if ($Nin->id > 0) {
                echo "$Nin->name wurde zugefügt.<br>";
                $Relevante = "$Relevante|$Nin->id|";
            } else {
                echo "{$_POST['Zufungung']} existiert nicht.<br>";
            }
        }

        if (!empty($_GET['Plepsorga'])) {
            $query = $dbc->prepare("SELECT id, name FROM user WHERE id = :id");
            $query->bindValue(':id', $_GET['Plepsorga'], PDO::PARAM_INT);
            $query->execute();
            $Nin = $query->fetchObject();

            if ($Nin !== false && str_contains((string)$Relevante, "|$Nin->id|")) {
                echo "$Nin->name entfernt.<br>";
                $Relevante = str_replace("|{$_GET['Plepsorga']}|", "", (string)$Relevante);
            } else {
                echo "$Nin->name kannst du nicht entfernen.<br>";
            }
        }

        $Vorschlag = "|$dorfs->id|$Relevante";
        $Vorschlag = trim($Vorschlag, "|");
        $split = explode("||", $Vorschlag);

        foreach ($split as $value) {
            if ($value !== "") {
                $query = $dbc->prepare("SELECT id, name FROM user WHERE id = :id");
                $query->bindValue(':id', $value, PDO::PARAM_INT);
                $query->execute();
                $Nin = $query->fetchObject();

                echo "<p><a href='?NeueEntwicklung=1&Plepsorga=$Nin->id&Relevante=$Relevante'>$Nin->name</a></p>";
            }
        }
        $post_action = http_build_query(['NeueEntwicklung' => 1, 'Plep' => 1, 'Relevante' => $Relevante]);
        ?>
        <form method="POST" action="?<?= $post_action ?>">
            <input type="text" name="Zufungung" autocomplete="off" data-autocomplete>
            <button type="submit">Hinzufügen</button>
        </form>
        <p>
            <a href="?<?= http_build_query(['NeueEntwicklungZwei' => true, 'RelevanteSpieler' => $Relevante]) ?>">
                Weiter zur Erstellung
            </a>
        </p>
        <?php
    }

    public function NeueEntwicklungZwei(): void
    {
        $dbc = nrpg_get_database();
        echo "Gibt es Personen, die hier keine Einsicht haben dürfen? Bisher dürfen die Erlaubnis nicht einsehen:<br>";

        $RelevanteSpieler = filter_input(INPUT_GET, 'RelevanteSpieler');
        $Relevante = filter_input(INPUT_GET, 'Relevante');

        if (isset($_GET['Plep']) && $_GET['Plep'] == 1) {
            $query = $dbc->prepare("SELECT id, name FROM user WHERE name = :name");
            $query->bindValue(':name', $_POST['Zufungung']);
            $query->execute();
            $Nin = $query->fetchObject();

            if ($Nin->id > 0) {
                echo "$Nin->name wurde zugefügt.<br>";
                $Relevante = "$Relevante|$Nin->id|";
            } else {
                echo "{$_POST['Zufungung']} existiert nicht.<br>";
            }
        }

        if (!empty($_GET['Plepsorga'])) {
            $query = $dbc->prepare("SELECT id, name FROM user WHERE id = :id");
            $query->bindValue(':id', $_GET['Plepsorga'], PDO::PARAM_INT);
            $query->execute();
            $Nin = $query->fetchObject();

            if ($Nin !== false && str_contains((string)$Relevante, "|$Nin->id|")) {
                echo "$Nin->name entfernt.<br>";
                $Relevante = str_replace("|{$_GET['Plepsorga']}|", "", (string)$Relevante);
            } else {
                echo "$Nin->name kannst du nicht entfernen.<br>";
            }
        }

        $Vorschlag = "$Relevante";
        $Vorschlag = trim($Vorschlag, "|");
        $split = explode("||", $Vorschlag);

        foreach ($split as $value) {
            if ($value !== "") {
                $query = $dbc->prepare("SELECT id, name FROM user WHERE id = :id");
                $query->bindValue(':id', $value, PDO::PARAM_INT);
                $query->execute();
                $Nin = $query->fetchObject();

                echo "<a href='?NeueEntwicklungZwei=1&Plepsorga=$Nin->id&Relevante=$Relevante&RelevanteSpieler=$RelevanteSpieler'>$Nin->name</a>";
            }
        }
        echo "<form method='POST' action='?NeueEntwicklungZwei=1&Plep=1&Relevante=$Relevante&RelevanteSpieler=$RelevanteSpieler'>
            <input type='text' name='Zufungung'> <input type='submit' value='Hinzufügen'>";
        echo "<br><br>";

        echo "<a href='?Entwickeln=1&RelevanteSpieler=$RelevanteSpieler&Ausgeschlossen=$Relevante'>Weiter zur Erstellung</a>";
    }

    public function Entwickeln(UserData $dorfs): void
    {
        echo "<b>Anfrage für</b><br>";

        $RelevanteSpieler = filter_input(INPUT_GET, 'RelevanteSpieler');
        $Ausgeschlossen = filter_input(INPUT_GET, 'Ausgeschlossen');

        $Vorschlag = trim("|$dorfs->id|$RelevanteSpieler", "|");
        $split = explode("||", $Vorschlag);

        foreach ($split as $value) {
            if ($value !== "") {
                $sql = "SELECT id, name FROM user WHERE id = '$value'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);

                echo "<a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a>";
            }
        }

        echo "<br><b>Unter Ausschluss von:</b><br>";

        $Vorschlag = trim((string)$Ausgeschlossen, "|");
        $split = explode("||", $Vorschlag);

        foreach ($split as $value) {
            if ($value !== "") {
                $sql = "SELECT id, name FROM user WHERE id = '$value'";
                $query = mysql_query($sql);
                $Nin = mysql_fetch_object($query);

                echo "<a href='userpopup.php?usernam=$Nin->name'>$Nin->name</a>";
            }
        }

        $post_action = http_build_query(['Entwickle' => 1, 'RelevanteSpieler' => $RelevanteSpieler, 'Ausgeschlossen' => $Ausgeschlossen]);
        ?>
        <br>
        <form method="POST" action='?<?= $post_action ?>'>
            <p>
                <b>Titel der Anfrage:</b> <input type='text' name='NamederE' size='30' maxlength='30'>
            </p>
            <label>
                <b>Art der Anfrage:</b>
                <select name='ArtAnfrage'>
                    <option value="1">RP-Erlaubnis</option>
                    <option value="2">Support-Anfrage</option>
                    <option value="3">Dorfoberhaupt-Anfrage</option>
                    <option value="6">NPC-/Team-/SL-Anfrage</option>
                    <option value="5">Sonstige Anfrage</option>
                </select>
            </label>
            <p><b>Zusätzliche Einsicht für:</b></p>
            <table border='0' width='60%'>
                <tr>
                    <td width='3%'><input type='checkbox' name='RPCoAdminEinsicht' value='1'></td>
                    <td width='30%'>RPG-Co-Admin</td>
                    <td width='3%'><input type='checkbox' name='RegelCoAdminEinsicht' value='1'></td>
                    <td width='30%'>Regel-Co-Admin</td>
                </tr>
                <tr>
                    <td width='3%'><input type='checkbox' name='SupportCoAdminEinsicht' value='1'></td>
                    <td width='30%'>Support-Co-Admin</td>
                    <td width='3%'><input type='checkbox' name='TeamleiterEinsicht' value='1'></td>
                    <td width='30%'>Zugewiesener Jounin</td>
                </tr>
            </table>

            <p>Dorfoberh&auml;upter</p>

            <?php
            foreach ($this->villages as $village) {
                echo sprintf(
                    '<label><input type="checkbox" name="Einsicht[]" value="%s"> %s</label>',
                    $village,
                    $village
                );
            }
            ?>

            <p>
                Für eine &Uuml;bersicht, wer wofür zust&auml;ndig ist,<br>
                schaue dir bitte die <a href='https://wiki.narutorpg.de/index.php?title=Anfragen'>Seite zu den verschiedenen Administratoren</a> im Wiki an.
            </p>
            <b>Text (für RP-Erlaubnisse z.B. Schilderung der Situation.)</b><br>
            <textarea name='Ausarbeitung' rows='10' cols='80' style='height: 167px'></textarea><br>
            <input type='submit' value='Anfrage eintragen'>
        </form>
        <?php
    }

    public function Entwickle(UserData $dorfs): void
    {
        if (!empty($_POST['NamederE']) && !empty($_POST['Ausarbeitung'])) {
            $Ausarbeitung = str_replace("'", "\"", (string)$_POST['Ausarbeitung']);
            $Ausarbeitung = htmlentities($Ausarbeitung);
            $NamederE = str_replace("'", "\"", (string)$_POST['NamederE']);
            $NamederE = htmlentities($NamederE);
            $time = time();
            $ArtAnfrage = (int)filter_input(INPUT_POST, 'ArtAnfrage', FILTER_SANITIZE_NUMBER_INT);

            $Zugriffe = "";
            if (isset($_POST['RPCoAdminEinsicht']) || $ArtAnfrage === 1) {
                $Zugriffe .= "|RPCo|";
            }
            if (isset($_POST['RegelCoAdminEinsicht'])) {
                $Zugriffe .= "|RegelCo|";
            }
            if (isset($_POST['SupportCoAdminEinsicht']) || $ArtAnfrage === 2) {
                $Zugriffe .= "|SupportCo|";
            }
            if ($ArtAnfrage === 3) {
                $Zugriffe .= "|Landesfuehrer|";
            }
            if (isset($_POST['TeamleiterEinsicht']) || $ArtAnfrage === 4) {
                $Zugriffe .= "|TeamleiterEinsicht|";
            }

            $Dorfer = "";
            $Einsicht = (array)filter_input(INPUT_POST, 'Einsicht', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            foreach ($this->villages as $village) {
                if (in_array($village, $Einsicht)) {
                    if (!str_contains($Zugriffe, "|Landesfuehrer|")) {
                        $Zugriffe = "$Zugriffe|Landesfuehrer|";
                    }
                    $Dorfer = $Dorfer . '|' . $village . '|';
                }
            }

            if ($Zugriffe == "") {
                $Zugriffe = "|RPCo||RegelCo||SupportCo|";
            }

            $RelevanteSpieler = (string)filter_input(INPUT_GET, 'RelevanteSpieler');
            $Ausgeschlossen = (string)filter_input(INPUT_GET, 'Ausgeschlossen');
            $ArtAnfrage = (string)filter_input(INPUT_POST, 'ArtAnfrage');

            $ins = "INSERT INTO Anfragen (Ninja, Ausschluss, Art, Ausarbeitung, Titel, lastuser, lastact, Zugriffe, Dorfer) VALUES ('|$dorfs->id|$RelevanteSpieler', '$Ausgeschlossen', '$ArtAnfrage', '$Ausarbeitung', '$NamederE', '$time', '$time', '$Zugriffe', '$Dorfer')";
            mysql_query($ins);
            $sql = "SELECT id FROM Anfragen WHERE Ninja = '|$dorfs->id|$RelevanteSpieler' ORDER BY id DESC";
            $query = mysql_query($sql);
            $Eintrag = mysql_fetch_object($query);

            $Datum = date("d.m.Y, H:i");

            $ins = "INSERT INTO Anfragen_Posts (Von, Topic, Text, Postdatum) VALUES (0, '$Eintrag->id', '$Ausarbeitung', '$Datum')";
            mysql_query($ins);

            $ins = "INSERT INTO Anfragen_Posts (Von, Topic, Text, Postdatum) VALUES (0, '$Eintrag->id', 'Keine SL-Informationen vorhanden', '$Datum')";
            mysql_query($ins);

            $ins = "INSERT INTO Anfragen_Posts (Von, Topic, Text, Postdatum) VALUES (0, '$Eintrag->id', 'Keine KR-Informationen vorhanden', '$Datum')";
            mysql_query($ins);

            echo 'Die Anfrage wurde eingetragen.<br><a href="javascript:history.back()">zurück</a>';
        } else {
            echo 'Der Text oder der Titel der Anfrage sind leer. Bitte trage beide für eine reibungslose Bearbeitung ein. <a href="javascript:history.back()">Zurück</a>';
        }
    }
}
