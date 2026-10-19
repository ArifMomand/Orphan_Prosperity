<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PioneerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('pioneer')->check()) {
            return redirect()->route('pioneer.dashboard');
        }

        return view('pioneer-portal.login');
    }

    public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required'],
    ]);

    if (Auth::guard('pioneer')->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Ignore any remembered "intended" page (e.g. /admin) and always go to the Amin dashboard
        $request->session()->forget('url.intended');

        return redirect()->route('pioneer.dashboard');
    }

    return back()
        ->withErrors(['username' => 'Wrong username or password.'])
        ->onlyInput('username');
}

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('pioneer')->logout();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}