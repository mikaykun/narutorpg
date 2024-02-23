<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties]
#[ORM\Table(name: 'user')]
final class CharacterData
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    public int $id = 0;
    public string $Heimatdorf = '';
    public int|float $Chakra;
    public int|float $Ninjutsu;
    public int|float $Geschwindigkeit;
    public int|float $Stärke;
    public int|float $Taijutsu;
    public int|float $Ausdauer;
    public int|float $Verteidigung;
    public int|float $Genjutsu;
    public bool $feddig = false;
    public string $Rang = '';
    public string $Training;
    public string $name;
    public string $Dauer;
    public string $Biswert;
    public int $doubleup;
    public int $Bonustage;
    public bool $Spielleiter;
    public int $Rangwert = 0;
    public int $Geld;
    public string $Standort;
    public int $Meldeverbot;
    #[ORM\Column(type: 'integer')]
    public int $Landoberhaupt = 0;
    public string $Team;
    #[ORM\Column(type: 'integer')]
    public int $Trainingspunkte = 0;
}
