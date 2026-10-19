@extends('admin.layout')
@section('title', 'Cause Donation Report')

@section('content')
<form method="GET" class="flex flex-wrap items-end gap-3 mb-6">
    <div>
        <label class="block text-xs text-gray-500 mb-1">From</label>
        <input type="date" name="from" value="{{ $from }}" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">To</label>
        <input type="date" name="to" value="{{ $to }}" class="rounded-lg border border-gray-200 px-3 py-2 text-sm">
    </div>
    <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">Apply</button>
    <a href="{{ route('admin.cause-donations.report') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-900">Reset</a>
    <a href="{{ route('admin.cause-donations.index') }}" class="ml-auto px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-900 text-sm font-medium">All donations</a>
</form>

<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Recorded donations</div>
        <div class="text-2xl font-extrabold text-gray-900">${{ number_format($grandTotal, 2) }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Number of donations</div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($grandCount) }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Causes that received help</div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $fundedCount }} <span class="text-sm font-medium text-gray-400">of {{ $approvedCount }} approved</span></div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Average donation</div>
        <div class="text-2xl font-extrabold text-gray-900">${{ $grandCount ? number_format($grandTotal / $grandCount, 2) : '0.00' }}</div>
    </div>
</div>

@if ($openingTotal > 0)
    <p class="mb-6 text-xs text-gray-500">
        Not included above: ${{ number_format($openingTotal) }} in starting amounts entered before donation tracking began.
    </p>
@endif

<div class="grid lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-900">Received per cause</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Cause</th>
                    <th class="px-5 py-3 font-medium text-right">Donations</th>
                    <th class="px-5 py-3 font-medium text-right">Recorded</th>
                    <th class="px-5 py-3 font-medium text-right">Total raised</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($byCause as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            @if ($row->cause)
                                <a href="{{ route('admin.causes.donations', $row->cause) }}" class="text-blue-600 hover:underline">{{ $row->cause->title }}</a>
                                <div class="text-xs text-gray-400">{{ $row->cause->percentFunded() }}% of ${{ number_format($row->cause->goal) }}</div>
                            @else — @endif
                        </td>
                        <td class="px-5 py-3 text-right text-gray-600">{{ $row->donations }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">${{ number_format($row->total, 2) }}</td>
                        <td class="px-5 py-3 text-right text-gray-600">${{ number_format($row->cause?->raised ?? 0) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No donations in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-900">By method</div>
            <ul class="divide-y divide-gray-100 text-sm">
                @forelse ($byMethod as $row)
                    <li class="flex items-center justify-between px-5 py-3">
                        <span class="text-gray-700">{{ \App\Models\CauseDonation::METHODS[$row->method] ?? $row->method }}
                            <span class="text-gray-400">({{ $row->donations }})</span></span>
                        <span class="font-semibold text-gray-900">${{ number_format($row->total, 2) }}</span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-gray-400">No data.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-900">By month (last 12)</div>
            <ul class="divide-y divide-gray-100 text-sm">
                @forelse ($byMonth as $row)
                    <li class="flex items-center justify-between px-5 py-3">
                        <span class="text-gray-700">{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('F Y') }}
                            <span class="text-gray-400">({{ $row->donations }})</span></span>
                        <span class="font-semibold text-gray-900">${{ number_format($row->total, 2) }}</span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-gray-400">No data.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection