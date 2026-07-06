<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ \App\Models\SiteSetting::get('site_title', null, 'Meghradzor Quad Adventure') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @stack('styles')
</head>
<body>

<!-- ── MOBILE MENU ─────────────────────────────────────────────────── -->
<div class="mobile-menu" id="mobileMenu">
    <a href="#about" onclick="closeMenu()">{{ __('front.nav_about') }}</a>
    <a href="#tours" onclick="closeMenu()">{{ __('front.nav_tours') }}</a>
    <a href="#gallery" onclick="closeMenu()">{{ __('front.nav_gallery') }}</a>
    <a href="#contact" onclick="closeMenu()">{{ __('front.nav_contact') }}</a>
    <a href="#contact" class="m-cta" onclick="closeMenu()">{{ __('front.tour_book_btn') }}</a>
    <div class="lang-switcher" style="display:flex; gap:12px; margin-top:12px;">
        @foreach(['hy','ru','en'] as $lang)
            <a href="{{ route('locale.switch', $lang) }}"
               style="color: {{ app()->getLocale() === $lang ? 'var(--orange)' : 'var(--sage)' }}; font-size:14px; text-decoration:none; font-weight:600;">
                {{ strtoupper($lang) }}
            </a>
        @endforeach
    </div>
</div>

<!-- ── NAV ────────────────────────────────────────────────────────── -->
<nav id="mainNav">
    <div class="nav-logo">
        <a href="/"><img src="{{ asset('images/logo.png') }}" alt="Meghradzor Quad Adventure" /></a>
    </div>
    <ul class="nav-links">
        <li><a href="#about">{{ __('front.nav_about') }}</a></li>
        <li><a href="#tours">{{ __('front.nav_tours') }}</a></li>
        <li><a href="#gallery">{{ __('front.nav_gallery') }}</a></li>
        <li><a href="#contact">{{ __('front.nav_contact') }}</a></li>
    </ul>
    <div style="display:flex; align-items:center; gap:20px;">
        <div class="lang-switcher" style="display:flex; gap:10px;">
            @foreach(['hy','ru','en'] as $lang)
                <a href="{{ route('locale.switch', $lang) }}"
                   style="color: {{ app()->getLocale() === $lang ? 'var(--orange)' : 'rgba(237,242,232,0.45)' }};
                  font-size:11px; font-weight:700; letter-spacing:0.1em; text-decoration:none; transition:color .2s;"
                   onmouseover="this.style.color='var(--sage)'" onmouseout="this.style.color='{{ app()->getLocale() === $lang ? 'var(--orange)' : 'rgba(237,242,232,0.45)' }}'">
                    {{ strtoupper($lang) }}
                </a>
            @endforeach
        </div>
        <a href="#contact" class="nav-cta">{{ __('front.tour_book_btn') }}</a>
        @auth('admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-auth nav-auth--profile">
                {{ auth('admin')->user()->name }}
            </a>
        @else
            <a href="{{ route('admin.login') }}" class="nav-auth">{{ __('front.login') }}</a>
        @endauth
    </div>
    <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
        <span></span><span></span><span></span>
    </button>
</nav>

@yield('content')

<!-- ── FOOTER ─────────────────────────────────────────────────────── -->
<footer>
    <div class="footer-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Meghradzor Quad Adventure" />
    </div>
    <p class="footer-copy">© {{ date('Y') }} Meghradzor Quad Adventure. {{ __('front.footer_rights') }}</p>
    <div class="footer-links">
        <a href="#">Instagram</a>
        <a href="#">Facebook</a>
        <a href="https://wa.me/{{ preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp', null, '')) }}" target="_blank">WhatsApp</a>
    </div>
</footer>

<script>
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    });
    function toggleMenu() {
        const menu = document.getElementById('mobileMenu');
        const btn  = document.getElementById('hamburger');
        menu.classList.toggle('open');
        btn.classList.toggle('open');
    }
    function closeMenu() {
        document.getElementById('mobileMenu').classList.remove('open');
        document.getElementById('hamburger').classList.remove('open');
    }
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    setTimeout(() => document.querySelectorAll('.hero .fade-up').forEach(el => el.classList.add('visible')), 100);
</script>

@stack('scripts')
</body>
</html>
