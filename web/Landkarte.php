<?php
include __DIR__ . "/../Menus/layout1.inc";
include __DIR__ . "/../layouts/Overview/OverviewLand.php";
include __DIR__ . "/../layouts/Overview/OverviewReisen.php";
?>
    <tr>
        <td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'><br>
            <IFRAME
                name=Karte id=Karte src='Kampfscript/Weltkarte/SKarte.php'
                frameBorder=1 width=750 height=465>
                Aktionen
            </IFRAME>
            <IFRAME
                name=Info id=Info src='Kampfscript/Weltkarte/SInfo.php'
                frameBorder=1 width=750 height=300>
                Aktionen
            </IFRAME>
        </td>
    </tr>
    </table>
<?php
get_footer();
