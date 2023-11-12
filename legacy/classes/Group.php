<?php

final class Group
{
    public int $Id = 0;
    public $Name;
    public $ParentGroup;
    public $UserId;
    public bool $IsOpenGroup;

    public function __construct($name, $parentGroup, $userId, bool $isOpenGroup)
    {
        $this->Name = $name;
        $this->ParentGroup = $parentGroup;
        $this->UserId = $userId;
        $this->IsOpenGroup = $isOpenGroup;
    }

    public function SetValues(array $get): void
    {
        $this->Id = intval($get["id"]);
        $this->Name = $get["name"];
        $this->UserId = $get["userId"];
        $this->IsOpenGroup = $get["IsOpenGroup"];
    }
}
