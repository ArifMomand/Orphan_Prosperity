<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\AidController;
use App\Http\Controllers\AmanIdController;
use App\Http\Controllers\AdminAmanIdController;
use App\Http\Controllers\AdminCauseDonationController;
use App\Http\Controllers\PioneerController;
use App\Http\Controllers\PioneerAuthController;
use App\Http\Controllers\PioneerPortalController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNeedyPersonController;
use App\Http\Controllers\AdminAidPackageController;
use App\Http\Middleware\PioneerAuth;
use App\Http\Controllers\AdminAmanDonationController;
use App\Http\Controllers\AdminCampaignController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/donate', function (\Illuminate\Http\Request $request) {
    $amanId = null;
    $cause  = null;

    if ($request->filled('aman_id')) {
        $amanId = \App\Models\AmanId::where('aman_id', $request->query('aman_id'))
            ->where('status', 'verified')
            ->first();
    }

    if ($request->filled('cause')) {
        $cause = \App\Models\Cause::approved()->where('slug', $request->query('cause'))->first();
    }

    return view('donation', [
        'amanId' => $amanId,
        'need'   => $request->query('need'),
        'cause'  => $cause,
    ]);
});

// ---------------- Language switching ----------------

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(array_key_exists($locale, config('locales.supported')), 404);

    session(['locale' => $locale]);

    return back()->withCookie(cookie()->forever('locale', $locale));
})->name('lang.switch');


// ---------------- Pioneer: volunteer application ----------------

Route::get('/pioneer', [PioneerController::class, 'show']);
Route::post('/pioneer/apply', [PioneerController::class, 'store']);

// ---------------- Pioneer: login, register and portal ----------------

Route::get('/pioneer/register', fn () => redirect('/pioneer#apply'))->name('pioneer.register');
Route::get('/pioneer/login', [PioneerAuthController::class, 'showLogin'])->name('pioneer.login');
Route::post('/pioneer/login', [PioneerAuthController::class, 'login']);

Route::middleware(PioneerAuth::class)->group(function () {
    Route::get('/pioneer/dashboard', [PioneerPortalController::class, 'dashboard'])->name('pioneer.dashboard');
    Route::post('/pioneer/causes', [PioneerPortalController::class, 'storeCause'])->name('pioneer.causes.store');
    Route::post('/pioneer/logout', [PioneerAuthController::class, 'logout'])->name('pioneer.logout');
});

// ---------------- Public pages ----------------

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', [ContactController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/causes', [CauseController::class, 'index']);
Route::get('/causes/{slug}', [CauseController::class, 'show']);


// ---------------- Aman ID registration ----------------
Route::prefix('aman-id')->name('aman-id.')->group(function () {
    Route::get('/',          [AmanIdController::class, 'index'])->name('index');
    Route::get('/search',    [AmanIdController::class, 'search'])->middleware('throttle:20,1')->name('search');
    Route::get('/register',  [AmanIdController::class, 'create'])->name('create');
    Route::post('/register', [AmanIdController::class, 'store'])->name('store');
    Route::get('/{amanId}',  [AmanIdController::class, 'show'])->name('show');
});

// People in need and direct aid: "View all" pages and full detail pages
Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
Route::get('/people/{person}', [PeopleController::class, 'show'])->name('people.show');
Route::get('/aid', [AidController::class, 'index'])->name('aid.index');
Route::get('/aid/{aidPackage}', [AidController::class, 'show'])->name('aid.show');

// ---------------- Admin ----------------

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/causes', [AdminController::class, 'causesIndex'])->name('causes.index');
    Route::get('/causes/create', [AdminController::class, 'causesCreate'])->name('causes.create');
    Route::post('/causes', [AdminController::class, 'causesStore'])->name('causes.store');
    Route::get('/causes/{cause}/edit', [AdminController::class, 'causesEdit'])->name('causes.edit');
    Route::put('/causes/{cause}', [AdminController::class, 'causesUpdate'])->name('causes.update');
    Route::delete('/causes/{cause}', [AdminController::class, 'causesDestroy'])->name('causes.destroy');
    Route::post('/causes/{cause}/approve', [AdminController::class, 'causesApprove'])->name('causes.approve');
