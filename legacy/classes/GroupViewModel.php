<?php

class GroupViewModel
{
    public $group;
    public $effects;
    private PDO $db;

    public function __construct() {}

    public function SetGroup($group): void
    {
        $this->group = $group;
    }

    public function NewGroup($name, $parentGroup, $userId, bool $isOpenGroup = false): void
    {
        $this->group = new Group($name, $parentGroup, $userId, $isOpenGroup);
    }

    public function GetGroup(int $groupId, ?int $userId, bool $ownOnly = true): void
    {
        $groupSelect = "SELECT * FROM eegroup WHERE id = " . mysql_real_escape_string($groupId);
        if ($ownOnly && $userId != null) {
            $groupSelect .= " AND (userId = " . mysql_real_escape_string($userId) . ") OR (id = " . mysql_real_escape_string($groupId) . " AND IsOpenGroup = true)";
        }
        $groupResult = mysql_query($groupSelect);

        if (mysql_num_rows($groupResult) == 1) {
            $parents = null;
            $parentsSelect = "SELECT parentGroupID FROM eeGroupGroupRelation WHERE childGroupID = " . mysql_real_escape_string($groupId);
            $parentsResult = mysql_query($parentsSelect);
            if (mysql_num_rows($parentsResult) != 0) {
                $parents = mysql_fetch_array($parentsResult)['parentGroupID'];
            }

            $result = mysql_fetch_array($groupResult);
            $this->group = new Group(
                $result["name"],
                $parents,
                $result["userId"],
                $result["IsOpenGroup"]
            );
        }
    }

    //Still must be done
    public function SaveOrUpdateGroup()
    {
        if ($this->group == null) {
            return null;
        }
        $baukastenLog = new LoggingTool();

        $name = mysql_real_escape_string($this->group->Name);
        $parentGroup = mysql_real_escape_string($this->group->ParentGroup);
        $userId = mysql_real_escape_string($this->group->UserId);
        $isOpenGroup = mysql_real_escape_string($this->group->IsOpenGroup);
        if ($this->group->Id == 0) {
            $groupQuery = "insert into eegroup (name,userId,IsOpenGroup)" .
                " values('" . $name . "'," . $userId . "," . ($isOpenGroup ? 1 : 0) . ")";
        } else {
            $getGroupName = "SELECT name, IsOpenGroup FROM eegroup WHERE id = " . mysql_real_escape_string($this->group->Id);
            $getGroupName = mysql_query($getGroupName);
            $singleGroupName = mysql_fetch_array($getGroupName);
            $groupQuery = "update eegroup set name = '" . $name . "',";
            $groupQuery .= " IsOpenGroup = " . ($isOpenGroup == "true" ? 1 : 0);
            $groupQuery .= " where id = " . mysql_real_escape_string($this->group->Id);
        }

        $groupResult = mysql_query($groupQuery);
        if ($groupResult) {
            if ($this->group->Id == 0) {
                $this->group->Id = mysql_insert_id();
                $baukastenLog->defineLogEntry("Baukasten", "Hinzufügen der Gruppe $name");
                $baukastenLog->logUpload();
                if (!is_null($parentGroup)) {
                    $relationQuery = "insert into eeGroupGroupRelation(parentGroupID,childGroupID)
					values(" . $parentGroup . "," . $this->group->Id . ")";
                    $relationResult = mysql_query($relationQuery);
                }
            } else {
                $baukastenLog->defineLogEntry("Baukasten", "Gruppe " . $singleGroupName['name'] . " in $name umbenannt. Öffentlichkeit von " . ($singleGroupName['IsOpenGroup'] == "1" ? "true" : "false") . " zu $isOpenGroup geändert.");
                $baukastenLog->logUpload();
            }

            return $this->group;
        }
        return ($this->group = null);
    }

    /*
     * Löscht die Gruppe wenn man darauf Rechte hat.
     *
     */
    public function DeleteGroup($groupID, $ownOnly = 1)
    {
        $baukastenLog = new LoggingTool();
        $groupID = mysql_real_escape_string($groupID);

        $getGroupName = "SELECT name FROM eegroup WHERE id = " . $groupID;
        $getGroupName = mysql_query($getGroupName);
        $singleGroupName = mysql_fetch_array($getGroupName);

        $groupDel = "DELETE FROM eegroup WHERE id = $groupID";
        if ($ownOnly == 1) {
            $groupDel .= " AND (userId = " . mysql_real_escape_string($_COOKIE['c_loged']) . " OR IsOpenGroup = true)";
        }

        $groupResult = mysql_query($groupDel);

        if ($groupResult) {
            $baukastenLog->defineLogEntry("Baukasten", "Gruppe " . $singleGroupName['name'] . " gelöscht.");
            $baukastenLog->logUpload();
            return true;
        } else {
            return null;
        }
    }

