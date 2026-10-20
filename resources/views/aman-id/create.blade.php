@extends('layouts.app')
@section('title', __('Register an Aman ID'))

@section('content')
@php
    $inputClass = 'w-full rounded-2xl border border-gray-200 bg-white px-4 py-3.5 text-sm placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 disabled:bg-gray-100';
    $oldCategories = old('categories', []);
    $oldNeeds = old('needs', []);
@endphp

<section class="bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Register an Aman ID') }}</h1>
        <a href="{{ route('aman-id.index') }}" class="text-blue-600 underline text-sm">{{ __('What is Aman ID?') }}</a>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold mb-1">{{ __('Please fix the following:') }}</p>
                <ul class="list-disc ms-5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('aman-id.store') }}"
              class="mt-8 grid lg:grid-cols-[minmax(0,1fr)_420px] gap-8 items-start">
            @csrf

            <div class="space-y-6">

                {{-- ========== ABOUT BENEFICIARY ========== --}}
                <div class="bg-white rounded-3xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-900">{{ __('About Beneficiary') }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ __('Provide the information of the person you are trying to help.') }}</p>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <input id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('First Name') }}" required class="{{ $inputClass }}">
                        <input id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Last Name') }}" required class="{{ $inputClass }}">

                        <select id="country" name="country" required class="{{ $inputClass }}">
                            <option value="">{{ __('Country') }}</option>
                            <option value="Afghanistan" @selected(old('country') === 'Afghanistan')>{{ __('Afghanistan') }}</option>
                        </select>

                        <select id="province" name="province" required disabled class="{{ $inputClass }}">
                            <option value="">{{ __('Province') }}</option>
                        </select>

                        <input name="district" value="{{ old('district') }}" placeholder="{{ __('District') }}" required class="{{ $inputClass }} sm:col-span-2">

                        <select name="gender" required class="{{ $inputClass }} sm:col-span-2">
                            <option value="">{{ __('Gender') }}</option>
                            @foreach ($genders as $g)
                                <option value="{{ $g }}" @selected(old('gender') === $g)>{{ __($g) }}</option>
                            @endforeach
                        </select>

                        {{-- Phone numbers always read left-to-right --}}
                        <div class="flex sm:col-span-2" dir="ltr">
                            <span class="inline-flex items-center px-4 rounded-l-2xl border border-r-0 border-gray-200 bg-gray-50 text-sm text-gray-600">+93</span>
                            <input name="primary_phone" value="{{ old('primary_phone') }}" placeholder="{{ __('Primary Phone') }}" required
                                   class="{{ $inputClass }} !rounded-l-none">
                        </div>
                        <div class="flex sm:col-span-2" dir="ltr">
                            <span class="inline-flex items-center px-4 rounded-l-2xl border border-r-0 border-gray-200 bg-gray-50 text-sm text-gray-600">+93</span>
                            <input name="secondary_phone" value="{{ old('secondary_phone') }}" placeholder="{{ __('Secondary Phone') }}"
                                   class="{{ $inputClass }} !rounded-l-none">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">{{ __('An active number is required so we can coordinate delivery.') }}</p>
                </div>

                {{-- ========== WHO IS THIS FOR ========== --}}
                <div class="bg-white rounded-3xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-900">{{ __('Who is this Aman ID for?') }}</h2>
                    <div class="mt-4 grid sm:grid-cols-3 gap-3">
                        @foreach ($forWhom as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="registered_for" value="{{ $value }}" class="peer sr-only"
                                       @checked(old('registered_for', 'other') === $value)>
                                <span class="block text-center rounded-2xl border border-gray-200 px-4 py-3 text-sm font-medium
                                             peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    {{ __($label) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- ========== WHAT BEST DESCRIBES THEM ========== --}}
                <div class="bg-white rounded-3xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-900">{{ __('What best describes them?') }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ __('Select all that apply.') }}</p>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($categories as $value => $label)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $value }}" class="peer sr-only"
                                       @checked(in_array($value, $oldCategories))>
                                <span class="inline-flex rounded-xl border border-gray-200 px-4 py-2 text-sm
                                             peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50">
                                    {{ __($label) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- ========== WISHLIST / NEEDS ========== --}}
                <div class="bg-white rounded-3xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-900">{{ __('Wishlist/Needs') }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ __('What do they need?') }}</p>

                    <div class="rounded-2xl border border-gray-200 p-3">
                        <input id="needSearch" type="text" placeholder="{{ __('Select Packages') }}"
                               class="w-full rounded-xl bg-gray-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40">

                        <div class="flex gap-2 overflow-x-auto py-3" id="needFilters">
                            <button type="button" data-group="all"
                                    class="need-filter shrink-0 rounded-lg border px-3 py-1.5 text-xs font-medium border-blue-600 bg-blue-50 text-blue-700">{{ __('All') }}</button>
                            @foreach ($needGroups as $group)
                                <button type="button" data-group="{{ $group }}"
                                        class="need-filter shrink-0 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium">{{ __($group) }}</button>
                            @endforeach
                        </div>

                        <div class="max-h-72 overflow-y-auto space-y-2" id="needList">
                            @foreach ($needs as $slug => [$label, $group])
                                <label class="need-row flex items-center gap-3 rounded-xl border border-gray-200 px-3 py-3 cursor-pointer hover:bg-gray-50"
                                       data-group="{{ $group }}" data-label="{{ mb_strtolower(__($label)) }}">
                                    <input type="checkbox" name="needs[]" value="{{ $slug }}"
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           @checked(in_array($slug, $oldNeeds))>
                                    <span class="text-sm flex-1">{{ __($label) }}</span>
                                    <span class="text-xs text-gray-400">{{ __($group) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Cash need --}}
                    <div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span>
                                <span class="block font-medium text-gray-900">{{ __('Add a cash need') }}</span>
                                <span class="block text-sm text-gray-500">{{ __('Request a specific amount of direct cash.') }}</span>
                            </span>
                            <input type="hidden" name="cash_need" value="0">
                            <input type="checkbox" id="cashToggle" name="cash_need" value="1" class="sr-only peer"
                                   @checked(old('cash_need'))>
                            <span class="relative w-11 h-6 rounded-full bg-gray-300 transition peer-checked:bg-blue-600
                                         after:content-[''] after:absolute after:top-0.5 after:start-0.5 after:w-5 after:h-5
                                         after:rounded-full after:bg-white after:transition
                                         peer-checked:after:translate-x-5 rtl:peer-checked:after:-translate-x-5"></span>
                        </label>
                        <div id="cashBox" class="{{ old('cash_need') ? '' : 'hidden' }} mt-3">
                            <input type="number" min="1" step="1" name="cash_amount" value="{{ old('cash_amount') }}"
                                   placeholder="{{ __('Amount in USD') }}" class="{{ $inputClass }}">
                        </div>
                    </div>
                </div>

                {{-- ========== VISIBILITY ========== --}}
                <div class="bg-white rounded-3xl border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-900">{{ __('Profile visibility') }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ __('What do donors see?') }}</p>

                    <div class="space-y-3">
                        <label class="block cursor-pointer">
                            <input type="radio" name="visibility" value="private" class="peer sr-only" @checked(old('visibility', 'private') === 'private')>
                            <span class="block rounded-2xl border border-gray-200 p-4 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                <span class="font-medium text-gray-900">{{ __('Private') }}</span>
                                <span class="block text-sm text-gray-500">{{ __('Aman ID and wishlist only.') }}</span>
                            </span>
                        </label>
                        <label class="block cursor-pointer">
                            <input type="radio" name="visibility" value="public" class="peer sr-only" @checked(old('visibility') === 'public')>
                            <span class="block rounded-2xl border border-gray-200 p-4 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                <span class="font-medium text-gray-900">{{ __('Public') }}
                                    <span class="ms-1 rounded bg-blue-100 text-blue-700 text-[11px] px-2 py-0.5">{{ __('More visibility') }}</span>
                                </span>
                                <span class="block text-sm text-gray-500">{{ __('Full story and profile information. We verify consent with the family first.') }}</span>
                            </span>
                        </label>
                    </div>
                </div>

                <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                               text-white font-semibold py-4 shadow-lg shadow-blue-600/25 transition">
                    {{ __('Continue') }}
                </button>
            </div>

            {{-- ========== LIVE CARD PREVIEW ========== --}}
            <aside class="lg:sticky lg:top-24">
                @include('partials.aman-card', [
                    'name' => '', 'id' => 'A-0000000',
                    'nameId' => 'cardName', 'idId' => 'cardId',
                ])
            </aside>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinces     = @json($provinces);                                                   // English names (what gets saved)
    const provinceNames = @json(collect($provinces)->mapWithKeys(fn ($p) => [$p => __($p)])); // names shown to the visitor
    const provinceLabel = @json(__('Province'));
    const oldProvince   = @json(old('province'));

    // ---- Country -> Province ----
    const country  = document.getElementById('country');
    const province = document.getElementById('province');

    function fillProvinces() {
        province.innerHTML = '';
        const first = document.createElement('option');
        first.value = '';
        first.textContent = provinceLabel;
        province.appendChild(first);

        if (country.value === 'Afghanistan') {
            provinces.forEach(function (p) {
                const o = document.createElement('option');
                o.value = p;
                o.textContent = provinceNames[p] || p;
                if (p === oldProvince) o.selected = true;
                province.appendChild(o);
            });
            province.disabled = false;
        } else {
            province.disabled = true;
        }
    }
    country.addEventListener('change', fillProvinces);
    fillProvinces();

    // ---- Live card name ----
    const first = document.getElementById('first_name');
    const last  = document.getElementById('last_name');
    const cardName = document.getElementById('cardName');

    function updateName() {
        const l = last.value.trim().slice(0, 2);
        cardName.textContent = (first.value.trim() + ' ' + (l ? l + '***' : '')).trim();
    }
    first.addEventListener('input', updateName);
    last.addEventListener('input', updateName);
    updateName();

    // ---- Needs: search + group filter ----
    const rows    = document.querySelectorAll('.need-row');
    const filters = document.querySelectorAll('.need-filter');
    const search  = document.getElementById('needSearch');
    let activeGroup = 'all';

    function applyNeedFilter() {
        const q = search.value.trim().toLowerCase();
        rows.forEach(function (row) {
            const okGroup = activeGroup === 'all' || row.dataset.group === activeGroup;
            const okText  = !q || row.dataset.label.includes(q);
            row.classList.toggle('hidden', !(okGroup && okText));
        });
    }
    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeGroup = btn.dataset.group;
            filters.forEach(function (b) {
                const on = b === btn;
                b.classList.toggle('border-blue-600', on);
                b.classList.toggle('bg-blue-50', on);
                b.classList.toggle('text-blue-700', on);
                b.classList.toggle('border-gray-200', !on);
            });
            applyNeedFilter();
        });
    });
    search.addEventListener('input', applyNeedFilter);

    // ---- Cash toggle ----
    const toggle = document.getElementById('cashToggle');
    const box    = document.getElementById('cashBox');
    toggle.addEventListener('change', function () {
        box.classList.toggle('hidden', !toggle.checked);
    });
});
</script>
@endpush