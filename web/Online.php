<?php

get_header();

echo "<h1>Online</h1>";
echo "<p>Innerhalb der letzten 5 Minuten waren online:</p>";

$pdo = nrpg_get_database();
$doerfer = ['Konoha', 'Kusa', 'Iwa', 'Ame', 'Suna', 'Taki', 'Kumo', 'Landlos'];

foreach ($doerfer as $dorf) {
    $rofl = 0;
    $ergebnis2 = $pdo->query("SELECT * FROM onlineuser WHERE Land = '" . $dorf . "'");

    if ($ergebnis2->rowCount() == 0) {
        continue;
    }

    echo '<br><b>' . $dorf . '</b><br>';

    while ($row = $ergebnis2->fetchObject()) {
        if ($rofl <= 4 and $rofl > 0) {
            echo ", ";
        } elseif ($rofl == 5) {
            echo "<br>";
            $rofl = 0;
        }
        echo sprintf("<a href='userpopup.php?usernam=%s'>%s</a>", $row->name, $row->name);
        $rofl += 1;
    }
}

$userOnline = $pdo->query("SELECT COUNT(*) FROM onlineuser WHERE name = 'Gast'");
$gast = (int)$userOnline->fetchColumn();
echo sprintf("<br><br>Des Weiteren sind %d Gäste online.", $gast);

get_footer();