Route::post('/causes/{cause}/reject', [AdminController::class, 'causesReject'])->name('causes.reject');

    Route::get('/messages', [AdminController::class, 'messagesIndex'])->name('messages.index');
    Route::get('/messages/{contactMessage}', [AdminController::class, 'messagesShow'])->name('messages.show');

    Route::get('/pioneers', [AdminController::class, 'pioneersIndex'])->name('pioneers.index');
    Route::get('/pioneers/{pioneerApplication}', [AdminController::class, 'pioneersShow'])->name('pioneers.show');
    Route::post('/pioneers/{pioneerApplication}/status', [AdminController::class, 'pioneersUpdateStatus'])->name('pioneers.status');

    Route::get('/blog', [AdminController::class, 'blogIndex'])->name('blog.index');
    Route::get('/blog/create', [AdminController::class, 'blogCreate'])->name('blog.create');
    Route::post('/blog', [AdminController::class, 'blogStore'])->name('blog.store');
    Route::get('/blog/{post}/edit', [AdminController::class, 'blogEdit'])->name('blog.edit');
    Route::put('/blog/{post}', [AdminController::class, 'blogUpdate'])->name('blog.update');
    Route::delete('/blog/{post}', [AdminController::class, 'blogDestroy'])->name('blog.destroy');

    Route::get('/gallery', [AdminController::class, 'galleryIndex'])->name('gallery.index');
    Route::post('/gallery', [AdminController::class, 'galleryStore'])->name('gallery.store');
    Route::delete('/gallery/{galleryImage}', [AdminController::class, 'galleryDestroy'])->name('gallery.destroy');

    Route::get('/locations', [AdminController::class, 'locationsIndex'])->name('locations.index');
    Route::get('/locations/create', [AdminController::class, 'locationsCreate'])->name('locations.create');
    Route::post('/locations', [AdminController::class, 'locationsStore'])->name('locations.store');
    Route::get('/locations/{location}/edit', [AdminController::class, 'locationsEdit'])->name('locations.edit');
    Route::put('/locations/{location}', [AdminController::class, 'locationsUpdate'])->name('locations.update');
    Route::delete('/locations/{location}', [AdminController::class, 'locationsDestroy'])->name('locations.destroy');

    Route::get('/video', [AdminController::class, 'videoForm'])->name('video.form');
    Route::post('/video', [AdminController::class, 'videoUpload'])->name('video.upload');



   // campaign routes (admin.campaigns.*)
Route::post('/campaigns/{campaign}/toggle', [AdminCampaignController::class, 'toggle'])->name('campaigns.toggle');
Route::resource('campaigns', AdminCampaignController::class)->except('show');
    // People in need (admin.people.*) and direct aid packages (admin.aid-packages.*)
    Route::resource('people', AdminNeedyPersonController::class)->except('show');
    Route::resource('aid-packages', AdminAidPackageController::class)->except('show');

         // Aman ID review (admin.aman-ids.*)
    Route::get('/aman-ids', [AdminAmanIdController::class, 'index'])->name('aman-ids.index');
    Route::get('/aman-ids/{amanId}', [AdminAmanIdController::class, 'show'])->name('aman-ids.show');
    Route::post('/aman-ids/{amanId}/approve', [AdminAmanIdController::class, 'approve'])->name('aman-ids.approve');
    Route::post('/aman-ids/{amanId}/reject', [AdminAmanIdController::class, 'reject'])->name('aman-ids.reject');
    Route::get('/aman-ids/{amanId}/card', [AdminAmanIdController::class, 'card'])->name('aman-ids.card');
        // Aman ID donations (admin.aman-donations.*)
    Route::get('/aman-donations', [AdminAmanDonationController::class, 'index'])->name('aman-donations.index');
    Route::get('/aman-donations/report', [AdminAmanDonationController::class, 'report'])->name('aman-donations.report');
    Route::get('/aman-donations/export', [AdminAmanDonationController::class, 'export'])->name('aman-donations.export');
    Route::post('/aman-ids/{amanId}/donations', [AdminAmanDonationController::class, 'store'])->name('aman-donations.store');
    Route::delete('/aman-donations/{donation}', [AdminAmanDonationController::class, 'destroy'])->name('aman-donations.destroy');


        // Cause donations (admin.cause-donations.*)
    Route::get('/cause-donations', [AdminCauseDonationController::class, 'index'])->name('cause-donations.index');
    Route::get('/cause-donations/report', [AdminCauseDonationController::class, 'report'])->name('cause-donations.report');
    Route::get('/cause-donations/export', [AdminCauseDonationController::class, 'export'])->name('cause-donations.export');
    Route::delete('/cause-donations/{donation}', [AdminCauseDonationController::class, 'destroy'])->name('cause-donations.destroy');
    Route::get('/causes/{cause}/donations', [AdminCauseDonationController::class, 'forCause'])->name('causes.donations');
    Route::post('/causes/{cause}/donations', [AdminCauseDonationController::class, 'store'])->name('causes.donations.store');
});



