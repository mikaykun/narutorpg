<?php

final class Group
{
    public int $Id = 0;
    public string $Name;
    public ?int $ParentGroup = null;
    public ?int $UserId = null;
    public bool $IsOpenGroup;

    public function SetValues(array $get): void
    {
        $this->Id = (int)($get["id"]);
        $this->Name = $get["name"];
        $this->UserId = $get["userId"];
        $this->IsOpenGroup = $get["IsOpenGroup"];
    }

    public function setParentGroup(?int $ParentGroup): void
    {
        $this->ParentGroup = $ParentGroup;
    }
}
