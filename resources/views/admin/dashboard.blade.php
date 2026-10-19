@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
@php
    $maxBar = max(1, $chart->max(fn ($c) => max($c['cause'], $c['aman'])));
    $attention = [
        ['Causes waiting for approval', $causesPending,   route('admin.causes.index'),                            'amber'],
        ['Aman IDs to verify',          $amanPending,     route('admin.aman-ids.index', ['status' => 'pending']), 'blue'],
        ['Amin applications',           $pioneersPending, route('admin.pioneers.index'),                          'indigo'],
        ['Unread messages',             $messagesUnread,  route('admin.messages.index'),                          'rose'],
    ];
    $tone = [
        'amber'  => 'bg-amber-50 text-amber-700 ring-amber-200',
        'blue'   => 'bg-blue-50 text-blue-700 ring-blue-200',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'rose'   => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];

    // Home page campaigns (read here so the dashboard controller needs no change)
    $campaignTotal   = \App\Models\Campaign::count();
    $campaignActive  = \App\Models\Campaign::active()->count();
    $campaignHidden  = $campaignTotal - $campaignActive;
    $latestCampaigns = \App\Models\Campaign::ordered()->take(4)->get();
@endphp

{{-- ============ WELCOME ============ --}}
<div class="rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-800 text-white p-6 sm:p-8 shadow-lg shadow-indigo-900/20 mb-8 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/10"></div>
    <div class="absolute right-24 -bottom-16 w-48 h-48 rounded-full bg-white/5"></div>
    <div class="relative flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="text-sm text-blue-100">{{ now()->format('l, F j, Y') }}</div>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-1">Welcome back, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }}</h2>
            <p class="text-blue-100 mt-1 text-sm">
                @if ($causesPending + $amanPending + $pioneersPending + $messagesUnread > 0)
                    You have {{ $causesPending + $amanPending + $pioneersPending + $messagesUnread }} items that need your attention.
                @else
                    Everything is up to date.
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.causes.create') }}" class="bg-white text-indigo-700 hover:bg-blue-50 text-sm font-semibold px-5 py-2.5 rounded-full transition">+ New cause</a>
            <a href="{{ route('admin.aman-donations.index') }}" class="bg-white/15 hover:bg-white/25 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">Donations</a>
        </div>
    </div>
</div>

{{-- ============ NEEDS ATTENTION ============ --}}
<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    @foreach ($attention as [$label, $count, $url, $color])
        <a href="{{ $url }}" class="group bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl ring-1 flex items-center justify-center text-xl font-extrabold {{ $tone[$color] }}">{{ $count }}</div>
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-900">{{ $label }}</div>
                <div class="text-xs text-gray-400 group-hover:text-blue-600 transition">{{ $count ? 'Review now →' : 'All clear' }}</div>
            </div>
        </a>
    @endforeach
</div>

{{-- ============ KPI ROW ============ --}}
<div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">Recorded donations (all time)</div>
        <div class="text-2xl font-extrabold text-gray-900 mt-1">${{ number_format($totalRecorded) }}</div>
        <div class="text-xs text-gray-400 mt-1">Causes + Aman IDs</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs text-gray-500">This month</div>
        <div class="text-2xl font-extrabold text-gray-900 mt-1">${{ number_format($monthRecorded) }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</div>
    </div>
    <a href="{{ route('admin.causes.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
        <div class="text-xs text-gray-500">Approved causes</div>
        <div class="text-2xl font-extrabold text-gray-900 mt-1">{{ $causesApproved }}</div>
        <div class="text-xs text-gray-400 mt-1">of {{ $causesTotal }} total</div>
    </a>
    <a href="{{ route('admin.aman-ids.index', ['status' => 'verified']) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
        <div class="text-xs text-gray-500">Verified Aman IDs</div>
        <div class="text-2xl font-extrabold text-gray-900 mt-1">{{ $amanVerified }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $amanPending }} pending</div>
    </a>
    <a href="{{ route('admin.pioneers.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
        <div class="text-xs text-gray-500">Verified Amins</div>
        <div class="text-2xl font-extrabold text-gray-900 mt-1">{{ $pioneersVerified }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $pioneersPending }} pending</div>
    </a>
</div>

