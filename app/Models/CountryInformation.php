<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $Land
 * @property string $Gesetze
 * @property string $Staatsform
 * @property string $Ratrecht
 * @property string $Wetter
 * @property string $Wettergestern
 * @property string $Wettermorgen
 * @property string $VerboteneJutsu
 */
#[Table('Landdaten')]
#[WithoutTimestamps]
final class CountryInformation extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'Land' => 'string',
            'Gesetze' => 'string',
            'Staatsform' => 'string',
            'Ratrecht' => 'string',
            'Wetter' => 'string',
            'Wettergestern' => 'string',
            'Wettermorgen' => 'string',
            'VerboteneJutsu' => 'string',
        ];
    }
}
