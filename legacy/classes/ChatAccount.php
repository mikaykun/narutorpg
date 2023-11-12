<?php

class ChatAccount
{
    public $Id = 0;
    public $Name;
    public $npc;

    public function SetValues(array $get): void
    {
        $this->Id = $get["uid"];
        $this->Name = $get["uname"];
        $this->npc = $get["npc"];
    }
}
