<?php

get_header();

$query = mysql_query("SELECT Land, Wettergestern, Wetter, Wettermorgen FROM Landdaten");
?>
    <b><u>Wetterübersicht</u></b>:
    <table style="width: 80%">
        <tr>
            <th>
                <center><b>Land</b></center>
            </th>
            <th>
                <center><b>Gestern</b></center>
            </th>
            <th>
                <center><b>Heute</b></center>
            </th>
            <th>
                <center><b>Morgen</b></center>
            </th>
        </tr>
        <?php while ($object = mysql_fetch_object($query)) : ?>
            <tr>
                <td style="text-align: center; font-weight: bold">
                    <?= $object->Land ?>gakure
                </td>
                <td>
                    <center><?= $object->Wettergestern ?></center>
                </td>
                <td>
                    <center><?= $object->Wetter ?></center>
                </td>
                <td>
                    <center><?= $object->Wettermorgen ?></center>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

<?php
get_footer();
