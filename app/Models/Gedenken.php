<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $Land
 * @property string $Datum
 * @property int $Missing
 */
#[Table('Gedenken')]
#[WithoutTimestamps]
final class Gedenken extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'Land' => 'string',
            'Datum' => 'string',
            'Missing' => 'integer',
        ];
    }
}
