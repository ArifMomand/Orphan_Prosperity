<?php

namespace App\Http\Controllers;

use App\Models\AidPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AidController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $packages = AidPackage::active()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $presentCategories = AidPackage::active()->distinct()->pluck('category')->all();

        return view('aid.index', [
            'packages' => $packages,
            'categories' => array_values(array_intersect(AidPackage::CATEGORIES, $presentCategories)),
            'activeCategory' => $category,
        ]);
    }

    public function show(AidPackage $aidPackage): View
    {
        abort_unless($aidPackage->is_active, 404);

        $people = $aidPackage->people()->active()->ordered()->take(8)->get();

        $related = AidPackage::active()
            ->where('id', '!=', $aidPackage->id)
            ->where('category', $aidPackage->category)
            ->ordered()
            ->take(4)
            ->get();

        return view('aid.show', [
            'package' => $aidPackage,
            'people' => $people,
            'related' => $related,
        ]);
    }
}
