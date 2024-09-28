<?php

namespace model;

#[\AllowDynamicProperties]
final class Jutsu
{
    public int $Taijutsu = 0;
    public int $Ninjutsu = 0;
    public int $Genjutsu = 0;

    public function getJutsuRang(): string
    {
        $rang = max(0, $this->Taijutsu, $this->Ninjutsu, $this->Genjutsu);

        return match($rang) {
            10 => "S-Rang",
            8, 9 => "A-Rang",
            6, 7 => "B-Rang",
            4, 5 => "C-Rang",
            2, 3 => "D-Rang",
            default => "E-Rang",
        };
    }

    public function getJutsuStufe(): int
    {
        return array_sum([$this->Taijutsu, $this->Ninjutsu, $this->Genjutsu]);
    }
}
