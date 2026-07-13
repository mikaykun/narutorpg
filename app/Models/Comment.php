<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $kommentar
 * @property string $date
 * @property int $newsid
 * @property int $lol
 */
#[Table('Kommentare')]
#[Fillable(['name', 'kommentar', 'date', 'newsid', 'lol'])]
#[WithoutTimestamps]
final class Comment extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'kommentar' => 'string',
            'date' => 'string',
            'newsid' => 'integer',
            'lol' => 'integer',
        ];
    }
}
