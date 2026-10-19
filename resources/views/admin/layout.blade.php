<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-slate-100 text-gray-800 antialiased">

@php
    // Live badge counts for the sidebar
    $badges = [
        'causes'   => \App\Models\Cause::where('status', 'pending')->count(),
        'aman'     => \App\Models\AmanId::where('status', 'pending')->count(),
        'pioneers' => \App\Models\PioneerApplication::where('status', 'pending')->count(),
        'messages' => \App\Models\ContactMessage::where('status', 'unread')->count(),
    ];

    // Simple outline icons (24x24)
    $icons = [
        'home'     => 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
        'heart'    => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
        'cash'     => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart'    => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
        'id'       => 'M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z',
        'users'    => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        'mail'     => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
        'news'     => 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z',
        'photo'    => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z',
        'pin'      => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z',
        'video'    => 'M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z',
        'play'     => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0zM15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z',
        'gift'     => 'M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
    ];

    // [label, route, active route patterns, icon, badge key]
    $menu = [
        'Overview' => [
            ['Dashboard', 'admin.dashboard', ['admin.dashboard'], 'home', null],
        ],
        'Fundraising' => [
            ['Causes', 'admin.causes.index', ['admin.causes.*'], 'heart', 'causes'],
            ['Cause Donations', 'admin.cause-donations.index', ['admin.cause-donations.index'], 'cash', null],
            ['Cause Report', 'admin.cause-donations.report', ['admin.cause-donations.report'], 'chart', null],
        ],
        'Aman ID' => [
            ['Aman IDs', 'admin.aman-ids.index', ['admin.aman-ids.*'], 'id', 'aman'],
            ['Aman Donations', 'admin.aman-donations.index', ['admin.aman-donations.index'], 'cash', null],
            ['Aman Report', 'admin.aman-donations.report', ['admin.aman-donations.report'], 'chart', null],
        ],
        'Community' => [
            ['Pioneer Applications', 'admin.pioneers.index', ['admin.pioneers.*'], 'users', 'pioneers'],
            ['Messages', 'admin.messages.index', ['admin.messages.*'], 'mail', 'messages'],
        ],
        'Content' => [
            ['Campaigns', 'admin.campaigns.index', ['admin.campaigns.*'], 'play', null],
            ['Blog Posts', 'admin.blog.index', ['admin.blog.*'], 'news', null],
            ['Gallery', 'admin.gallery.index', ['admin.gallery.*'], 'photo', null],
            ['Project Locations', 'admin.locations.index', ['admin.locations.*'], 'pin', null],
            ['Homepage Video', 'admin.video.form', ['admin.video.*'], 'video', null],
            ['People in Need', 'admin.people.index', ['admin.people.*'], 'heart', null],
            ['Aid Packages', 'admin.aid-packages.index', ['admin.aid-packages.*'], 'gift', null],
        ],
    ];

    $adminName = auth()->user()?->name ?? 'Admin';
@endphp

<div class="min-h-screen lg:flex">

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

    {{-- ================= SIDEBAR ================= --}}
    <aside id="sidebar"
           class="fixed lg:sticky top-0 left-0 z-40 h-screen w-72 shrink-0 -translate-x-full lg:translate-x-0
                  transition-transform duration-300 bg-gray-900 text-gray-300 flex flex-col">

        <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
            <div>
                <div class="text-white text-lg font-bold">Orphan<span class="text-blue-500">Prosperity</span></div>
                <div class="text-xs text-gray-500 mt-0.5">Admin Panel</div>
            </div>
            <button id="sidebarClose" type="button" class="lg:hidden text-gray-400 hover:text-white" aria-label="Close menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-4 py-5 text-sm">
            @foreach ($menu as $group => $items)
                <div class="px-3 mb-2 {{ $loop->first ? '' : 'mt-6' }} text-[11px] font-semibold uppercase tracking-wider text-gray-500">{{ $group }}</div>
                <div class="space-y-1">
                    @foreach ($items as [$label, $route, $patterns, $icon, $badgeKey])
                        @php
                            $active = request()->routeIs(...$patterns);
                            $count  = $badgeKey ? ($badges[$badgeKey] ?? 0) : 0;
                        @endphp
                        <a href="{{ route($route) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition
                                  {{ $active ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/30' : 'hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 shrink-0 {{ $active ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="{{ $icons[$icon] }}"/>
                            </svg>
                            <span class="flex-1">{{ $label }}</span>
                            @if ($count > 0)
                                <span class="text-[11px] font-semibold rounded-full px-2 py-0.5 {{ $active ? 'bg-white text-blue-700' : 'bg-amber-500 text-white' }}">{{ $count }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-gray-800">
            <div class="flex items-center gap-3 px-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center">
                    {{ strtoupper(mb_substr($adminName, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm text-white truncate">{{ $adminName }}</div>
                    <div class="text-xs text-gray-500">Administrator</div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('/') }}" target="_blank"
                   class="flex-1 text-center px-3 py-2 rounded-lg text-xs bg-gray-800 hover:bg-gray-700 hover:text-white transition">View site &rarr;</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 rounded-lg text-xs bg-gray-800 hover:bg-red-600 hover:text-white transition">Log out</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ================= CONTENT ================= --}}
    <div class="flex-1 min-w-0 flex flex-col">

        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-gray-200 px-4 sm:px-8 py-4 flex items-center gap-4">
            <button id="sidebarOpen" type="button" class="lg:hidden text-gray-600 hover:text-gray-900" aria-label="Open menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-xl font-bold text-gray-900 truncate">@yield('title', 'Dashboard')</h1>
            <div class="ml-auto flex items-center gap-3">
                @yield('actions')
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-8">
            @if (session('success'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openMenu()  { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
        function closeMenu() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }

        document.getElementById('sidebarOpen').addEventListener('click', openMenu);
        document.getElementById('sidebarClose').addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    })();
</script>
@stack('scripts')
</body>
</html>