    public function AddParent($parentId, $groupId, $ownOnly = 1): ?bool
    {
        $baukastenLog = new LoggingTool();
        $userId = $_COOKIE["c_loged"];
        if ($parentId != $groupId) {
            $groupSelect = "SELECT * FROM eegroup WHERE id = " . mysql_real_escape_string($groupId);
            if ($ownOnly == 1) {
                $groupSelect .= " AND (userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true)";
            }
            $groupResult = mysql_query($groupSelect);
            if (mysql_num_rows($groupResult) == 1) {
                $groupSelect = "SELECT * FROM eegroup WHERE id = " . mysql_real_escape_string($parentId);
                if ($ownOnly == 1) {
                    $groupSelect .= " AND (userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true)";
                }
                $groupResult1 = mysql_query($groupSelect);
                if (mysql_num_rows($groupResult1) == 1) {
                    $groupSelect2 = "SELECT * FROM eeGroupGroupRelation WHERE parentGroupID = " . mysql_real_escape_string($groupId)
                        . " AND childGroupID = " . mysql_real_escape_string($parentId);
                    $groupResult2 = mysql_query($groupSelect2);
                    if (mysql_num_rows($groupResult2) == 0) {
                        $parentQuery = "insert into eeGroupGroupRelation (parentGroupID,childGroupID)" .
                            " values(" . $parentId . "," . $groupId . ")";
                        mysql_query($parentQuery);
                        $groupResult = mysql_fetch_assoc($groupResult);
                        $groupResult1 = mysql_fetch_assoc($groupResult1);
                        $baukastenLog->defineLogEntry("Baukasten", "Gruppe " . mysql_real_escape_string($groupResult['name']) . " zu " . mysql_real_escape_string($groupResult1['name']) . " hinzugefügt.");
                        $baukastenLog->logUpload();
                        return true;
                    }
                }
            }
        }
        return null;
    }

    public function RemoveParent($parentId, $groupId, $ownOnly = 1): ?bool
    {
        $baukastenLog = new LoggingTool();
        $userId = $_COOKIE["c_loged"];
        $groupSelect = "SELECT * FROM eegroup WHERE id = " . mysql_real_escape_string($groupId);
        if ($ownOnly == 1) {
            $groupSelect .= " AND (userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true)";
        }
        $groupResult = mysql_query($groupSelect);
        if (mysql_num_rows($groupResult) == 1) {
            $groupSelect = "SELECT * FROM eegroup WHERE id = " . mysql_real_escape_string($parentId);
            if ($ownOnly == 1) {
                $groupSelect .= " AND (userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true)";
            }
            $groupResult1 = mysql_query($groupSelect);
            if (mysql_num_rows($groupResult1) == 1) {
                $parentQuery = "DELETE FROM eeGroupGroupRelation WHERE parentGroupID = " . mysql_real_escape_string($parentId) .
                    " AND childGroupID = " . mysql_real_escape_string($groupId);
                $parentQuery = mysql_query($parentQuery);
                $groupResult = mysql_fetch_assoc($groupResult);
                $groupResult1 = mysql_fetch_assoc($groupResult1);
                $baukastenLog->defineLogEntry("Baukasten", "Obergruppe " . mysql_real_escape_string($groupResult1['name']) . " von " . mysql_real_escape_string($groupResult['name']) . " entfernt.");
                $baukastenLog->logUpload();
                return true;
            }
        }
        return null;
    }

    public function GetGroupListByUser($userId, $admin = 1): array
    {
        if ($admin == 0) {
            $groupsSelect = "select * from eegroup where userId = " . mysql_real_escape_string($userId) . " or IsOpenGroup = true";
        } else {
            $groupsSelect = "select * from eegroup";
        }
        $groupsResult = mysql_query($groupsSelect);

        $groups = array();

        while ($singleGroup = mysql_fetch_array($groupsResult)) {
            $group = new Group();
            $group->SetValues($singleGroup);
            $groups[] = $group;
        }

        return $groups;
    }

