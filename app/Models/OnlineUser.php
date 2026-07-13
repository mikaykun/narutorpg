<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $Land
 */
#[Table('onlineuser')]
#[WithoutTimestamps]
final class OnlineUser extends Model
{
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'Land' => 'string',
        ];
    }
}
