<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\AidPackage;
use App\Models\NeedyPerson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminNeedyPersonController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.people.index', [
            'people' => NeedyPerson::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.people.form', [
            'person' => new NeedyPerson(['country' => 'Afghanistan', 'country_code' => 'AF', 'is_verified' => true, 'is_active' => true]),
            'packages' => AidPackage::ordered()->get(),
            'selectedPackages' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->storeImage($request, 'image', 'images/people');

        $person = NeedyPerson::create($data);
        $person->aidPackages()->sync($request->input('aid_package_ids', []));

        return redirect()->route('admin.people.index')->with('success', 'Person added.');
    }

    public function edit(NeedyPerson $person): View
    {
        return view('admin.people.form', [
            'person' => $person,
            'packages' => AidPackage::ordered()->get(),
            'selectedPackages' => $person->aidPackages()->pluck('aid_packages.id')->all(),
        ]);
    }

    public function update(Request $request, NeedyPerson $person): RedirectResponse
    {
        $data = $this->validated($request);

        if ($path = $this->storeImage($request, 'image', 'images/people')) {
            $data['image_path'] = $path;
        }

        $person->update($data);
        $person->aidPackages()->sync($request->input('aid_package_ids', []));

        return redirect()->route('admin.people.index')->with('success', 'Person updated.');
    }

    public function destroy(NeedyPerson $person): RedirectResponse
    {
        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'Person removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(NeedyPerson::CATEGORIES)],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'family_size' => ['nullable', 'integer', 'min:1', 'max:50'],
            'story' => ['nullable', 'string', 'max:5000'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'size:2'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
            'aid_package_ids' => ['nullable', 'array'],
            'aid_package_ids.*' => ['exists:aid_packages,id'],
        ]);

        unset($data['image'], $data['aid_package_ids']);

        $data['country_code'] = strtoupper($data['country_code']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_verified'] = $request->boolean('is_verified');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
