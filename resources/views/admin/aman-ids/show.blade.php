@extends('admin.layout')
@section('title', 'Aman ID ' . $item->aman_id)

@section('content')
@php
    $badge = [
        'pending'  => 'bg-amber-100 text-amber-700',
        'verified' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];
    $row = 'flex justify-between gap-4 py-3 border-b border-gray-100 text-sm';
@endphp

<a href="{{ route('admin.aman-ids.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Aman IDs</a>

<div class="mt-4 grid lg:grid-cols-3 gap-6">

    {{-- ========== DETAILS ========== --}}
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-lg font-bold text-gray-900">{{ $item->full_name }}</h2>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge[$item->status] }}">{{ ucfirst($item->status) }}</span>
            </div>

            <div class="{{ $row }}"><span class="text-gray-500">Aman ID</span><span class="font-mono font-medium">{{ $item->aman_id }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Gender</span><span>{{ $item->gender }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Country</span><span>{{ $item->country }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Province</span><span>{{ $item->province }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">District</span><span>{{ $item->district }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Primary phone</span><span>{{ $item->primary_phone }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Secondary phone</span><span>{{ $item->secondary_phone ?: '—' }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Registered for</span><span>{{ \App\Models\AmanId::REGISTERED_FOR[$item->registered_for] ?? $item->registered_for }}</span></div>
            <div class="{{ $row }}"><span class="text-gray-500">Profile visibility</span><span class="capitalize">{{ $item->visibility }}</span></div>
            <div class="{{ $row }} !border-b-0"><span class="text-gray-500">Registered on</span><span>{{ $item->created_at->format('M j, Y H:i') }}</span></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Situation</h3>
            <div class="flex flex-wrap gap-2">
                @forelse ($item->categories ?? [] as $cat)
                    <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm">{{ \App\Models\AmanId::CATEGORIES[$cat] ?? $cat }}</span>
                @empty
                    <span class="text-sm text-gray-400">None selected.</span>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Wishlist / needs</h3>
            <ul class="space-y-2 text-sm">
                @forelse ($item->needs ?? [] as $slug)
                    <li class="flex justify-between rounded-lg border border-gray-100 px-3 py-2">
                        <span>{{ \App\Models\AmanId::NEEDS[$slug][0] ?? $slug }}</span>
                        <span class="text-gray-400">{{ \App\Models\AmanId::NEEDS[$slug][1] ?? '' }}</span>
                    </li>
                @empty
                    <li class="text-gray-400">No packages selected.</li>
                @endforelse
            </ul>

            {{-- Cash need + progress (admin version) --}}
            @if ($item->cash_need)
                @php
                    $cashRaised = (float) $item->donations()->where('need', 'cash')->sum('amount');
                    $cashPct = $item->cash_amount > 0 ? min(100, round($cashRaised / $item->cash_amount * 100)) : 0;
                @endphp
                <div class="mt-4 rounded-lg border border-gray-200 px-4 py-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-900">Cash need</span>
                        <span class="text-gray-500">
                            ${{ number_format($cashRaised, 0) }} of ${{ number_format($item->cash_amount, 0) }} received
                        </span>
                    </div>
                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $cashPct }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ========== DECISION ========== --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Decision</h3>

            @if ($item->reviewed_at)
                <p class="text-xs text-gray-500 mb-4">
                    Last reviewed {{ $item->reviewed_at->format('M j, Y H:i') }}
                    @if ($item->reviewer) by {{ $item->reviewer->name }} @endif
                </p>
            @endif

            @if ($item->status === 'verified')
                <a href="{{ route('admin.aman-ids.card', $item) }}" target="_blank"
                   class="mb-3 block text-center w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 transition">
                    Print / Download ID Card
                </a>
            @endif

            @if ($item->status === 'rejected' && $item->rejection_reason)
                <div class="mb-4 rounded-lg bg-red-50 border border-red-100 p-3 text-sm text-red-700">
                    <span class="font-semibold">Rejection reason:</span> {{ $item->rejection_reason }}
                </div>
            @endif

            @if ($item->status !== 'verified')
                <form method="POST" action="{{ route('admin.aman-ids.approve', $item) }}" class="mb-3"
                      onsubmit="return confirm('Approve this Aman ID?')">
                    @csrf
                    <button class="w-full rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 transition">
                        Approve
                    </button>
                </form>
            @endif

            @if ($item->status !== 'rejected')
                <form method="POST" action="{{ route('admin.aman-ids.reject', $item) }}">
                    @csrf
                    <label class="block text-sm text-gray-600 mb-1">Reason for rejection</label>
                    <textarea name="rejection_reason" rows="3" required maxlength="500"
                              class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/30"
                              placeholder="Explain why this registration is rejected">{{ old('rejection_reason') }}</textarea>
                    @error('rejection_reason')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <button class="mt-3 w-full rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 transition">
                        Reject
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- ========== DONATIONS ========== --}}
@if ($item->status === 'verified')
    @php
        $donations = $item->donations()->latest('donated_at')->latest('id')->get();
        $inputCls = 'w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40';
    @endphp

    <div class="mt-6 grid lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Donations received</h3>
                <div class="text-sm text-gray-500">Total:
                    <span class="font-bold text-gray-900">${{ number_format($donations->sum('amount'), 2) }}</span>
                    ({{ $donations->count() }})
                </div>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @forelse ($donations as $d)
                        <tr>
                            <td class="px-6 py-3 text-gray-500 whitespace-nowrap">{{ $d->donated_at->format('M j, Y') }}</td>
                            <td class="px-3 py-3 text-gray-900">{{ $d->donor_name ?: 'Anonymous' }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ \App\Models\AmanDonation::needLabel($d->need) }}</td>
                            <td class="px-3 py-3 text-right font-semibold">${{ number_format($d->amount, 2) }}</td>
                            <td class="px-6 py-3 text-right">
                                <form method="POST" action="{{ route('admin.aman-donations.destroy', $d) }}" onsubmit="return confirm('Remove this donation record?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-8 text-center text-gray-400">No donations recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Record a donation</h3>

            @if ($errors->hasAny(['amount','donor_email','need','method','donated_at']))
                <div class="mb-3 rounded-lg bg-red-50 border border-red-100 p-3 text-xs text-red-700">
                    @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.aman-donations.store', $item) }}" class="space-y-3">
                @csrf
                <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" placeholder="Amount (USD)" required class="{{ $inputCls }}">
                <input type="text" name="donor_name" value="{{ old('donor_name') }}" placeholder="Donor name (optional)" class="{{ $inputCls }}">
                <input type="email" name="donor_email" value="{{ old('donor_email') }}" placeholder="Donor email (optional)" class="{{ $inputCls }}">

                <select name="need" class="{{ $inputCls }}">
                    <option value="general">General support</option>
                    @if ($item->cash_need)<option value="cash">Direct cash</option>@endif
                    @foreach ($item->needs ?? [] as $slug)
                        <option value="{{ $slug }}" @selected(old('need') === $slug)>{{ \App\Models\AmanId::NEEDS[$slug][0] ?? $slug }}</option>
                    @endforeach
                </select>

                <select name="method" class="{{ $inputCls }}">
                    @foreach (\App\Models\AmanDonation::METHODS as $key => $label)
                        <option value="{{ $key }}" @selected(old('method') === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Receipt / reference no." class="{{ $inputCls }}">
                <input type="date" name="donated_at" value="{{ old('donated_at', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required class="{{ $inputCls }}">
                <textarea name="note" rows="2" placeholder="Note (optional)" class="{{ $inputCls }}">{{ old('note') }}</textarea>

                <button class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 transition">Save donation</button>
            </form>
        </div>
    </div>
@endif
@endsection