<?php

class ItemViewModel
{
    public $item;
    public $effects;

    public function __construct() {}

    public function SetItem($item): void
    {
        $this->item = $item;
    }

    public function NewItem($id): void
    {
        $this->item = new Item();
        $this->item->SetValues($id);
    }

    public function GetItem($itemId, $userId)
    {
        //currently not in use
    }

    public function SaveOrUpdateItem()
    {
        //currently not in use
    }

    public function GetItemListByUser($userId)
    {
        //currently not in use
    }

    public function GetItemTreeByUser($userId, $parentId, $params = null)
    {
        //currently not in use
    }

    public function GetItemEffects()
    {
        if ($this->item == null) {
            return;
        }

        $effectViewModel = new EffectViewModel();

        return ($this->effects = $effectViewModel->GetEffectsByItem($this->item));
    }

    public function connectItemEffects($eId, $effectConnection, $conId)
    {
        if ($this->item->Id == null || $eId == null || $effectConnection == null) {
            return;
        }
        $effectQuery = "UPDATE eeEffectsItem SET connectionGroup = $effectConnection
		WHERE `iId` = '" . $this->item->Id . "' AND `eId` = '" . $eId . "' AND connectionGroup = $conId";
        if (mysql_query($effectQuery)) {
            return true;
        }
        return;
    }

    public function AddOrDeleteEffectFromItem($eId, $conId = null, $del = null): void
    {
        if ($this->item->Id == null || $eId == null) {
            return;
        }

        if ($del == null) {
            $effectQuery = "insert into eeEffectsItem (iId,eId,connectionGroup)" .
                " values('" . $this->item->Id . "','" . $eId . "',0)";
        } else {
            $effectQuery = "DELETE FROM eeEffectsItem WHERE `iId` = '" . $this->item->Id . "' AND `eId` = '" . $eId . "' AND `connectionGroup` = '$conId'";
        }

        mysql_query($effectQuery);
    }

    public function GetGroupEffect($effectId, $userId)
    {
        $effectViewModel = new EffectViewModel();

        $selectedEffect = $effectViewModel->GetEffectById($_GET["effectId"], $_COOKIE["c_loged"]);

        if ($selectedEffect == null) {
            return null;
        }

        $this->GetGroup($selectedEffect->GroupId, $userId);

        $selectedEffect->Group = $this->Group;

        return $selectedEffect;
    }
}
