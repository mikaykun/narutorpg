<?php

namespace NarutoLegacy\Components;

class Inventory
{
    public array $items;

    public static function getInventory(int $id): self
    {
        $pdo = nrpg_get_database();
        $stmt = $pdo->prepare('SELECT * FROM Item WHERE Von = :id');
        $stmt->execute(['id' => $id]);

        $inventory = new self();
        $inventory->items = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $inventory;
    }
}
