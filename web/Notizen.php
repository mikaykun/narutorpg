<?php

include(__DIR__ . "/../Menus/layout1.inc");

if ($dorfs->id > 0) {
    if ($notizmach == 1 and $_GET['Savior'] == $object_save_forms and ($Notizinhaltmach != '' or $yeswanttodelete == 1)) {
        $Notizinhaltmach = htmlentities($Notizinhaltmach);
        mysql_query("UPDATE user SET Notizen = '$Notizinhaltmach' WHERE id = '$dorfs2->id'");
    } elseif (($Notizinhaltmach == '' or $yeswanttodelete != 1) && $notizmach == 1) {
        echo "<form method='POST' action='?notizmach=1&Savior=" . "$object_save_forms" . "'>
		<b>Notizen wirklich l&ouml;schen?</b><br><br>
		<input type='hidden' name='Notizinhaltmach' value=''>
		<input type='hidden' name='yeswanttodelete' value='1'>
		<input type='submit' value='l&ouml;schen'></form>";
    }
    $query = mysql_query("SELECT Notizen FROM user WHERE id = '$dorfs2->id'");
    $Noti = mysql_fetch_object($query);
    echo "<form method='POST' action='?notizmach=1&Savior=" . "$object_save_forms" . "'>
		<b>Notizen</b><br><br>
		<textarea name='Notizinhaltmach' cols='90' rows='40'>$Noti->Notizen</textarea><br>
		<input type='submit' value='Speichern'></form>";
}

get_footer();