    public function GetGroupTreeByUser(?int $userId, $groupId, $params = null, bool $ownOnly = true): string
    {
        // Darstellung der Liste
        $result = "<ul class='baukastenListe'>";

        if (isset($groupId)) {
            // Parents Darstellung
            $result .= '<li><p class="homeGroup">&xlarr; Home</p></li>';
            $parentsSelect = "SELECT * FROM eegroup LEFT JOIN eeGroupGroupRelation ON eegroup.ID = eeGroupGroupRelation.parentGroupID WHERE ";
            if ($ownOnly && $userId != null) {
                $parentsSelect .= "(userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true) AND ";
            }
            $parentsSelect .= "eeGroupGroupRelation.childGroupID = " . mysql_real_escape_string($groupId) . " ORDER BY name ASC";

            $parentsResult = mysql_query($parentsSelect);
            if (mysql_num_rows($parentsResult) !== 0) {
                while ($parentGroup = mysql_fetch_object($parentsResult)) {
                    $result .= "<li><p class='gruppe' value=\"" . $parentGroup->id . "\">&xlarr; " . $parentGroup->name . "</p></li>";
                }
            }

            // Aktuelle Gruppe
            $mainSelect = "SELECT * FROM eegroup WHERE ";
            if ($ownOnly && $userId != null) {
                $mainSelect .= "(userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true) AND ";
            }
            $mainSelect .= "id = " . mysql_real_escape_string($groupId);

            $selectedGroup = mysql_query($mainSelect);
            if (mysql_num_rows($selectedGroup) !== 0) {
                $selectedGroup = mysql_fetch_object($selectedGroup);
                $result .= "<input type='hidden' id='aktuelleGruppe' value='" . $selectedGroup->id . "'>";
                $result .= "<li class='aktuelleGruppe'><p><b>" . $selectedGroup->name . "</b></p></li>";
            }
        }

        // Child Darstellung
        $groupsSelect = "SELECT * FROM eegroup LEFT JOIN eeGroupGroupRelation ON eegroup.ID = eeGroupGroupRelation.childGroupID WHERE ";
        if ($ownOnly && $userId != null) {
            $groupsSelect .= "(userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true) AND ";
        }
        $groupsSelect .= !isset($groupId) ? "eeGroupGroupRelation.parentGroupID IS NULL" : "eeGroupGroupRelation.parentGroupID = " . mysql_real_escape_string($groupId);
        $groupsSelect .= " ORDER BY name ASC";
        $groupsResult = mysql_query($groupsSelect);
        if (mysql_num_rows($groupsResult) !== 0) {
            while ($singleGroup = mysql_fetch_object($groupsResult)) {
                $result .= "<li><p class='gruppe' value=\"" . $singleGroup->id . "\">" . $singleGroup->name . "</p></li>";
            }
        }
        return $result . "</ul>";
    }

