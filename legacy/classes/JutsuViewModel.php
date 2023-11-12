<?php

class JutsuViewModel
{
    public $jutsu;
    public $effects;

    public function __construct() {}

    public function SetJutsu($jutsu): void
    {
        $this->jutsu = $jutsu;
    }

    public function NewJutsu($id): void
    {
        $this->jutsu = new Jutsu();
        $this->jutsu->SetValues($id);
    }

    public function GetJutsu($jutsuId, $userId)
    {
        //currently not in use
    }

    public function SaveOrUpdateJutsu()
    {
        //currently not in use
    }

    public function GetJutsuListByUser($userId)
    {
        //currently not in use
    }

    public function GetJutsuTreeByUser($userId, $parentId, $params = null)
    {
        //currently not in use
    }

    public function GetJutsuEffects()
    {
        if ($this->jutsu == null) {
            return;
        }

        $effectViewModel = new EffectViewModel();

        return ($this->effects = $effectViewModel->GetEffectsByJutsu($this->jutsu));

    }

    public function connectJutsuEffects($eId, $effectConnection, $conId)
    {
        if ($this->jutsu->Id == null || $eId == null || $effectConnection == null) {
            return;
        }
        $effectQuery = "UPDATE eeEffectsJutsu SET connectionGroup = $effectConnection
		WHERE `jId` = '" . $this->jutsu->Id . "' AND `eId` = '" . $eId . "' AND connectionGroup = $conId";
        if (mysql_query($effectQuery)) {
            return true;
        }
        return;
    }

    public function AddOrDeleteEffectFromJutsu($eId, $conId = null, $del = null)
    {
        if ($this->jutsu->Id == null || $eId == null) {
            return;
        }

        if ($del == null) {
            $effectQuery = "insert into eeEffectsJutsu (jId,eId,connectionGroup)" .
                " values('" . $this->jutsu->Id . "','" . $eId . "',0)";
        } else {
            $effectQuery = "DELETE FROM eeEffectsJutsu WHERE `jId` = '" . $this->jutsu->Id . "' AND `eId` = '" . $eId . "' AND `connectionGroup` = '$conId'";
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
