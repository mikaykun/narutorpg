<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Landnews;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class FrontpageController extends Controller
{
    public function index(): RedirectResponse|View
    {
        if (auth()->check()) {
            return redirect('/Center.php');
        }

        $newsList = Landnews::query()
            ->select(['Landnews.Land', 'Landnews.Datum', 'Landnews.Text', 'user.name as Verfasser', 'Landnews.Titel'])
            ->leftJoin('user', 'user.id', '=', 'Landnews.Verfasser')
            ->where(static function ($query): void {
                $query->where('Landnews.Land', '=', '')
                    ->orWhereNull('Landnews.Land');
            })
            ->orderByDesc('Landnews.id')
            ->limit(5)
            ->get();

        return view('frontpage', [
            'newsList' => $newsList,
        ]);
    }
}
