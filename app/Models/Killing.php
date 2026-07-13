<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $Text
 * @property string $Bygeloescht
 */
#[Table('Killing')]
#[WithoutTimestamps]
final class Killing extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'Text' => 'string',
            'Bygeloescht' => 'string',
        ];
    }
}
