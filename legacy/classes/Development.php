<?php

class Development
{
    public $Id = 0;
    public $Name;
    public $Description;
    public $Rank;
    public $IsPublic;
    public $ParentId;
    public $UserId;
    public $Type;

    public function SetValues(array $get): void
    {
        $this->Id = $get["id"];
        $this->Name = $get["name"];
        $this->Description = $get["description"];
        $this->Rank = $get["rank"];
        $this->IsPublic = $get["isPublic"];
        $this->ParentId = $get["parentId"];
        $this->UserId = $get["userId"];
        $this->Type = $get["type"];
    }
}
