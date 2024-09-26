<?php

include __DIR__ . "/../Menus/layout1.inc";

if ($dorfs->id > 0) {
    include __DIR__ . "/../layouts/Overview/Overview1.php";
    include __DIR__ . "/../layouts/Overview/OverviewDaten.php";

    echo "<tr>
		<td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'><br>
		<table border='0' width='75%'>
		<tr>
		<td width='50%' align='center'><a href='NurCharaloesch.php'>Charakter löschen</a></td>
		<td width='50%' align='center'><a href='Charaloesch.php'>Account löschen</a></td>
		</tr>
		</table><br><br>
		</td></tr></table>";
}

get_footer();
