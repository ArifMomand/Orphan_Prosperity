<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Cause;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\PioneerApplication;
use App\Models\ProjectLocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\AmanId;
use App\Models\AmanDonation;
use App\Models\CauseDonation;

class AdminController extends Controller
{
    public function dashboard(): View
{
    $monthStart = now()->startOfMonth();

    // ---- Last 6 months chart (causes vs Aman IDs) ----
    $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
    $since  = $months->first()->toDateString();

    $causeByMonth = CauseDonation::selectRaw("DATE_FORMAT(donated_at, '%Y-%m') as m, SUM(amount) as total")
        ->whereDate('donated_at', '>=', $since)->groupBy('m')->pluck('total', 'm');

    $amanByMonth = AmanDonation::selectRaw("DATE_FORMAT(donated_at, '%Y-%m') as m, SUM(amount) as total")
        ->whereDate('donated_at', '>=', $since)->groupBy('m')->pluck('total', 'm');

    $chart = $months->map(fn ($d) => [
        'label' => $d->format('M'),
        'cause' => (float) ($causeByMonth[$d->format('Y-m')] ?? 0),
        'aman'  => (float) ($amanByMonth[$d->format('Y-m')] ?? 0),
    ]);

    // ---- Recent donations from both systems ----
    $recentCause = CauseDonation::with('cause')->latest('donated_at')->latest('id')->limit(8)->get()
        ->map(fn ($d) => (object) [
            'date'   => $d->donated_at,
            'donor'  => $d->donor_name ?: 'Anonymous',
            'amount' => $d->amount,
            'for'    => $d->cause?->title ?? '—',
            'type'   => 'Cause',
            'url'    => $d->cause ? route('admin.causes.donations', $d->cause) : null,
        ]);

    $recentAman = AmanDonation::with('beneficiary')->latest('donated_at')->latest('id')->limit(8)->get()
        ->map(fn ($d) => (object) [
            'date'   => $d->donated_at,
            'donor'  => $d->donor_name ?: 'Anonymous',
            'amount' => $d->amount,
            'for'    => $d->beneficiary?->aman_id ?? '—',
            'type'   => 'Aman ID',
            'url'    => $d->beneficiary ? route('admin.aman-ids.show', $d->beneficiary) : null,
        ]);

    $recent = $recentCause->concat($recentAman)
        ->sortByDesc(fn ($r) => $r->date->timestamp)
        ->take(8)
        ->values();

    return view('admin.dashboard', [
        // money
        'totalRecorded' => (float) CauseDonation::sum('amount') + (float) AmanDonation::sum('amount'),
        'monthRecorded' => (float) CauseDonation::whereDate('donated_at', '>=', $monthStart)->sum('amount')
                         + (float) AmanDonation::whereDate('donated_at', '>=', $monthStart)->sum('amount'),
        'chart'         => $chart,
        'recent'        => $recent,

        // counts
        'causesTotal'      => Cause::count(),
        'causesApproved'   => Cause::where('status', 'approved')->count(),
        'causesPending'    => Cause::where('status', 'pending')->count(),
        'amanVerified'     => AmanId::where('status', 'verified')->count(),
        'amanPending'      => AmanId::where('status', 'pending')->count(),
        'pioneersVerified' => PioneerApplication::where('status', 'verified')->count(),
        'pioneersPending'  => PioneerApplication::where('status', 'pending')->count(),
        'messagesUnread'   => ContactMessage::where('status', 'unread')->count(),

        // lists
        'pendingAman'     => AmanId::where('status', 'pending')->latest()->limit(5)->get(),
        'pendingCauses'   => Cause::where('status', 'pending')->latest()->limit(5)->get(),
        'pendingPioneers' => PioneerApplication::where('status', 'pending')->latest()->limit(5)->get(),
        'latestMessages'  => ContactMessage::latest()->limit(5)->get(),
        'topCauses'       => Cause::where('status', 'approved')->latest()->limit(5)->get(),
    ]);
}

    // ---------------- Causes ----------------

    public function causesIndex(): View
    {
        return view('admin.causes.index', ['causes' => Cause::latest()->get()]);
    }

    public function causesCreate(): View
    {
        return view('admin.causes.form', ['cause' => new Cause()]);
    }

    public function causesStore(Request $request): RedirectResponse
    {
        $data = $this->validateCause($request);
        $data['image_path'] = $this->handleUploadedImage($request, 'image', 'images/causes');

        $cause = Cause::create($data);
        $cause->recalculateTotals();

        return redirect()->route('admin.causes.index')->with('success', 'Cause created.');
    }

    public function causesEdit(Cause $cause): View
    {
        return view('admin.causes.form', ['cause' => $cause]);
    }

    public function causesUpdate(Request $request, Cause $cause): RedirectResponse
    {
        $data = $this->validateCause($request);

        if ($path = $this->handleUploadedImage($request, 'image', 'images/causes')) {
            $data['image_path'] = $path;
        }

        $cause->update($data);
        $cause->recalculateTotals();

        return redirect()->route('admin.causes.index')->with('success', 'Cause updated.');
    }

