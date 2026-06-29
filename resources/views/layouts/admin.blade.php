<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Կառավարում — @yield('title', 'Վահանակ')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue:       #2563EB;
            --blue-hover: #1D4ED8;
            --blue-light: #EFF6FF;
            --blue-mid:   #BFDBFE;
            --slate:      #1E293B;
            --slate-mid:  #475569;
            --slate-soft: #94A3B8;
            --border:     #E2E8F0;
            --bg:         #F8FAFC;
            --surface:    #FFFFFF;
            --red:        #EF4444;
            --red-light:  #FEF2F2;
            --green:      #16A34A;
            --green-light:#F0FDF4;
            --amber:      #D97706;
            --amber-light:#FFFBEB;
            --sidebar-w:  240px;
        }

        body {
            background: var(--bg);
            color: var(--slate);
            font-family: Inter, system-ui, sans-serif;
            font-size: 14px;
            display: flex;
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-logo-icon {
            width: 32px; height: 32px;
            background: var(--blue);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .sidebar-logo-text {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: var(--slate);
            line-height: 1.2;
        }
        .sidebar-logo-text span { color: var(--blue); display: block; }

        .sidebar-nav { padding: 12px 10px; flex: 1; }

        .nav-section {
            padding: 14px 10px 5px;
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--slate-soft);
            font-weight: 600;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--slate-mid);
            transition: all .15s;
            cursor: pointer;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            color: var(--slate);
            background: var(--bg);
        }
        .nav-item.active {
            color: var(--blue);
            background: var(--blue-light);
            font-weight: 600;
        }
        .nav-item .icon { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
        }
        .sidebar-footer form button {
            background: none; border: none;
            color: var(--slate-soft);
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            padding: 0;
            transition: color .15s;
            display: flex; align-items: center; gap: 6px;
        }
        .sidebar-footer form button:hover { color: var(--red); }

        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            height: 56px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            position: sticky; top: 0; z-index: 10;
        }
        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--slate);
        }
        .topbar-right {
            display: flex; align-items: center; gap: 12px;
        }

        /* ── Site link ── */
        .site-link {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 12px; font-weight: 600;
            color: var(--slate-mid);
            border: 1.5px solid var(--border);
            background: var(--bg);
            transition: all .15s;
        }
        .site-link:hover {
            color: var(--blue);
            border-color: var(--blue-mid);
            background: var(--blue-light);
        }

        /* ── Lang switcher ── */
        .lang-switcher {
            display: flex; align-items: center;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            background: var(--bg);
        }
        .lang-switcher a {
            padding: 4px 9px;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.06em;
            color: var(--slate-soft);
            text-transform: uppercase;
            transition: all .15s;
            border-right: 1px solid var(--border);
        }
        .lang-switcher a:last-child { border-right: none; }
        .lang-switcher a:hover { color: var(--blue); background: var(--blue-light); }
        .lang-switcher a.active {
            background: var(--blue);
            color: #fff;
        }

        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: var(--slate-mid); font-weight: 500;
        }
        .topbar-avatar {
            width: 28px; height: 28px;
            background: var(--blue-light);
            color: var(--blue);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
        }

        .page-content { padding: 24px; flex: 1; }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h3 { font-size: 14px; font-weight: 700; color: var(--slate); }
        .card-body { padding: 20px; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            padding: 10px 16px;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--slate-soft);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            color: var(--slate);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; border: none;
            transition: all .15s;
            text-decoration: none;
            letter-spacing: 0.01em;
        }
        .btn-primary { background: var(--blue); color: #fff; }
        .btn-primary:hover { background: var(--blue-hover); }

        .btn-outline {
            background: transparent;
            color: var(--blue);
            border: 1.5px solid var(--blue-mid);
        }
        .btn-outline:hover { background: var(--blue-light); border-color: var(--blue); }

        .btn-ghost {
            background: var(--bg);
            color: var(--slate-mid);
            border: 1.5px solid var(--border);
        }
        .btn-ghost:hover { background: var(--border); color: var(--slate); }

        .btn-danger {
            background: var(--red-light);
            color: var(--red);
            border: 1.5px solid #FECACA;
        }
        .btn-danger:hover { background: #FEE2E2; }

        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 5px; }
        .btn-xs { padding: 3px 8px; font-size: 11px; border-radius: 4px; }

        /* ── Forms ── */
        .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
        .form-group label {
            font-size: 12px; font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--slate-mid);
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            background: var(--surface);
            border: 1.5px solid var(--border);
            color: var(--slate);
            padding: 9px 12px;
            border-radius: 7px;
            font-family: inherit; font-size: 13px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-row { display: grid; gap: 16px; }
        .form-row.col-2 { grid-template-columns: 1fr 1fr; }
        .form-row.col-3 { grid-template-columns: 1fr 1fr 1fr; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-blue   { background: var(--blue-light);  color: var(--blue); }
        .badge-green  { background: var(--green-light); color: var(--green); }
        .badge-red    { background: var(--red-light);   color: var(--red); }
        .badge-amber  { background: var(--amber-light); color: var(--amber); }
        .badge-gray   { background: var(--bg); color: var(--slate-soft); border: 1px solid var(--border); }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success { background: var(--green-light); border: 1px solid #BBF7D0; color: var(--green); }
        .alert-error   { background: var(--red-light);   border: 1px solid #FECACA; color: var(--red); }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
        }
        .stat-card .stat-icon {
            width: 38px; height: 38px;
            background: var(--blue-light);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }
        .stat-card .num {
            font-size: 28px; font-weight: 800;
            color: var(--slate);
            line-height: 1; margin-bottom: 4px;
        }
        .stat-card .lbl {
            font-size: 12px; font-weight: 500;
            color: var(--slate-soft);
        }

        /* ── Lang tabs (content editor) ── */
        .lang-tabs { display: flex; gap: 4px; margin-bottom: 0; }
        .lang-tab {
            padding: 5px 14px;
            border-radius: 6px 6px 0 0;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.06em;
            cursor: pointer;
            border: 1.5px solid var(--border);
            border-bottom: none;
            background: var(--bg);
            color: var(--slate-soft);
            transition: all .15s;
        }
        .lang-tab.active { background: var(--surface); color: var(--blue); border-color: var(--border); }
        .lang-panel { display: none; }
        .lang-panel.active { display: block; }

        /* ── Page header ── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .page-header h2 { font-size: 20px; font-weight: 800; color: var(--slate); }
        .page-header p { font-size: 13px; color: var(--slate-soft); margin-top: 2px; }
    </style>
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

<div class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('title', 'Վահանակ')</div>

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
                {{ auth('admin')->user()->name }}
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

<script>
    document.querySelectorAll('.lang-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const group = tab.closest('.lang-group');
            group.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
            group.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            group.querySelector('.lang-panel[data-lang="' + tab.dataset.lang + '"]').classList.add('active');
        });
    });
</script>
@stack('scripts')
</body>
</html>
