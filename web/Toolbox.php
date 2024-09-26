<?php

include(__DIR__ . "/../Menus/layout1.inc");
include(__DIR__ . "/../layouts/Overview/Overview1.php");
include(__DIR__ . "/../layouts/Overview/OverviewBaukasten.php");

if ($dorfs->admin == 3 || $dorfs->CoAdmin >= 3) {
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $developmentViewModel = new DevelopmentViewModel();

    if ($request->request->getString('saveDevelopment') == "saveDevelopment") {
        $developmentViewModel->NewDevelopment($_POST, $_COOKIE["c_loged"]);

        if ($developmentViewModel->SaveOrUpdateDevelopment() == null) {
            echo "save failed";
        } else {
            echo "save success";
        }
    } elseif ($request->request->getString('saveChildDevelopment') == "saveChildDevelopment") {
        $developmentViewModel->NewDevelopment($_POST, $_COOKIE["c_loged"]);

        if ($developmentViewModel->SaveOrUpdateDevelopment() == null) {
            echo "save failed";
        } else {
            echo "save success";
        }
    } else {
        if ($request->query->get('selectedId') != null) {
            $developmentViewModel->GetDevelopment($_GET["selectedId"], $_COOKIE["c_loged"]);
        } else {
            if (isset($_POST["updateDevelopment"]) && $_POST["updateDevelopment"] == "updateDevelopment") {
                $developmentViewModel->GetDevelopment($_POST["id"], $_COOKIE["c_loged"]);
                $development = $developmentViewModel->development;

                if ($development == null) {
                    $_POST["id"] = 0;
                    $development = new Development();
                }

                $development->SetValues($_POST);
                $development->UserId = $_COOKIE["c_loged"];
                $developmentViewModel->development = $development;
                if ($developmentViewModel->SaveOrUpdateDevelopment() != null) {
                    $developmentViewModel->ClearEffects();

                    foreach ($_POST as $key => $value) {
                        if (!str_starts_with($key, "isActiv")) {
                            continue;
                        }
                        $developmentViewModel->AddEffectToDevelopment($value);
                    }
                }
            }
        }
    }
    ?>
    <tr>
        <td align='center' background='/layouts/Uebergang/Untergrund.png' colspan='5'>
            <table width="100%">
                <tr>
                    <td colspan="2" align="center">
                        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                            <p><label>Name: <input type="text" name="name"/></label></p>
                            <button type="submit" value="saveDevelopment" name="saveDevelopment">
                                Eigenentwicklung speichern
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <div class="css-treeview">
                            <?= $developmentViewModel->GetDevelopmentTreeByUser($_COOKIE["c_loged"], null); ?>
                        </div>
                    </td>
                    <td>
                        <?php if (isset($_GET["developmentAction"]) && $_GET["developmentAction"] == "newDevelopment") : ?>
                            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                                <input type="hidden" name="parentId" value="<?= $_GET["parentId"]; ?>"/>
                                <label>Name: <input type="text" name="name"/></label>
                                <button type="submit" value="saveChildDevelopment" name="saveChildDevelopment">
                                    Gruppe speichern
                                </button>
                            </form>
                        <?php elseif ($developmentViewModel->development != null) : ?>
                        <?php
                        $developmentViewModel->GetDevelopmentEffects();

                        if ($developmentViewModel->development->UserId == $_COOKIE["c_loged"]){
                        ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
                            <input type="hidden" name="id" value="<?php echo $developmentViewModel->development->Id;?>" />
                            <table>
                                <tr>
                                    <td>Eigenentwicklung:</td>
                                    <td><input type="text" name="name" value="<?php echo $developmentViewModel->development->Name;?>" /></td>
                                    <td></td>
                                    <td><input type="checkbox" name="isPublic" <?php
                                        if ($developmentViewModel->development != null && $developmentViewModel->development->IsPublic) {
                                            echo 'checked="checked"';
                                        }
                                         ?>/> Öffentlich</td>
                                    <td><button type="submit" value="updateDevelopment" name="updateDevelopment">Ändern</button></td>
                                </tr>
                                <tr>
                                    <td>Rang:</td>
                                    <td><input type="number" name="rank" min="0" value="<?php echo $developmentViewModel->development->Rank;?>"/></td>
                                    <td>Typ:</td>
                                    <td>
                                        <select name="type">
                                            <option value="0" <?php if($developmentViewModel->development->Type == 0){echo 'selected="selected"';}?>>Item</option>
                                            <option value="1" <?php if($developmentViewModel->development->Type == 1){echo 'selected="selected"';}?>>Jutsu</option>
                                            <option value="2" <?php if($developmentViewModel->development->Type == 2){echo 'selected="selected"';}?>>Fähigkeit</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" align="left">Beschreibung</td>
                                </tr>
                                <tr>
                                    <td colspan="5" align="left">
                                        <textarea name="description" wrap="soft" cols="70" rows="10"><?php echo $developmentViewModel->development->Description;?></textarea>
                                    </td>
                                </tr>
                                <?php
                                    $groupViewModel = new GroupViewModel();
                                    $groups = $groupViewModel->GetGroupListByUser($_COOKIE["c_loged"]);
                                ?>
                                <tr>
                                    <td>
                                        <select id="groups" name="groups" onchange="groupChanged(<?php echo count($groups); ?>)">
                                            <?php
                                            foreach ($groups as $group) {
                                                ?>
                                                <option value="<?php echo $group->Id;?>"><?php echo $group->Name;?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td colspan="4">
                                        <?php
                                        $isFirst = true;
                                        $groupCount = 0;
                                        foreach ($groups as $group)
                                        {
                                            $groupViewModel->group = $group;
                                            ?>
                                                <table id="group<?php echo $groupCount.'"'; if(!$isFirst){ echo ' style="display: none"';}else{$isFirst=FALSE;}?>>
                                                    <tr>
                                                        <th>Aktiv?</th>
                                                        <th>Name</th>
                                                        <th>Beschreibung</th>
                                                        <th>Kosten</th>
                                                        <th>Rang</th>
                                                        <th>V/N</th>
                                                    </tr>
                                                    <?php
                                                        foreach ($groupViewModel->GetGroupEffects() as $effect) {
                                                            ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="isActiv<?php echo $effect->Id;?>" value="<?php echo $effect->Id;?>" <?php if($developmentViewModel->IsEffectSet($effect->Id)){echo ' checked="checked"';}?> /></td>
                                                        <td><?php echo $effect->Name;?></td>
                                                        <td><?php echo $effect->Description;?></td>
                                                        <td><?php echo $effect->Costs;?></td>
                                                        <td><?php echo $effect->Rank;?></td>
                                                        <td><?php echo ($effect->IsAdvantage) ? "Vorteil" : "Nachteil" ;?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                            <?php
                                            $groupCount++;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <?php }?>
            <?php endif; ?>
            <script type="text/javascript">
                function groupChanged(gruppen) {
                    const x = document.getElementById("groups");
                    for (let i = 0; i < gruppen; i++) {
                        const groupTable = document.getElementById(`group${i}`);
                        groupTable.style.display = "none";
                    }

                    const selectedGroupTable = document.getElementById(`group${x.selectedIndex}`);
                    selectedGroupTable.style.display = "";
                }
            </script>
        </td>
    </tr>
<?php
} else {
    echo 'Du hast nicht die nötigen Rechte, um diese Seite einzusehen.';
}

// Close everything from this weird layout structure.
echo '</table></td></tr></table>';

get_footer();