    public function causesDestroy(Cause $cause): RedirectResponse
    {
        $cause->delete();

        return redirect()->route('admin.causes.index')->with('success', 'Cause deleted.');
    }

    private function validateCause(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'goal' => ['required', 'integer', 'min:1'],
            'opening_raised' => ['nullable', 'integer', 'min:0'],
            'opening_donors' => ['nullable', 'integer', 'min:0'],
            'summary' => ['required', 'string', 'max:500'],
            'story' => ['required', 'string'],
        ]);

        $data['opening_raised'] = $data['opening_raised'] ?? 0;
        $data['opening_donors'] = $data['opening_donors'] ?? 0;

        return $data;
    }

    public function causesApprove(Cause $cause)
    {
        $cause->update(['status' => 'approved']);

        return back()->with('success', 'Cause approved and now live on the Causes page.');
    }

    public function causesReject(Cause $cause)
    {
        $cause->update(['status' => 'rejected']);

        return back()->with('success', 'Cause rejected. It will not appear on the public site.');
    }

    // ---------------- Contact messages ----------------

    public function messagesIndex(): View
    {
        return view('admin.messages.index', ['messages' => ContactMessage::latest()->get()]);
    }

    public function messagesShow(ContactMessage $contactMessage): View
    {
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.messages.show', ['contactMessage' => $contactMessage]);
    }

    // ---------------- Pioneer applications ----------------

    public function pioneersIndex(): View
    {
        return view('admin.pioneers.index', ['applications' => PioneerApplication::latest()->get()]);
    }

    public function pioneersShow(PioneerApplication $pioneerApplication): View
    {
        return view('admin.pioneers.show', ['application' => $pioneerApplication]);
    }

    public function pioneersUpdateStatus(Request $request, PioneerApplication $pioneerApplication): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:pending,verified,rejected']]);

        $pioneerApplication->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }

    // ---------------- Homepage video ----------------

    public function videoForm(): View
    {
        return view('admin.video');
    }

    public function videoUpload(Request $request): RedirectResponse
    {
        $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/mp4', 'max:102400'],
        ]);

        $request->file('video')->move(public_path('videos'), 'intro.mp4');

        return back()->with('success', 'Homepage video updated.');
    }

    // ---------------- Blog posts ----------------

    public function blogIndex(): View
    {
        return view('admin.blog.index', ['posts' => BlogPost::latest()->get()]);
    }

    public function blogCreate(): View
    {
        return view('admin.blog.form', ['post' => new BlogPost()]);
    }

    public function blogStore(Request $request): RedirectResponse
    {
        $data = $this->validateBlogPost($request);
        $data['image_path'] = $this->handleUploadedImage($request, 'image', 'images/blog');

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post created.');
    }

    public function blogEdit(BlogPost $post): View
    {
        return view('admin.blog.form', ['post' => $post]);
    }

    public function blogUpdate(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validateBlogPost($request);

        if ($path = $this->handleUploadedImage($request, 'image', 'images/blog')) {
            $data['image_path'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function blogDestroy(BlogPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }

    private function validateBlogPost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    // ---------------- Gallery ----------------

    public function galleryIndex(): View
    {
        return view('admin.gallery.index', ['images' => GalleryImage::latest()->get()]);
    }

    public function galleryStore(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        GalleryImage::create([
            'image_path' => $this->handleUploadedImage($request, 'image', 'images/gallery'),
            'caption' => $request->caption,
        ]);

        return back()->with('success', 'Image added to gallery.');
    }

    public function galleryDestroy(GalleryImage $galleryImage): RedirectResponse
    {
        $galleryImage->delete();

        return back()->with('success', 'Image removed.');
    }

    // ---------------- Project locations (Afghanistan map) ----------------

    public function locationsIndex(): View
    {
        return view('admin.locations.index', ['locations' => ProjectLocation::latest()->get()]);
    }

    public function locationsCreate(): View
    {
        return view('admin.locations.form', ['location' => new ProjectLocation()]);
    }

    public function locationsStore(Request $request): RedirectResponse
    {
        ProjectLocation::create($this->validateLocation($request));

        return redirect()->route('admin.locations.index')->with('success', 'Location added.');
    }

    public function locationsEdit(ProjectLocation $location): View
    {
        return view('admin.locations.form', ['location' => $location]);
    }

    public function locationsUpdate(Request $request, ProjectLocation $location): RedirectResponse
    {
        $location->update($this->validateLocation($request));

        return redirect()->route('admin.locations.index')->with('success', 'Location updated.');
    }

    public function locationsDestroy(ProjectLocation $location): RedirectResponse
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location removed.');
    }

    private function validateLocation(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'x_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'y_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
    }

    // ---------------- Shared helper ----------------

    private function handleUploadedImage(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $folder . '/' . $filename;
    }
}