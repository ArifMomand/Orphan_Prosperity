<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCampaignController extends Controller
{
    public function index(): View
    {
        return view('admin.campaigns.index', [
            'campaigns'   => Campaign::ordered()->paginate(20),
            'activeCount' => Campaign::active()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Friendly names like "picture in Campaign 2" instead of "items.1.image"
        $attributes = [];
        $keys = array_unique(array_merge(
            array_keys($request->input('items', [])),
            array_keys($request->file('items', []))
        ));
        $n = 1;
        foreach ($keys as $key) {
            $attributes["items.$key.image"]     = "picture in Campaign $n";
            $attributes["items.$key.video_url"] = "YouTube link in Campaign $n";
            $attributes["items.$key.title"]     = "title in Campaign $n";
            $n++;
        }

        $request->validate([
            'items'               => ['required', 'array', 'min:1', 'max:10'],
            'items.*.image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'items.*.video_url'   => ['required', 'string', 'max:500', $this->youtubeRule()],
            'items.*.title'       => ['nullable', 'string', 'max:120'],
        ], [], $attributes);

        $count = 0;

        foreach ($request->input('items') as $key => $item) {
            $file = $request->file("items.$key.image");

            Campaign::create([
                'title'      => $item['title'] ?? null,
                'image_path' => $this->saveImage($file),
                'video_url'  => trim($item['video_url']),
                'is_active'  => true,
                'sort_order' => 0,
            ]);

            $count++;
        }

        return redirect()->route('admin.campaigns.index')
            ->with('success', $count . ' campaign' . ($count === 1 ? '' : 's') . ' added. They are now live on the home page.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.edit', ['campaign' => $campaign]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'title'      => ['nullable', 'string', 'max:120'],
            'video_url'  => ['required', 'string', 'max:500', $this->youtubeRule()],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'between:-999,999'],
        ]);

        if ($request->hasFile('image')) {
            File::delete(public_path($campaign->image_path));
            $campaign->image_path = $this->saveImage($request->file('image'));
        }

        $campaign->title      = $data['title'] ?? null;
        $campaign->video_url  = trim($data['video_url']);
        $campaign->sort_order = (int) ($data['sort_order'] ?? 0);
        $campaign->is_active  = $request->boolean('is_active');
        $campaign->save();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function toggle(Campaign $campaign): RedirectResponse
    {
        $campaign->update(['is_active' => ! $campaign->is_active]);

        return back()->with('success', $campaign->is_active
            ? 'Campaign is now showing on the home page.'
            : 'Campaign is hidden from the home page.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        File::delete(public_path($campaign->image_path));
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted.');
    }

    // ---------------------------------------------------------------

    private function youtubeRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {
            if (! Campaign::extractYoutubeId($value)) {
                $fail('The :attribute is not a valid YouTube link.');
            }
        };
    }

    private function saveImage(UploadedFile $file): string
    {
        $name = Str::uuid() . '.' . $file->extension();
        $file->move(public_path('uploads/campaigns'), $name);

        return 'uploads/campaigns/' . $name;
    }
}