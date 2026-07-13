<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $Text
 * @property string $Datum
 * @property int $time
 */
#[Table('Neuerungen')]
#[Fillable(['Text', 'Datum', 'time'])]
#[WithoutTimestamps]
final class GameUpdate extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'Text' => 'string',
            'Datum' => 'string',
            'time' => 'integer',
        ];
    }
}
