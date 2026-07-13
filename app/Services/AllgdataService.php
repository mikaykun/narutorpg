<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Allgdata;

final class AllgdataService
{
    public function current(): Allgdata
    {
        return Allgdata::query()->findOrFail(1);
    }

    public function incrementKampfe(): void
    {
        Allgdata::query()->whereKey(1)->increment('Kampfe');
    }
}
