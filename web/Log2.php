<?php

include(__DIR__ . "/../Menus/layout1.inc");

if (\NarutoRPG\SessionHelper::isLoggedIn()) {
    $c_loged = \NarutoRPG\SessionHelper::getUserId();
    echo "Erfolgreich eingeloggt!";
    echo "<meta http-equiv='refresh' content='0; URL=Center.php'>";
} else {
    echo "Einloggen fehlgeschlagen! Prüfe, ob deine Cookies deaktiviert sind.";
}

get_footer();
