<?php

final class DevelopmentViewModel
{
    public ?Development $development = null;
    public $developmentEffects;

    public function SetDevelopment(Development $development): void
    {
        $this->development = $development;
    }

    public function NewDevelopment(array $post, $userId): void
    {
        $this->development = new Development();

        $this->development->Name = mysql_real_escape_string($post["name"]);
        $this->development->Description = mysql_real_escape_string($post["description"]);
        $this->development->Rank = mysql_real_escape_string($post["rank"]);
        $this->development->IsPublic = mysql_real_escape_string($post["isPublic"]);
        $this->development->ParentId = mysql_real_escape_string($post["parentId"]);
        $this->development->UserId = $userId;
        $this->development->Type = mysql_real_escape_string($post["type"]);
    }

    public function GetDevelopment($developmentId, $userId): void
    {
        $developmentSelect = "select * from eedevelopment where (id = " . mysql_real_escape_string($developmentId) . " and userId = " . mysql_real_escape_string($userId) . ") or (id = " . mysql_real_escape_string($developmentId) . " and isPublic = true)";

        $developmentResult = mysql_query($developmentSelect);

        if (mysql_num_rows($developmentResult) == 0) {
            $this->development = null;
            return;
        }

        $this->development = new Development();

        $this->development->SetValues(mysql_fetch_array($developmentResult));
    }

    public function SaveOrUpdateDevelopment(): ?Development
    {
        if ($this->development == null) {
            return null;
        }

        $name = mysql_real_escape_string($this->development->Name);
        $parentId = mysql_real_escape_string($this->development->ParentId);
        $userId = mysql_real_escape_string($this->development->UserId);

        if ($this->development->Id == 0) {
            $developmentQuery = "insert into eedevelopment (name,description,rank,isPublic,parentId,userId,type)" .
                " values('" . $name . "','',0,0," . ($parentId == null ? "null" : $parentId) . "," . $userId . ",0)";
        } else {
            $isPublic = mysql_real_escape_string($this->development->IsOpenGroup);
            $description = mysql_real_escape_string($this->development->Description);
            $rank = mysql_real_escape_string($this->development->Rank);
            $parentId = mysql_real_escape_string($this->development->ParentId);
            $type = mysql_real_escape_string($this->development->Type);

            $developmentQuery = "update eedevelopment set name = '" . $name . "', userId = " . $userId;
            $developmentQuery .= ($parentId == null) ? "" : ", parentId = " . $parentId;
            $developmentQuery .= (!$isPublic) ? "" : ", isPublic = " . $isPublic;
            $developmentQuery .= ($description == null) ? "" : ", description = '" . $description . "'";
            $developmentQuery .= ($rank == null) ? "" : ", rank = " . $rank;
            $developmentQuery .= ($type == null) ? "" : ", type = " . $type;

            $developmentQuery .= " where id = " . mysql_real_escape_string($this->development->Id) . " and userId = " . $userId;
        }

        $developmentResult = mysql_query($developmentQuery);

        if ($developmentResult) {
            if ($this->development->id == 0) {
                $this->development->id = mysql_insert_id();
            }

            return $this->development;
        }
        return null;
    }

    public function GetDevelopmentListByUser($userId)
    {

        $developmentsSelect = "select * from eedevelopment where userId = " . mysql_real_escape_string($userId) . " or isPublic = true";

        $developmentsResult = mysql_query($developmentsSelect);

        $developments = [];

        while ($singleDevelopment = mysql_fetch_array($developmentsResult)) {
            $development = new Development();
            $development->SetValues($singleDevelopment);
            $developments[] = $development;
        }

        return $developments;
    }

    public function GetDevelopmentTreeByUser($userId, $parentId): string
    {
        $developmentsSelect = "select * from eedevelopment where (userId = " . mysql_real_escape_string($userId) . " or isPublic = true) and ";
        $developmentsSelect .= !isset($parentId) ? "parentId is null" : "parentId = " . mysql_real_escape_string($parentId);
        $developmentsResult = mysql_query($developmentsSelect);

        if (mysql_num_rows($developmentsResult) === 0) {
            return "";
        }

        $result = "<ul>";
        while ($singleDevelopment = mysql_fetch_object($developmentsResult)) {
            $result .= "<li><input type=\"checkbox\" /><label><a href=" . $_PHP["Self"] . "?selectedId=" . $singleDevelopment->id . ">" . $singleDevelopment->name . "</a>
			<a href=\"" . $_PHP["Self"] . "?parentId=" . $singleDevelopment->id . "&developmentAction=newDevelopment\">[+]</a>
			</label>" . $this->GetDevelopmentTreeByUser($userId, $singleDevelopment->id) . "</li>";

        }

        return $result . "</ul>";
    }

    public function GetDevelopmentEffects()
    {
        if ($this->development == null) {
            return;
        }

        $developmentEffectSelect = "select effectId from eedevelopmenthaseffect  where developmentId = " . $this->development->Id;

        $result = mysql_query($developmentEffectSelect);
        $this->developmentEffects = [];

        while ($effectId = mysql_fetch_array($result)) {
            $this->developmentEffects[] = $effectId["effectId"];
        }
    }

    public function ClearEffects()
    {
        $developmentEffectDelete = "delete from eedevelopmenthaseffect where developmentId = " . $this->development->Id;

        return mysql_query($developmentEffectDelete);
    }

    public function AddEffectToDevelopment($effectId)
    {

        if ($this->development == null) {
            return;
        }

        $developmentEffectInsert = "insert into eedevelopmenthaseffect (developmentId,effectId) values(" . mysql_real_escape_string($this->development->Id) . "," . mysql_real_escape_string($effectId) . ")";

        return mysql_query($developmentEffectInsert);
    }

    public function GetDevelopmentEffect($effectId, $userId)
    {

        $effectViewModel = new EffectViewModel();

        $selectedEffect = $effectViewModel->GetEffectById($_GET["effectId"], $userId);

        if ($selectedEffect == null) {
            return null;
        }

        $this->GetDevelopment($selectedEffect->GroupId, $userId);

        $selectedEffect->Group = $this->Group;

        return $selectedEffect;
    }

    public function IsEffectSet($effectId): bool
    {
        if ($this->development == null || $this->developmentEffects == null) {
            return false;
        }
        return in_array($effectId, $this->developmentEffects, false);
    }
}
