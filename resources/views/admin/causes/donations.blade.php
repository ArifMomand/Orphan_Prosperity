@extends('admin.layout')
@section('title', 'Donations: ' . $cause->title)

@section('content')
@php
    $recordedTotal = $donations->sum('amount');
    $inputCls = 'w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40';
@endphp

<a href="{{ route('admin.causes.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to Causes</a>

<div class="mt-4 bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
        <h2 class="text-lg font-bold text-gray-900">{{ $cause->title }}</h2>
        <a href="{{ url('/causes/' . $cause->slug) }}" target="_blank" class="text-sm text-gray-500 hover:text-gray-900">View public page &rarr;</a>
    </div>

    <div class="flex justify-between text-sm text-gray-500 mb-2">
        <span><b class="text-gray-900">${{ number_format($cause->raised) }}</b> raised of ${{ number_format($cause->goal) }}</span>
        <span>{{ $cause->percentFunded() }}% &middot; {{ $cause->donors }} donors</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $cause->percentFunded() }}%"></div>
    </div>
    @if ($cause->opening_raised > 0 || $cause->opening_donors > 0)
        <p class="mt-3 text-xs text-gray-400">
            Includes a starting amount of ${{ number_format($cause->opening_raised) }} and {{ $cause->opening_donors }} donors recorded before tracking began.
            (Change it in <a href="{{ route('admin.causes.edit', $cause) }}" class="underline">Edit</a>.)
        </p>
    @endif
</div>

<div class="mt-6 grid lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Recorded donations</h3>
            <div class="text-sm text-gray-500">Total:
                <span class="font-bold text-gray-900">${{ number_format($recordedTotal, 2) }}</span>
                ({{ $donations->count() }})
            </div>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-100">
                @forelse ($donations as $d)
                    <tr>
                        <td class="px-6 py-3 text-gray-500 whitespace-nowrap">{{ $d->donated_at->format('M j, Y') }}</td>
                        <td class="px-3 py-3 text-gray-900">
                            {{ $d->donor_name ?: 'Anonymous' }}
                            @if ($d->reference)<div class="text-xs text-gray-400">{{ $d->reference }}</div>@endif
                        </td>
                        <td class="px-3 py-3 text-gray-600">{{ \App\Models\CauseDonation::METHODS[$d->method] ?? $d->method }}</td>
                        <td class="px-3 py-3 text-right font-semibold">${{ number_format($d->amount, 2) }}</td>
                        <td class="px-6 py-3 text-right">
                            <form method="POST" action="{{ route('admin.cause-donations.destroy', $d) }}" onsubmit="return confirm('Remove this donation record?')">
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

        @if ($errors->any())
            <div class="mb-3 rounded-lg bg-red-50 border border-red-100 p-3 text-xs text-red-700">
                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.causes.donations.store', $cause) }}" class="space-y-3">
            @csrf
            <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" placeholder="Amount (USD)" required class="{{ $inputCls }}">
            <input type="text" name="donor_name" value="{{ old('donor_name') }}" placeholder="Donor name (optional)" class="{{ $inputCls }}">
            <input type="email" name="donor_email" value="{{ old('donor_email') }}" placeholder="Donor email (optional)" class="{{ $inputCls }}">

            <select name="method" class="{{ $inputCls }}">
                @foreach (\App\Models\CauseDonation::METHODS as $key => $label)
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
@endsection