<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\AidPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAidPackageController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.aid-packages.index', [
            'packages' => AidPackage::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.aid-packages.form', [
            'package' => new AidPackage(['currency' => 'USD', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->storeImage($request, 'image', 'images/aid');

        AidPackage::create($data);

        return redirect()->route('admin.aid-packages.index')->with('success', 'Package added.');
    }

    public function edit(AidPackage $aidPackage): View
    {
        return view('admin.aid-packages.form', ['package' => $aidPackage]);
    }

    public function update(Request $request, AidPackage $aidPackage): RedirectResponse
    {
        $data = $this->validated($request);

        if ($path = $this->storeImage($request, 'image', 'images/aid')) {
            $data['image_path'] = $path;
        }

        $aidPackage->update($data);

        return redirect()->route('admin.aid-packages.index')->with('success', 'Package updated.');
    }

    public function destroy(AidPackage $aidPackage): RedirectResponse
    {
        $aidPackage->delete();

        return redirect()->route('admin.aid-packages.index')->with('success', 'Package removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(AidPackage::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', Rule::in(array_keys(AidPackage::CURRENCIES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'includes' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['image']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
