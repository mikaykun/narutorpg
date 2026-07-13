<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CountryInformation;
use App\Models\GameUpdate;
use App\Models\Gedenken;
use App\Models\Killing;
use App\Models\OnlineUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NarutoRPG\Types\Villages;

final class GameDataController extends Controller
{
    public function weather(): View
    {
        $result = CountryInformation::all();

        return view('game_data.weather', [
            'weatherData' => $result,
        ]);
    }

    public function deletedUsers(Request $request): View
    {
        $maxid = max(0, $request->integer('maxid'));
        $users = Killing::query()
            ->select(['Text'])
            ->orderByDesc('id')
            ->offset($maxid)
            ->limit(50)
            ->get();

        return view('game_data.deleted', [
            'maxid' => $maxid,
            'users' => $users,
        ]);
    }

    public function deceasedNinja(): View
    {
        $gedenken = Gedenken::query()
            ->select(['name', 'Land', 'Missing', 'Datum'])
            ->orderByDesc('id')
            ->get()
            ->groupBy('Land');

        $lands = [];

        foreach (Villages::all() as $name) {
            $lands[$name] = $gedenken->get($name, collect());
        }

        return view('game_data.index', [
            'lands' => $lands,
        ]);
    }

    public function gameUpdates(Request $request): View|RedirectResponse
    {
        $currentUser = nrpg_get_current_user();
        $currentCharacter = nrpg_get_current_character();
        $canManageUpdates = $currentUser->admin === true || in_array((int) ($currentUser->CoAdmin ?? 0), [3, 4], true);

        if ($canManageUpdates && $request->isMethod('POST')) {
            if ($request->boolean('neu')) {
                $content = trim((string) $request->input('Neuerung', ''));

                if ($content !== '') {
                    GameUpdate::query()->create([
                        'Text' => $content,
                        'Datum' => date('d.m.Y <br> H:i'),
                        'time' => time(),
                    ]);
                }

                return redirect()->route('app_game_updates');
            }

            if ($request->boolean('edit')) {
                $id = $request->integer('id');
                $content = trim((string) $request->input('Neuerung', ''));

                if ($id > 0 && $content !== '') {
                    GameUpdate::query()
                        ->whereKey($id)
                        ->update(['Text' => $content]);
                }

                return redirect()->route('app_game_updates');
            }

            if ($request->boolean('delete')) {
                $id = $request->integer('id');

                if ($id > 0) {
                    GameUpdate::query()
                        ->whereKey($id)
                        ->delete();
                }

                return redirect()->route('app_game_updates');
            }
        }

        $editMode = $canManageUpdates && $request->boolean('editMode');
        $editUpdate = null;

        if ($editMode) {
            $editId = $request->integer('id');

            if ($editId > 0) {
                $editUpdate = GameUpdate::query()->find($editId);
            }
        }

        if (! $editMode && $currentCharacter->id !== null) {
            DB::table('user')->where('id', $currentCharacter->id)->update([
                'Neuerungen' => time(),
            ]);
        }

        $page = max(1, $request->integer('Seiteschau', 1));
        $perPage = 30;
        $totalUpdates = GameUpdate::query()->count();
        $totalPages = max(1, (int) ceil($totalUpdates / $perPage));
        $page = min($page, $totalPages);

        $gameUpdates = GameUpdate::query()
            ->orderByDesc('id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return view('game_data.neuerungen', [
            'gameUpdates' => $gameUpdates,
            'canManageUpdates' => $canManageUpdates,
            'editMode' => $editMode,
            'editUpdate' => $editUpdate,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function onlineUsers(): View
    {
        $guests = (int) OnlineUser::query()
            ->where('name', 'Gast')
            ->count();

        $doerfer = ['Konoha', 'Kusa', 'Iwa', 'Ame', 'Suna', 'Taki', 'Kumo', 'Landlos'];
        $usersByVillage = [];

        foreach ($doerfer as $dorf) {
            $users = OnlineUser::query()
                ->select(['name'])
                ->where('Land', $dorf)
                ->orderBy('name')
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            $usersByVillage[$dorf] = $users;
        }

        return view('game_data.online', [
            'guests' => $guests,
            'usersByVillage' => $usersByVillage,
        ]);
    }
}
