<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $Jahr
 * @property int $Inaktiv
 * @property int $TageStandby
 * @property int $Tagenachricht
 * @property int $MaxEEs
 * @property int $Kampfe
 * @property int $alleTBTAusgegeben
 */
#[Table('allgdata')]
#[WithoutTimestamps]
final class Allgdata extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'Jahr' => 'integer',
            'Inaktiv' => 'integer',
            'TageStandby' => 'integer',
            'Tagenachricht' => 'integer',
            'MaxEEs' => 'integer',
            'Kampfe' => 'integer',
            'alleTBTAusgegeben' => 'integer',
        ];
    }
}
