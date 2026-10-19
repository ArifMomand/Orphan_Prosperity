<?php

namespace App\Http\Controllers;

use App\Models\Pioneer;
use App\Models\PioneerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class PioneerController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('pioneer');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name'               => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255', 'unique:pioneers,email'],
            'phone'                   => ['nullable', 'string', 'max:30'],
            'city'                    => ['required', 'string', 'max:255'],
            'district'                => ['nullable', 'string', 'max:255'],
            'experience'              => ['required', 'in:none,some,experienced'],
            'heard_from'              => ['nullable', 'string', 'max:255'],
            'availability'            => ['nullable', 'string'], // comma-separated from the hidden input
            'skills'                  => ['nullable', 'string'], // comma-separated from the hidden input
            'motivation'              => ['required', 'string', 'max:2000'],
            'agree_background_check'  => ['required', 'accepted'],
            'username'                => ['required', 'string', 'alpha_dash', 'min:4', 'max:30', 'unique:pioneers,username'],
            'password'                => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.unique'    => 'An account with this email already exists. Please log in instead.',
            'username.unique' => 'That username is already taken. Please choose another one.',
        ]);

        $pioneer = DB::transaction(function () use ($validated) {
            $pioneer = Pioneer::create([
                'name'     => $validated['full_name'],
                'username' => $validated['username'],
                'email'    => $validated['email'],
                'password' => $validated['password'], // hashed automatically by the model
            ]);

            PioneerApplication::create([
                'pioneer_id'             => $pioneer->id,
                'full_name'              => $validated['full_name'],
                'email'                  => $validated['email'],
                'phone'                  => $validated['phone'] ?? null,
                'city'                   => $validated['city'],
                'district'               => $validated['district'] ?? null,
                'experience'             => $validated['experience'],
                'heard_from'             => $validated['heard_from'] ?? null,
                'availability'           => $this->toArray($validated['availability'] ?? ''),
                'skills'                 => $this->toArray($validated['skills'] ?? ''),
                'motivation'             => $validated['motivation'],
                'agree_background_check' => true,
                'status'                 => 'pending',
            ]);

            return $pioneer;
        });

        Auth::guard('pioneer')->login($pioneer);
        $request->session()->regenerate();

       return redirect()->route('pioneer.dashboard')
    ->with('success', __('Application received! You can log in any time with your username. You can add causes once the admin verifies you.'));
    }

    private function toArray(string $commaSeparated): array
    {
        return array_values(array_filter(explode(',', $commaSeparated)));
    }
}