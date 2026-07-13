<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $Titel
 * @property string $Text
 * @property string $Datum
 * @property string $Datum1
 * @property int $Verfasser
 * @property string $Land
 */
#[Table('Landnews')]
#[Fillable(['Titel', 'Text', 'Datum', 'Datum1', 'Verfasser', 'Land'])]
#[WithoutTimestamps]
final class Landnews extends Model
{
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'Titel' => 'string',
            'Text' => 'string',
            'Datum' => 'string',
            'Datum1' => 'string',
            'Verfasser' => 'integer',
            'Land' => 'string',
        ];
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'newsid');
    }
}
