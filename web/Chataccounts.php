<?php

include(__DIR__ . "/../Menus/layout1.inc");
include(__DIR__ . "/../layouts/Overview/Overview1.php");
include(__DIR__ . "/../layouts/Overview/OverviewDaten.php");

$caViewModel = new ChataccViewModel();
$caViewModel->SetAcc($dorfs->id, $dorfs2->name);

if (isset($del)) {
    $caViewModel->CreateOrDeleteChatacc(name: $caName, del: true, caId: $caId);
} elseif (isset($caName)) {
    $caViewModel->CreateOrDeleteChatacc($caName, $isNPC, $vorlage);
} elseif (isset($tmplId)) {
    $caViewModel->UpdateChataccToTemplate($caname2, $tmplId);
}
?>
<tr>
    <td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
        <table>
            <td>
                <tr>Name:</tr>
                <tr><input type="text" name="caName"/></tr>
                <tr>NPC?</tr>
                <tr><input type="checkbox" value="1" name="isNPC"/></tr>
                <tr>Optionen &uuml;bernehmen von:</tr>
                <tr>
                    <select name='vorlage'>
                        <option value="0">-</option>
                        <?php
                        $uAccs = $caViewModel->GetChataccsByUser();
                        foreach ($uAccs as $ca) { ?>
                            <option value="<?php echo $ca->Id ?>"><?php echo $ca->Name;?>
                        <?php } ?>
                    </select>
                </tr>
            </td>
            <td>
                <tr colspan="6">
                    <button type="submit" value="saveCA" name="saveCA">
                        Chataccount erstellen
                    </button>
                </tr>
            </td>
        </table>
        </form>
    </td>
</tr>
<tr>
    <td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'>
        <table>
        <?php
        foreach ($uAccs as $ca) {?>
                <tr>
                    <td><?php echo htmlspecialchars($ca->Name);?></td>
                    <td><?php echo $ca->npc ? 'NPC' : '';?></td>
                    <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?caId=" . $ca->Id . '&caName=' . $ca->Name;?>&del=1">löschen</a></td>
                    <td>
                        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
                            <input name="caname2" type="hidden" value="<?php echo $ca->Name;?>">
                            Optionen von:
                            <select name='tmplId'>
                                <option value="0">-</option>
                                <?php foreach ($uAccs as $ca2) : ?>
                                    <option value="<?php echo $ca2->Id ?>"><?php echo htmlspecialchars($ca2->Name);?>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" value="saveCA" name="saveCA">
                                &uuml;bernehmen
                            </button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </td>
</tr>
<?php
get_footer();