{{-- ============ CHART + RECENT DONATIONS ============ --}}
<div class="grid xl:grid-cols-3 gap-6 mb-8">

    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-semibold text-gray-900">Donations, last 6 months</h3>
                <p class="text-xs text-gray-400">Recorded donations only</p>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-600"></span>Causes</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500"></span>Aman IDs</span>
            </div>
        </div>

        <div class="flex items-end justify-between gap-3 h-56 border-b border-gray-100">
            @foreach ($chart as $c)
                <div class="flex-1 h-full flex flex-col justify-end items-center gap-2">
                    <div class="w-full flex items-end justify-center gap-1.5 flex-1">
                        <div class="w-1/3 max-w-[28px] rounded-t bg-blue-600 transition-all"
                             style="height: {{ $c['cause'] > 0 ? max(3, round($c['cause'] / $maxBar * 100)) : 0 }}%"
                             title="Causes: ${{ number_format($c['cause'], 2) }}"></div>
                        <div class="w-1/3 max-w-[28px] rounded-t bg-emerald-500 transition-all"
                             style="height: {{ $c['aman'] > 0 ? max(3, round($c['aman'] / $maxBar * 100)) : 0 }}%"
                             title="Aman IDs: ${{ number_format($c['aman'], 2) }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between gap-3 mt-2">
            @foreach ($chart as $c)
                <div class="flex-1 text-center">
                    <div class="text-xs font-medium text-gray-600">{{ $c['label'] }}</div>
                    <div class="text-[11px] text-gray-400">${{ number_format($c['cause'] + $c['aman']) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Recent donations</h3>
            <a href="{{ route('admin.cause-donations.index') }}" class="text-xs text-blue-600 hover:underline">All &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($recent as $r)
                <li class="px-6 py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ $r->donor }}</div>
                        <div class="text-xs text-gray-400 truncate">
                            <span class="inline-block px-1.5 rounded {{ $r->type === 'Cause' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">{{ $r->type }}</span>
                            @if ($r->url)<a href="{{ $r->url }}" class="hover:underline">{{ $r->for }}</a>@else {{ $r->for }} @endif
                            &middot; {{ $r->date->format('M j') }}
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-900 shrink-0">${{ number_format($r->amount, 2) }}</div>
                </li>
            @empty
                <li class="px-6 py-10 text-center text-sm text-gray-400">No donations recorded yet.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ============ WAITING FOR REVIEW ============ --}}
<div class="grid lg:grid-cols-3 gap-6 mb-8">

    {{-- Aman IDs --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Aman IDs to verify</h3>
            <a href="{{ route('admin.aman-ids.index', ['status' => 'pending']) }}" class="text-xs text-blue-600 hover:underline">All &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($pendingAman as $a)
                <li>
                    <a href="{{ route('admin.aman-ids.show', $a) }}" class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $a->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $a->aman_id }} &middot; {{ $a->province }}</div>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $a->created_at->diffForHumans(null, true) }}</span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-gray-400">Nothing waiting.</li>
            @endforelse
        </ul>
    </div>

    {{-- Causes --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Causes to approve</h3>
            <a href="{{ route('admin.causes.index') }}" class="text-xs text-blue-600 hover:underline">All &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($pendingCauses as $c)
                <li>
                    <a href="{{ route('admin.causes.edit', $c) }}" class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $c->title }}</div>
                            <div class="text-xs text-gray-400">{{ $c->category }} &middot; Goal ${{ number_format($c->goal) }}</div>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $c->created_at->diffForHumans(null, true) }}</span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-gray-400">Nothing waiting.</li>
            @endforelse
        </ul>
    </div>

    {{-- Amin applications --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Amin applications</h3>
            <a href="{{ route('admin.pioneers.index') }}" class="text-xs text-blue-600 hover:underline">All &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($pendingPioneers as $p)
                <li>
                    <a href="{{ route('admin.pioneers.show', $p) }}" class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $p->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $p->city }}</div>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $p->created_at->diffForHumans(null, true) }}</span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-gray-400">Nothing waiting.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ============ MESSAGES + CAUSE PROGRESS ============ --}}
