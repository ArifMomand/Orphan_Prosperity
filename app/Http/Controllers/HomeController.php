<?php

namespace App\Http\Controllers;

use App\Models\AidPackage;
use App\Models\BlogPost;
use App\Models\Campaign;
use App\Models\Cause;
use App\Models\GalleryImage;
use App\Models\NeedyPerson;
use App\Models\ProjectLocation;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $people = NeedyPerson::active()
            ->with(['aidPackages' => fn ($q) => $q->active()])
            ->ordered()
            ->take(30)
            ->get();

        $packages = AidPackage::active()->ordered()->take(30)->get();

        return view('home', [
            'campaigns' => Campaign::active()->ordered()->take(12)->get(),
            'latestCauses' => Cause::approved()->latest()->take(3)->get(),
            'latestPosts' => BlogPost::latest()->take(3)->get(),
            'galleryImages' => GalleryImage::latest()->take(6)->get(),
            'projectLocations' => ProjectLocation::all(),

            // Support People In Need
            'needyPeople' => $people,
            'peopleCategories' => array_values(array_intersect(NeedyPerson::CATEGORIES, $people->pluck('category')->all())),
            'peopleCities' => $people->pluck('city')->filter()->unique()->sort()->values(),

            // Send Direct Aid
            'aidPackages' => $packages,
            'aidCategories' => array_values(array_intersect(AidPackage::CATEGORIES, $packages->pluck('category')->all())),
        ]);
    }
}