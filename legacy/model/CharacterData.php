<?php

use NarutoLegacy\Components\Inventory;

#[AllowDynamicProperties]
final class CharacterData
{
    public int $id = 0;
    public ?string $Heimatdorf = '';
    public int|float $Chakra;
    public int|float $Ninjutsu;
    public int|float $Geschwindigkeit;
    public int|float $Stärke;
    public int|float $Taijutsu;
    public int|float $Ausdauer;
    public int|float $Verteidigung;
    public int|float $Genjutsu;
    public bool $feddig = false;
    public ?string $Rang = '';
    public ?string $Training;
    public string $name;
    public string $Dauer;
    public ?string $Biswert;
    public int $doubleup;
    public int $Bonustage;
    public bool $Spielleiter = false;
    public int $Rangwert = 0;
    public int $Geld;
    public ?string $Standort;
    public int $Meldeverbot;
    public int $Landoberhaupt = 0;
    public ?string $Team;
    public int $Trainingspunkte = 0;
    public int $Niveau = 0;

    public function getInventory(): Inventory
    {
        return Inventory::getInventory($this->id);
    }
}
