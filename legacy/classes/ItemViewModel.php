<?php

final class ItemViewModel
{
    public ?Item $item = null;
    public array $effects;

    public function SetItem(?\Item $item): void
    {
        $this->item = $item;
    }

    public function NewItem($id): void
    {
        $this->item = new Item();
        $this->item->SetValues($id);
    }

    public function GetItemEffects(): void
    {
        if ($this->item == null) {
            return;
        }

        $effectViewModel = new EffectViewModel();
        $this->effects = $effectViewModel->GetEffectsByItem($this->item);
    }

    public function connectItemEffects(?string $eId, $effectConnection, $conId)
    {
        if ($this->item->Id == null || $eId == null || $effectConnection == null) {
            return;
        }
        $effectQuery = "UPDATE eeEffectsItem SET connectionGroup = $effectConnection WHERE `iId` = '" . $this->item->Id . "' AND `eId` = '" . $eId . "' AND connectionGroup = $conId";
        if (mysql_query($effectQuery)) {
            return true;
        }
    }

    public function AddOrDeleteEffectFromItem(?string $eId, $conId = null, $del = null): void
    {
        if ($this->item->Id == null || $eId == null) {
            return;
        }

        if ($del == null) {
            $effectQuery = "INSERT INTO eeEffectsItem (iId,eId,connectionGroup) VALUES ('" . $this->item->Id . "','" . $eId . "',0)";
        } else {
            $effectQuery = "DELETE FROM eeEffectsItem WHERE `iId` = '" . $this->item->Id . "' AND `eId` = '" . $eId . "' AND `connectionGroup` = '$conId'";
        }

        mysql_query($effectQuery);
    }

    public function GetGroupEffect($effectId, $userId): ?\Effect
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