<div class="grid lg:grid-cols-2 gap-6 mb-8">

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Latest messages</h3>
            <a href="{{ route('admin.messages.index') }}" class="text-xs text-blue-600 hover:underline">All &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($latestMessages as $m)
                <li>
                    <a href="{{ route('admin.messages.show', $m) }}" class="px-6 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition">
                        <div class="min-w-0">
                            <div class="text-sm text-gray-900 truncate {{ ($m->status ?? '') === 'unread' ? 'font-bold' : 'font-medium' }}">
                                {{ $m->name ?? $m->full_name ?? 'Visitor' }}
                                @if (($m->status ?? '') === 'unread')<span class="ml-1 inline-block w-2 h-2 rounded-full bg-blue-600 align-middle"></span>@endif
                            </div>
                            <div class="text-xs text-gray-400 truncate">{{ \Illuminate\Support\Str::limit($m->subject ?? $m->message ?? '', 60) }}</div>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $m->created_at->diffForHumans(null, true) }}</span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-gray-400">No messages yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Cause progress</h3>
            <a href="{{ route('admin.cause-donations.report') }}" class="text-xs text-blue-600 hover:underline">Report &rarr;</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($topCauses as $c)
                <li class="px-6 py-3">
                    <div class="flex items-center justify-between gap-3 mb-1.5">
                        <a href="{{ route('admin.causes.donations', $c) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 truncate">{{ $c->title }}</a>
                        <span class="text-xs text-gray-500 shrink-0">${{ number_format($c->raised) }} / ${{ number_format($c->goal) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $c->percentFunded() }}%"></div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm text-gray-400">No approved causes yet.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- ============ HOME PAGE CAMPAIGNS ============ --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-semibold text-gray-900">Home page campaigns</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pictures that open a YouTube video on your home page</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.campaigns.index') }}"
               class="border border-gray-300 hover:border-gray-900 text-xs font-semibold px-4 py-2 rounded-full transition">Manage</a>
            <a href="{{ route('admin.campaigns.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-full transition">+ Add campaigns</a>
        </div>
    </div>

    @if ($campaignTotal > 0)
        <div class="p-6 flex flex-wrap items-center gap-8">
            <div class="flex gap-8">
                <div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $campaignActive }}</div>
                    <div class="text-xs text-gray-500">Showing now</div>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $campaignHidden }}</div>
                    <div class="text-xs text-gray-500">Hidden</div>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $campaignTotal }}</div>
                    <div class="text-xs text-gray-500">Total</div>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:ml-auto">
                @foreach ($latestCampaigns as $camp)
                    <a href="{{ route('admin.campaigns.edit', $camp) }}" title="{{ $camp->title ?: 'Untitled' }}"
                       class="relative block w-14 sm:w-16 aspect-[4/5] rounded-lg overflow-hidden border border-gray-200 hover:ring-2 hover:ring-blue-500 transition {{ $camp->is_active ? '' : 'opacity-50' }}">
                        <img src="{{ $camp->imageUrl() }}" alt="" class="w-full h-full object-cover">
                        <span class="absolute inset-0 flex items-center justify-center">
                            <span class="w-5 h-5 rounded-full bg-blue-600/90 text-white flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 ml-px" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 0 0 1.52.85l11-6.86a1 1 0 0 0 0-1.7l-11-6.86A1 1 0 0 0 8 5.14z"/></svg>
                            </span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="px-6 py-10 text-center">
            <p class="text-sm text-gray-500 mb-4">No campaigns yet. Add a few pictures with their YouTube links and they will appear on the home page.</p>
            <a href="{{ route('admin.campaigns.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">Add your first campaigns</a>
        </div>
    @endif
</div>

{{-- ============ MANAGE EVERYTHING ============ --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="font-semibold text-gray-900 mb-1">Manage everything</h3>
    <p class="text-sm text-gray-500 mb-5">Jump straight to any part of the site.</p>

    @php
        $manage = [
            ['Add a cause',        route('admin.causes.create'),       'bg-blue-50 text-blue-700'],
            ['Add a blog post',    route('admin.blog.create'),         'bg-indigo-50 text-indigo-700'],
            ['Add a person in need', route('admin.people.create'),     'bg-rose-50 text-rose-700'],
            ['Add an aid package', route('admin.aid-packages.create'), 'bg-amber-50 text-amber-700'],
            ['Add a map location', route('admin.locations.create'),    'bg-emerald-50 text-emerald-700'],
            ['Gallery',            route('admin.gallery.index'),       'bg-sky-50 text-sky-700'],
            ['Replace homepage video', route('admin.video.form'),      'bg-purple-50 text-purple-700'],
            ['Aman donations report', route('admin.aman-donations.report'), 'bg-teal-50 text-teal-700'],
        ];
    @endphp

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach ($manage as [$label, $url, $classes])
            <a href="{{ $url }}" class="rounded-xl px-4 py-3.5 text-sm font-semibold transition hover:-translate-y-0.5 hover:shadow {{ $classes }}">{{ $label }}</a>
        @endforeach
    </div>
</div>
@endsection