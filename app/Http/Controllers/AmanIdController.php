<?php

namespace App\Http\Controllers;

use App\Models\AmanId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AmanIdController extends Controller
{
    // Landing page (with the search box)
    public function index(): View
    {
        return view('aman-id.index', [
            'q'        => '',
            'results'  => collect(),
            'searched' => false,
            'error'    => null,
        ]);
    }

    // Public search: by Aman ID or by name
    public function search(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $results = collect();
        $error = null;

        if ($q === '') {
            $error = 'Please type a name or an Aman ID.';
        }
        // Aman ID: "A-0000003", "a0000003", "0000003" or "3"
        elseif (preg_match('/^a?\s*-?\s*(\d{1,7})$/i', $q, $m)) {
            $results = AmanId::where('status', 'verified')
                ->where('aman_id', AmanId::formatId((int) $m[1]))
                ->get();
        }
        // Name search needs at least 3 characters
        elseif (mb_strlen($q) < 3) {
            $error = 'Please type at least 3 letters of the name, or the full Aman ID.';
        }
        // Name: only profiles whose family chose "Public"
        else {
            $query = AmanId::where('status', 'verified')->where('visibility', 'public');

            foreach (preg_split('/\s+/', $q) as $word) {
                if ($word === '') {
                    continue;
                }

                $like = '%' . addcslashes($word, '%_\\') . '%';

                $query->where(function ($w) use ($like) {
                    $w->where('first_name', 'like', $like)
                      ->orWhere('last_name', 'like', $like);
                });
            }

            $results = $query->orderBy('first_name')->limit(12)->get();
        }

        return view('aman-id.index', [
            'q'        => $q,
            'results'  => $results,
            'searched' => true,
            'error'    => $error,
        ]);
    }

    // Registration form
    public function create(): View
    {
        return view('aman-id.create', [
            'provinces'  => AmanId::PROVINCES,
            'genders'    => AmanId::GENDERS,
            'forWhom'    => AmanId::REGISTERED_FOR,
            'categories' => AmanId::CATEGORIES,
            'needs'      => AmanId::NEEDS,
            'needGroups' => AmanId::NEED_GROUPS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:80'],
            'last_name'       => ['required', 'string', 'max:80'],
            'country'         => ['required', Rule::in(['Afghanistan'])],
            'province'        => ['required', Rule::in(AmanId::PROVINCES)],
            'district'        => ['required', 'string', 'max:120'],
            'gender'          => ['required', Rule::in(AmanId::GENDERS)],
            'primary_phone'   => ['required', 'string', 'regex:/^[0-9\s\-]{7,15}$/'],
            'secondary_phone' => ['nullable', 'string', 'regex:/^[0-9\s\-]{7,15}$/'],
            'registered_for'  => ['required', Rule::in(array_keys(AmanId::REGISTERED_FOR))],
            'categories'      => ['nullable', 'array'],
            'categories.*'    => [Rule::in(array_keys(AmanId::CATEGORIES))],
            'needs'           => ['nullable', 'array'],
            'needs.*'         => [Rule::in(array_keys(AmanId::NEEDS))],
            'cash_need'       => ['nullable', 'boolean'],
            'cash_amount'     => ['nullable', 'required_if:cash_need,1', 'numeric', 'min:1', 'max:100000'],
            'visibility'      => ['required', Rule::in(['private', 'public'])],
        ], [
            'primary_phone.regex' => 'Please enter a valid phone number (digits only).',
        ]);

        $amanId = DB::transaction(function () use ($data, $request) {
            $record = AmanId::create([
                'uuid'            => (string) Str::uuid(),
                'user_id'         => $request->user()?->id,
                'first_name'      => $data['first_name'],
                'last_name'       => $data['last_name'],
                'country'         => $data['country'],
                'province'        => $data['province'],
                'district'        => $data['district'],
                'gender'          => $data['gender'],
                'primary_phone'   => '+93' . ltrim(preg_replace('/\D/', '', $data['primary_phone']), '0'),
                'secondary_phone' => !empty($data['secondary_phone'])
                    ? '+93' . ltrim(preg_replace('/\D/', '', $data['secondary_phone']), '0')
                    : null,
                'registered_for'  => $data['registered_for'],
                'categories'      => $data['categories'] ?? [],
                'needs'           => $data['needs'] ?? [],
                'cash_need'       => $request->boolean('cash_need'),
                'cash_amount'     => $request->boolean('cash_need') ? $data['cash_amount'] : null,
                'visibility'      => $data['visibility'],
                'status'          => 'pending', // admin verifies before it goes live
            ]);

            $record->update(['aman_id' => AmanId::formatId($record->id)]);

            return $record;
        });

        // "just_registered" makes the next page show the "Aman ID created" message once
        return redirect()
            ->route('aman-id.show', $amanId)
            ->with('just_registered', true);
    }

    // Public page opened by the QR code (and the "created" page right after registering)
    public function show(AmanId $amanId): View
    {
        return view('aman-id.show', [
            'amanId'         => $amanId,
            'justRegistered' => session('just_registered', false),
            'isPublic'       => $amanId->visibility === 'public',
        ]);
    }
}