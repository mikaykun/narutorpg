<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CountryInformation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:update-weather')]
#[Description('Update the weather for all villages.')]
final class UpdateWeather extends Command
{
    private array $weatherChances = [
        'Konoha' => ['sun' => 70, 'rain' => 80, 'clouds' => 95, 'villageSpecificWeather' => 'Stürmisch', 'canSnow' => true],
        'Kusa' => ['sun' => 70, 'rain' => 80, 'clouds' => 95, 'villageSpecificWeather' => 'Stürmisch', 'canSnow' => true],
        'Iwa' => ['sun' => 55, 'rain' => 70, 'clouds' => 85, 'villageSpecificWeather' => 'Stürmisch', 'canSnow' => true],
        'Taki' => ['sun' => 40, 'rain' => 70, 'clouds' => 100, 'villageSpecificWeather' => 'Stark Regnerisch', 'canSnow' => true],
        'Kumo' => ['sun' => 20, 'rain' => 35, 'clouds' => 85, 'villageSpecificWeather' => 'Stürmisch', 'canSnow' => true],
        'Ame' => ['sun' => 15, 'rain' => 65, 'clouds' => 100, 'villageSpecificWeather' => 'Stark Regnerisch', 'canSnow' => false],
        'Suna' => ['sun' => 80, 'rain' => 85, 'clouds' => 95, 'villageSpecificWeather' => 'Sandsturm', 'canSnow' => false],
    ];

    public function handle(): int
    {
        DB::transaction(function (): void {
            $this->updateWeatherForAllVillages();
        });

        return self::SUCCESS;
    }

    private function updateWeatherForAllVillages(): void
    {
        $currentMonth = (int) date('n');

        foreach ($this->weatherChances as $village => $chance) {
            $randomIntForWeatherChance = random_int(1, 100);
            $newWeather = $this->getWeather($chance, $randomIntForWeatherChance, $currentMonth);

            $countryInformation = CountryInformation::query()
                ->where('Land', $village)
                ->first();

            if ($countryInformation === null) {
                continue;
            }

            $countryInformation->forceFill([
                'Wettergestern' => $countryInformation->Wetter,
                'Wetter' => $countryInformation->Wettermorgen,
                'Wettermorgen' => $newWeather,
            ]);

            $countryInformation->save();
        }
    }

    private function getWeatherType(bool $canSnow, int $currentMonth): string
    {
        if (($currentMonth <= 2 || $currentMonth >= 11) && $canSnow) {
            return 'Schneiend';
        }

        return 'Regnerisch';
    }

    private function getWeatherStrength(array $chanceOfWeather, int $weatherChanceKey, int $randomIntForWeatherChance): string
    {
        if (! isset($chanceOfWeather[$weatherChanceKey - 1]) || ! isset($chanceOfWeather[$weatherChanceKey])) {
            return '';
        }

        $absoluteWeatherChance = $chanceOfWeather[$weatherChanceKey] - $chanceOfWeather[$weatherChanceKey - 1];
        $harderWeatherRange = $absoluteWeatherChance / 20 * 9;
        $lowestWeatherBorder = $chanceOfWeather[$weatherChanceKey - 1] + $harderWeatherRange;

        if ($lowestWeatherBorder >= $randomIntForWeatherChance) {
            return 'Leicht ';
        }

        if (($lowestWeatherBorder + $harderWeatherRange) >= $randomIntForWeatherChance) {
            return '';
        }

        return 'Stark ';
    }

    private function getWeather(array $chance, int $randomIntForWeatherChance, int $currentMonth): string
    {
        if ($randomIntForWeatherChance <= $chance['sun']) {
            return 'Klarer Himmel';
        }

        if ($randomIntForWeatherChance <= $chance['rain']) {
            return $this->getWeatherStrength($chance, 1, $randomIntForWeatherChance).$this->getWeatherType((bool) $chance['canSnow'], $currentMonth);
        }

        if ($randomIntForWeatherChance <= $chance['clouds']) {
            return $this->getWeatherStrength($chance, 2, $randomIntForWeatherChance).'Bewölkt';
        }

        return $chance['villageSpecificWeather'];
    }
}
