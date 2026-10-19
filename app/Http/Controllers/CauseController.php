<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use Illuminate\View\View;

class CauseController extends Controller
{
    public function index(): View
    {
        return view('causes.index', ['causes' => Cause::approved()->latest()->get()]);
    }

    public function show(string $slug): View
    {
        $cause = Cause::approved()->where('slug', $slug)->firstOrFail();

        $related = Cause::approved()
            ->where('id', '!=', $cause->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('causes.show', ['cause' => $cause, 'related' => $related]);
    }
}