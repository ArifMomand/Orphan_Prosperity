<?php

namespace App\Http\Controllers;

use App\Models\NeedyPerson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeopleController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $city = $request->query('city');

        $people = NeedyPerson::active()
            ->with(['aidPackages' => fn ($q) => $q->active()])
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($city, fn ($q) => $q->where('city', $city))
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $presentCategories = NeedyPerson::active()->distinct()->pluck('category')->all();

        return view('people.index', [
            'people' => $people,
            'categories' => array_values(array_intersect(NeedyPerson::CATEGORIES, $presentCategories)),
            'cities' => NeedyPerson::active()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city'),
            'activeCategory' => $category,
            'activeCity' => $city,
        ]);
    }

    public function show(NeedyPerson $person): View
    {
        abort_unless($person->is_active, 404);

        $person->load(['aidPackages' => fn ($q) => $q->active()]);

        $related = NeedyPerson::active()
            ->where('id', '!=', $person->id)
            ->where('category', $person->category)
            ->ordered()
            ->take(4)
            ->get();

        return view('people.show', compact('person', 'related'));
    }
}
