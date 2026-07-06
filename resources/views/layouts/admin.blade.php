<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Կառավարում — @yield('title', 'Վահանակ')</title>
    @vite(['resources/css/admin.css', 'resources/js/admin-layout.js'])
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">🏔️</div>
        <div class="sidebar-logo-text">
            ՄԵՂՐԱՁՈՐ
            <span>ԿԱՌԱՎԱՐՄԱՆ ՎԱՀԱՆԱԿ</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Ընդհանուր</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Վահանակ
        </a>

        <div class="nav-section">Բովանդակություն</div>
        <a href="{{ route('admin.tours.index') }}" class="nav-item {{ request()->routeIs('admin.tours*') ? 'active' : '' }}">
            <span class="icon">🏔️</span> Շրջայցեր
        </a>
        <a href="{{ route('admin.calendar.index') }}"
           class="nav-item {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
            <span class="icon">📅</span> Օրացույց
        </a>
        <a href="{{ route('admin.booking.index') }}" class="nav-item {{ request()->routeIs('admin.booking*') ? 'active' : '' }}">
            <span class="icon">📋</span> Հայտեր
            @php $newCount = \App\Models\Booking::where('status','new')->count(); @endphp
            @if($newCount)
                <span class="badge badge-blue" style="margin-left:auto; font-size:11px;">{{ $newCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.gallery.index') }}" class="nav-item {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
            <span class="icon">🖼️</span> Պատկերասրահ
        </a>
        <a href="{{ route('admin.pages.index') }}" class="nav-item {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
            <span class="icon">⚙️</span> Կարգավորումներ
        </a>
        <a href="{{ route('admin.quads.index') }}" class="nav-item {{ request()->routeIs('admin.quads.*') ? 'active' : '' }}">
            🏍️ Տեխնիկա
        </a>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit">
                <span>→</span> Դուրս գալ
            </button>
        </form>
    </div>
</aside>

{{-- затемнение фона при открытом мобильном меню --}}
<div class="sidebar-overlay"></div>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <button type="button" class="menu-toggle" aria-label="Բացել մենյուն">☰</button>
            <div class="topbar-title">@yield('title', 'Վահանակ')</div>
        </div>

        <div class="topbar-right">

            {{-- Site link --}}
            <a href="{{ url('/') }}" target="_blank" class="site-link">
                🌐 <span>Կայք</span> ↗
            </a>

            {{-- Lang switcher --}}
            <div class="lang-switcher">
                @foreach(['hy' => 'ՀՅ', 'ru' => 'RU', 'en' => 'EN'] as $locale => $label)
                    <a href="{{ route('admin.lang', $locale) }}"
                       class="{{ app()->getLocale() === $locale ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- User --}}
            <div class="topbar-user">
                <div class="topbar-avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}</div>
                <span class="topbar-user-name">{{ auth('admin')->user()->name }}</span>
            </div>

        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                ✕ @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif
        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
