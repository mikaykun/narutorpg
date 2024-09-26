<?php

final class JutsuViewModel
{
    public ?Jutsu $jutsu = null;

    /**
     * @var array<Effect>
     */
    public array $effects = [];

    public function SetJutsu(Jutsu $jutsu): void
    {
        $this->jutsu = $jutsu;
    }

    public function NewJutsu(int $id): void
    {
        $this->jutsu = new Jutsu($id);
    }

    public function GetJutsuEffects(): void
    {
        if ($this->jutsu == null) {
            return;
        }

        $effectViewModel = new EffectViewModel();
        $this->effects = $effectViewModel->GetEffectsByJutsu($this->jutsu);
    }

    public function connectJutsuEffects($eId, $effectConnection, $conId): bool
    {
        if ($this->jutsu->getId() == null || $eId == null || $effectConnection == null) {
            return false;
        }
        $effectQuery = "UPDATE eeEffectsJutsu SET connectionGroup = $effectConnection WHERE `jId` = '" . $this->jutsu->getId() . "' AND `eId` = '" . $eId . "' AND connectionGroup = $conId";
        if (mysql_query($effectQuery)) {
            return true;
        }
        return false;
    }

    public function AddOrDeleteEffectFromJutsu($eId, $conId = null, $del = null): void
    {
        if ($this->jutsu->getId() == null || $eId == null) {
            return;
        }

        if ($del == null) {
            $effectQuery = "insert into eeEffectsJutsu (jId,eId,connectionGroup)" .
                " values('" . $this->jutsu->getId() . "','" . $eId . "',0)";
        } else {
            $effectQuery = "DELETE FROM eeEffectsJutsu WHERE `jId` = '" . $this->jutsu->getId() . "' AND `eId` = '" . $eId . "' AND `connectionGroup` = '$conId'";
        }

        mysql_query($effectQuery);
    }

    public function GetGroupEffect($effectId, $userId): ?Effect
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
