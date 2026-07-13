<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class LiveSearchController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $term = (string) $request->query('q', '');

        $results = DB::table('user')
            ->select(['name'])
            ->where('name', 'like', $term.'%')
            ->where('zeigen', '=', '')
            ->orderByDesc('name')
            ->limit(10)
            ->pluck('name')
            ->map(static fn (string $name): string => sprintf(
                '<a href="/userpopup.php?usernam=%s">%s</a><br>',
                urlencode($name),
                e($name),
            ))
            ->implode('');

        return response($results);
    }
}
