<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PioneerPortalController extends Controller
{
    public const CATEGORIES = ['Education', 'Health', 'Food', 'Shelter', 'Clothing', 'Other'];

    public function dashboard()
    {
        $pioneer = Auth::guard('pioneer')->user();

        return view('pioneer-portal.dashboard', [
            'pioneer'     => $pioneer,
            'application' => $pioneer->application(),
            'causes'      => $pioneer->causes()->latest()->get(),
            'categories'  => self::CATEGORIES,
        ]);
    }

    public function storeCause(Request $request): RedirectResponse
    {
        $pioneer = Auth::guard('pioneer')->user();

        // Server-side check: the admin must have verified this Pioneer.
        if (! $pioneer->isVerified()) {
            return back()->withErrors(['title' => 'Your account has not been verified by the admin yet.']);
        }

        $data = $request->validate([
            'orphan_name' => ['required', 'string', 'max:255'],
            'title'       => ['required', 'string', 'max:255'],
            'category'    => ['required', 'in:' . implode(',', self::CATEGORIES)],
            'location'    => ['required', 'string', 'max:255'],
            'summary'     => ['required', 'string', 'max:300'],
            'story'       => ['required', 'string', 'max:5000'],
            'goal'        => ['required', 'integer', 'min:1', 'max:10000000'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // Save the photo inside public/uploads/causes (same style as the admin panel)
        $file = $request->file('image');
        $name = Str::uuid() . '.' . $file->extension();
        $file->move(public_path('uploads/causes'), $name);

        $cause = new Cause([
            'orphan_name' => $data['orphan_name'],
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']) . '-' . Str::lower(Str::random(5)),
            'category'    => $data['category'],
            'location'    => $data['location'],
            'summary'     => $data['summary'],
            'story'       => $data['story'],
            'goal'        => $data['goal'],
            'raised'      => 0,
            'donors'      => 0,
            'image_path'  => 'uploads/causes/' . $name,
        ]);

        // Set directly so they can never be dropped by $fillable
        $cause->pioneer_id = $pioneer->id;
        $cause->status     = 'pending';   // always waits for admin approval
        $cause->save();

        return back()->with('success', 'Thanks! Your cause was submitted and is waiting for admin approval.');
    }
}