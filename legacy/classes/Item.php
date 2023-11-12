<?php

class Item
{
    public $Id = 0;

    public function __construct() {}

    public function SetValues($id): void
    {
        $this->Id = $id;
    }
}