    public function GetParents($userId, $groupId, $ownOnly)
    {
        if (isset($groupId)) {
            $result = "<ul>";
            $parentsSelect = "SELECT * FROM eegroup LEFT JOIN eeGroupGroupRelation ON eegroup.ID = eeGroupGroupRelation.parentGroupID WHERE ";
            if ($ownOnly == 1) {
                $parentsSelect .= "(userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true) AND ";
            }
            $parentsSelect .= "eeGroupGroupRelation.childGroupID = " . mysql_real_escape_string($groupId) . " ORDER BY name ASC";

            $parentsResult = mysql_query($parentsSelect);
            if (mysql_num_rows($parentsResult) !== 0) {
                while ($parentGroup = mysql_fetch_object($parentsResult)) {
                    $result .= "<li><p class='parentGroup' value=\"" . $parentGroup->id . "\"> &#10006; " . $parentGroup->name . "</p></li>";
                }
                $result .= "</ul>";
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function CheckIfExist(?int $userId, $groupId, bool $ownOnly = true)
    {
        $mainSelect = "SELECT * FROM eegroup WHERE ";
        if ($ownOnly && $userId != null) {
            $mainSelect .= "(userId = " . mysql_real_escape_string($userId) . " OR IsOpenGroup = true) AND ";
        }
        $mainSelect .= "id = " . mysql_real_escape_string($groupId);

        $selectedGroup = mysql_query($mainSelect);
        if (mysql_num_rows($selectedGroup) !== 0) {
            return true;
        } else {
            return false;
        }
    }

    public function SearchGroupSelect($textSearch, $selectId, $ownOnly = 1)
    {
        $mainSelect = "SELECT * FROM eegroup WHERE ";
        if ($ownOnly == 1) {
            $mainSelect .= "(userId = " . $_COOKIE['c_loged'] . " OR IsOpenGroup = true) AND ";
        }
        $mainSelect .= "name LIKE '%" . mysql_real_escape_string($textSearch) . "%' AND id != " . mysql_real_escape_string($selectId) . " ORDER BY name ASC";
        $mainSelect = mysql_query($mainSelect);
        if (mysql_num_rows($mainSelect) !== 0) {
            while ($singleGroup = mysql_fetch_array($mainSelect)) {
                $result .= "<option value='" . $singleGroup['id'] . "'>" . htmlspecialchars($singleGroup['name']) . "</option>";
            }
            return $result;
        } else {
            return false;
        }
    }

    public function SearchGroupParent($groupId, $ownOnly = 1)
    {
        $parentsSelect = "SELECT * FROM eegroup LEFT JOIN eeGroupGroupRelation ON eegroup.ID = eeGroupGroupRelation.parentGroupID WHERE ";
        if ($ownOnly == 1) {
            $parentsSelect .= "(userId = " . $_COOKIE['c_loged'] . " OR IsOpenGroup = true) AND ";
        }
        $parentsSelect .= "eeGroupGroupRelation.childGroupID = " . mysql_real_escape_string($groupId) . " ORDER BY name ASC";

        $parentsResult = mysql_query($parentsSelect);
        if (mysql_num_rows($parentsResult) !== 0) {
            $result = "<ul id='parentGroupList'>";
            while ($parentGroup = mysql_fetch_object($parentsResult)) {
                $result .= "<li><p class='parentGroupSearch' value=\"" . $parentGroup->id . "\">" . $parentGroup->name . "</p></li>";
            }
            $result .= "</ul>";
            return $result;
        } else {
            return false;
        }

    }

    public function GetGroupEffects()
    {
        if ($this->group == null) {
            return;
        }

        $effectViewModel = new EffectViewModel();

        return ($this->effects = $effectViewModel->GetEffectsByGroup($this->group));


    }

    public function AddOrUpdateEffectToGroup($post, $userId, $ownOnly = 1)
    {
        $this->GetGroup(mysql_real_escape_string($post["groupId"]), $userId, $ownOnly);

        if ($this->group == null) {
            return false;
        }
        $effect = new Effect();
        $effect->Id = mysql_real_escape_string($post["id"]);
        $effect->Name = mysql_real_escape_string(htmlspecialchars($post["name"]));
        $effect->Description = mysql_real_escape_string(htmlspecialchars(str_replace('\"', '"', $post["description"])));
        $effect->Costs = mysql_real_escape_string($post["costs"]);
        $effect->Rank = mysql_real_escape_string($post["rank"]);
        $effect->maxVal = mysql_real_escape_string($post["maxVal"]);
        $effect->IsPublic = mysql_real_escape_string($post["isPublic"]);
        $effect->Group = $this->group;
        $effect->GroupId = $this->group->Id;
        $effect->UserId = $userId;
        $effect->IsAdvantage = $post["isAdvantage"];
        $effect->IsUpToDate = $post["isUpToDate"];
        $effect->freeAction = $post["freeAction"];
        $effect->kindOfCosts = $post["kindOfCosts"];
        $effect->affectAll = $post["affectAll"];
        $effectViewModel = new EffectViewModel();


        if ($effectViewModel->SaveOrUpdateEffect($effect)) {
            return true;
        }
    }

    public function DeleteEffect($effectID, $ownOnly = 1)
    {
        $baukastenLog = new LoggingTool();
        $effectID = mysql_real_escape_string($effectID);

        $getEffectName = "SELECT name FROM eeeffect WHERE id = " . $effectID;
        $getEffectName = mysql_query($getEffectName);
        $singleEffectName = mysql_fetch_array($getEffectName);

        $effectDel = "DELETE FROM eeeffect WHERE id = $effectID";
        if ($ownOnly == 1) {
            $effectDel .= " AND (userId = " . mysql_real_escape_string($_COOKIE['c_loged']) . " OR isPublic = true)";
        }

        $effectResult = mysql_query($effectDel);

        if ($effectResult) {
            $baukastenLog->defineLogEntry("Baukasten", "Effekt " . $singleEffectName['name'] . " gelöscht.");
            $baukastenLog->logUpload();
            return true;
        } else {
            return null;
        }
    }

    public function GetGroupEffect($effectId, $userId, $ownOnly = 1)
    {
        $effectViewModel = new EffectViewModel();

        $selectedEffect = $effectViewModel->GetEffectById($effectId, $_COOKIE["c_loged"], $ownOnly);

        if ($selectedEffect == null) {
            return "failed";
        }

        $this->GetGroup($selectedEffect->GroupId, $userId, $ownOnly);

        $selectedEffect->Group = $this->Group;

        return $selectedEffect;
    }

    public function UrlReverse(string $description): string
    {
        $description = str_replace("<a href='", "[url]", $description);
        $description = str_replace("'>Link</a>", "[/url]", $description);
        return $description;
    }
}
