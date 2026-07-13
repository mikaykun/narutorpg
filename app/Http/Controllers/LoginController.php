<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function login(): View
    {
        return view('login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $user = $request->user();
            $request->session()->regenerate();

            return redirect()
                ->intended('/Center.php')
                ->withCookie(Cookie::make('c_loged', (string) $user->id, 60 * 24 * 7))
                ->withCookie(Cookie::make('c_pw', $user->pw, 60 * 24 * 7))
                ->withCookie(Cookie::make('c_beta', '1', 60 * 24 * 7));
        }

        return back()->withErrors([
            'name' => 'Die angegebenen Zugangsdaten sind ungültig.',
        ])->onlyInput('name');
    }
}
