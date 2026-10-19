@extends('admin.layout')
@section('title', 'Aman Donations')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="q" value="{{ $q }}" placeholder="Donor, email, reference, Aman ID"
                   class="w-64 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Method</label>
            <select name="method" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
                <option value="">All</option>
                @foreach (\App\Models\AmanDonation::METHODS as $key => $label)
                    <option value="{{ $key }}" @selected($method === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">From</label>
            <input type="date" name="from" value="{{ $from }}" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">To</label>
            <input type="date" name="to" value="{{ $to }}" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
        </div>
        <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">Filter</button>
        <a href="{{ route('admin.aman-donations.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-900">Reset</a>
    </form>

    <div class="flex gap-2">
        <a href="{{ route('admin.aman-donations.export', request()->query()) }}"
           class="px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-900 text-sm font-medium">Download CSV</a>
        <a href="{{ route('admin.aman-donations.report') }}"
           class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-sm font-medium">View report</a>
    </div>
</div>

<div class="grid sm:grid-cols-2 gap-4 mb-6 max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Total (filtered)</div>
        <div class="text-2xl font-extrabold text-gray-900">${{ number_format($total, 2) }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Donations</div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($count) }}</div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Date</th>
                <th class="px-5 py-3 font-medium">Aman ID</th>
                <th class="px-5 py-3 font-medium">Donor</th>
                <th class="px-5 py-3 font-medium text-right">Amount</th>
                <th class="px-5 py-3 font-medium">For</th>
                <th class="px-5 py-3 font-medium">Method</th>
                <th class="px-5 py-3 font-medium">Reference</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($items as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $d->donated_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3">
                        @if ($d->beneficiary)
                            <a href="{{ route('admin.aman-ids.show', $d->beneficiary) }}" class="font-mono text-blue-600 hover:underline">{{ $d->beneficiary->aman_id }}</a>
                        @else — @endif
                    </td>
                    <td class="px-5 py-3 text-gray-900">{{ $d->donor_name ?: 'Anonymous' }}
                        @if ($d->donor_email)<div class="text-xs text-gray-400">{{ $d->donor_email }}</div>@endif
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-900">${{ number_format($d->amount, 2) }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ \App\Models\AmanDonation::needLabel($d->need) }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ \App\Models\AmanDonation::METHODS[$d->method] ?? $d->method }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $d->reference ?: '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('admin.aman-donations.destroy', $d) }}"
                              onsubmit="return confirm('Remove this donation record?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No donations recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $items->links() }}</div>
@endsection