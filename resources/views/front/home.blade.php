@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/flatpickr.min.css"/>
@endpush

@push('styles')
    @vite(['resources/css/tours.css'])
    <style>
        .quad-picker-group label { display: flex; align-items: center; gap: 8px; }
        .quad-optional-tag {
            font-size: 11px;
            font-weight: 500;
            color: rgba(148, 163, 184, 0.9);
            background: rgba(148, 163, 184, 0.12);
            border-radius: 20px;
            padding: 2px 9px;
            text-transform: none;
        }

        .quad-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 10px;
            margin-top: 8px;
        }

        .quad-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 10px 8px 9px;
            border-radius: 10px;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            text-align: center;
            transition: border-color .15s ease, background .15s ease, transform .1s ease;
        }
        .quad-card:hover:not(.is-disabled) {
            border-color: rgba(74, 124, 64, 0.55);
            background: rgba(74, 124, 64, 0.08);
            transform: translateY(-1px);
        }
        .quad-card.is-selected {
            border-color: var(--sage, #4A7C40);
            background: rgba(74, 124, 64, 0.16);
        }
        .quad-card.is-disabled {
            opacity: 0.35;
            cursor: not-allowed;
            transform: none;
        }
        .quad-card .quad-cb {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .quad-card-media {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 8px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quad-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .quad-card-media-ph { font-size: 26px; opacity: 0.5; }

        .quad-card-check {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background: var(--sage, #4A7C40);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.6);
            transition: opacity .12s ease, transform .12s ease;
        }
        .quad-card.is-selected .quad-card-check {
            opacity: 1;
            transform: scale(1);
        }

        .quad-card-name {
            font-size: 12px;
            line-height: 1.3;
            color: inherit;
        }

        .quad-hint {
            display: block;
            font-size: 12px;
            color: var(--slate-soft, #94a3b8);
            margin-top: 8px;
        }

        .quad-empty {
            font-size: 13px;
            color: var(--slate-soft, #94a3b8);
            padding: 6px 0;
        }
    </style>
@endpush

@section('content')

    @php
        $heroBg    = \App\Models\SiteSetting::get('hero_bg_image');
        $heroVideo = \App\Models\SiteSetting::get('hero_bg_video');
    @endphp
    <div class="hero" @if($heroBg && !$heroVideo) style="background-image:url('{{ asset('storage/' . $heroBg) }}'); background-size:cover; background-position:center;" @endif>
        @if($heroVideo)
            <video class="hero-bg-video" autoplay muted loop playsinline
                   @if($heroBg) poster="{{ asset('storage/' . $heroBg) }}" @endif>
                <source src="{{ asset('storage/' . $heroVideo) }}" type="video/mp4">
            </video>
        @endif
        <div class="hero-bg"></div>
        <div class="hero-mountains">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path fill="#2D5A27"
                      d="M0,320 L0,200 L120,100 L240,160 L360,60 L480,140 L600,40 L720,120 L840,30 L960,110 L1080,50 L1200,130 L1320,80 L1440,160 L1440,320 Z"/>
                <path fill="#1A3A16"
                      d="M0,320 L0,240 L180,160 L300,200 L420,130 L540,180 L660,110 L780,170 L900,100 L1020,160 L1140,120 L1260,175 L1380,140 L1440,190 L1440,320 Z"/>
                <path fill="#0F2A0C"
                      d="M0,320 L0,270 L160,220 L280,250 L400,200 L520,240 L640,190 L760,230 L880,185 L1000,225 L1120,200 L1240,235 L1360,210 L1440,240 L1440,320 Z"/>
            </svg>
        </div>
        <div class="hero-content fade-up">
            <p class="hero-eyebrow">{{ \App\Models\SiteSetting::get('hero_eyebrow') ?: __('front.hero_eyebrow') }}</p>
            <h1 class="hero-title">MEGHRA<span class="o">D</span><span class="g">Z</span>OR</h1>
            <div class="hero-subtitle">Quad Adventure</div>
            <p class="hero-desc">{{ \App\Models\SiteSetting::get('hero_desc') ?: __('front.hero_desc') }}</p>
            <div class="hero-actions">
                <a href="{{ route('tours.page') }}" class="btn-primary">{{ __('front.hero_btn_tours') }}</a>
                <a href="#gallery" class="btn-outline">{{ __('front.hero_btn_gallery') }}</a>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat">
                <div class="stat-num">{{ \App\Models\SiteSetting::get('stat1_num', null, '250+') }}</div>
                <div class="stat-label">{{ \App\Models\SiteSetting::get('stat1_label') ?: __('front.stat1_label') }}</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">{{ \App\Models\SiteSetting::get('stat2_num', null, '12') }}</div>
                <div class="stat-label">{{ \App\Models\SiteSetting::get('stat2_label') ?: __('front.stat2_label') }}</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">{{ \App\Models\SiteSetting::get('stat3_num', null, '6') }}</div>
                <div class="stat-label">{{ \App\Models\SiteSetting::get('stat3_label') ?: __('front.stat3_label') }}</div>
            </div>
        </div>
    </div>

    {{-- FOREST DIVIDER --}}
    <div class="forest-divider">
        <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path fill="#4A7C40"
                  d="M0,40 L30,20 L60,40 L90,15 L120,40 L150,25 L180,40 L210,18 L240,40 L270,22 L300,40 L330,16 L360,40 L390,24 L420,40 L450,19 L480,40 L510,21 L540,40 L570,17 L600,40 L630,23 L660,40 L690,20 L720,40 L750,18 L780,40 L810,22 L840,40 L870,16 L900,40 L930,24 L960,40 L990,19 L1020,40 L1050,21 L1080,40 L1110,17 L1140,40 L1170,23 L1200,40 L1230,20 L1260,40 L1290,18 L1320,40 L1350,22 L1380,40 L1410,16 L1440,40 L1440,60 L0,60 Z"/>
        </svg>
    </div>

    {{-- ABOUT --}}
    <div class="sec" id="about">
        <div class="about-grid">
            <div class="fade-up">
                <div class="about-tag">{{ \App\Models\SiteSetting::get('about_tag') ?: __('front.about_tag') }}</div>
                <p class="section-eyebrow">{{ __('front.nav_about') }}</p>
                <h2 class="section-title">{{ \App\Models\SiteSetting::get('about_title') ?: __('front.about_title') }}</h2>
                <p class="section-body"
                   style="margin-bottom:18px;">{{ \App\Models\SiteSetting::get('about_text1') ?: __('front.about_text1') }}</p>
                <p class="section-body">{{ \App\Models\SiteSetting::get('about_text2') ?: __('front.about_text2') }}</p>
            </div>
            @php $aboutImg = \App\Models\SiteSetting::get('about_image'); @endphp
            <div class="about-image-block fade-up" style="transition-delay:0.15s">
                <div class="about-accent-bar"></div>
                @if($aboutImg)
                    <img src="{{ asset('storage/' . $aboutImg) }}"
                         alt="{{ \App\Models\SiteSetting::get('about_title') ?: __('front.about_title') }}"
                         class="about-real-img" />
                @else
                    <div class="about-image-placeholder" id="about-img">
                        <span>PHOTO</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TOURS --}}
    <section class="sec" id="tours">
        <div class="tours-header">
            <div class="fade-up">
                <p class="section-eyebrow">{{ __('front.tours_eyebrow') }}</p>
                <h2 class="section-title">{{ __('front.tours_title') }}</h2>
            </div>
            <a href="{{ route('tours.page') }}" class="btn-outline " style="white-space: nowrap">
                {{ __('front.tours_all') }}
            </a>
        </div>

        <div class="tours-grid">
            @foreach($tours as $i => $tour)
                <div class="tour-card fade-up"
                     style="{{ $i > 0 ? 'transition-delay:' . ($i * 0.1) . 's' : '' }}">

                    <div class="tour-img">
                        @if($tour->image)
                            <img src="{{ asset('storage/' . $tour->image) }}"
                                 alt="{{ $tour->getName() }}"/>
                        @else
                            <div class="tour-img-placeholder">PHOTO</div>
                        @endif
                        <span class="tour-badge {{ $tour->badge_color === 'green' ? 'green' : '' }}">
                            {{ $tour->getBadge() }}
                        </span>
                    </div>

                    <div class="tour-body">
                        <div class="tour-name">{{ $tour->getName() }}</div>
                        <p class="tour-desc">{{ $tour->getDescription() }}</p>

                        <div class="tour-meta">
                            <div class="tour-meta-item">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 6v6l4 2"/>
                                </svg>
                                {{ $tour->getDuration() }} {{ __('front.hours') }}
                            </div>
                            <div class="tour-meta-item">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                </svg>
                                {{ $tour->getPeople() }} {{ __('front.people') }}
                            </div>
                        </div>

                        <div class="tour-price">
                            {{ $tour->getPriceFormatted() }}-{{ __('front.price_from') }}
                            <span class="tour-price-label">AMD/{{ __('front.per_quad') }}</span>
                        </div>

                        @if(!empty($tour->route_points) && count($tour->route_points) > 0)
                            <button class="tour-map-btn"
                                    onclick="openTourMap({{ $tour->id }})"
                                    type="button">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                    <line x1="9" y1="3" x2="9" y2="18"/>
                                    <line x1="15" y1="6" x2="15" y2="21"/>
                                </svg>
                                {{ __('front.view_route') }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- TOUR MAP MODAL --}}
    <div id="tourMapModal" class="tour-map-modal" role="dialog" aria-modal="true">
        <div class="tour-map-modal-inner">
            <button class="tour-map-close" onclick="closeTourMap()" type="button"
                    aria-label="{{ __('front.close') }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <div id="tourMapModalTitle" class="tour-map-modal-title"></div>
            <div id="tourMap"></div>
            <div class="tour-map-legend">
                <div class="tour-map-legend-item">
                    <span class="legend-dot start"></span>
                    {{ __('front.route_start') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot middle"></span>
                    {{ __('front.route_point') }}
                    &nbsp;
                    <svg width="12" height="12" fill="none" stroke="#c0392b" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5"/>
                    </svg>
                    &nbsp;{{ __('front.route_point_media') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot end"></span>
                    {{ __('front.route_end') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-line"></span>
                    {{ __('front.route_path') }}
                </div>
            </div>
        </div>
    </div>

    {{-- WAYPOINT MEDIA MODAL --}}
    <div id="waypointModal" class="waypoint-modal" role="dialog" aria-modal="true">
        <div class="waypoint-modal-inner">
            <button class="waypoint-modal-close" onclick="closeWaypointModal()" type="button"
                    aria-label="{{ __('front.close') }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <div id="waypointModalTitle" class="waypoint-modal-title"></div>
            <div id="waypointModalMedia" class="waypoint-modal-media"></div>
            <p id="waypointModalDesc" class="waypoint-modal-desc"></p>
        </div>
    </div>

    <div id="tmOverlay" class="tm-overlay"></div>
    <div id="wpOverlay" class="tm-overlay" style="z-index:1099;"></div>

    {{-- GALLERY --}}
    <div class="sec" id="gallery" style="padding-top:80px; padding-bottom:80px;">
        <p class="section-eyebrow fade-up">{{ __('front.gallery_eyebrow') }}</p>
        <h2 class="section-title fade-up">{{ __('front.gallery_title') }}</h2>
        <div class="gallery-grid">
            @forelse($gallery as $i => $item)
                <div class="gallery-item fade-up" style="{{ $i > 0 ? 'transition-delay:' . ($i * 0.05) . 's' : '' }}">
                    <img src="{{ asset('storage/' . ($item->thumb_path ?: $item->path)) }}"
                         alt="{{ $item->trans() }}"
                         onclick="openLightbox('{{ asset('storage/' . $item->path) }}')"/>
                </div>
            @empty
                @for($i = 1; $i <= 5; $i++)
                    <div class="gallery-item fade-up">
                        <div class="gallery-ph">{{ __('front.photo') }} {{ $i }}</div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>

    {{-- WHY US --}}
    <div class="sec why-section" style="padding-top:88px; padding-bottom:88px;">
        <p class="section-eyebrow fade-up">{{ __('front.why_eyebrow') }}</p>
        <h2 class="section-title fade-up">{{ __('front.why_title') }}</h2>
        <div class="why-grid">
            @foreach(__('front.why_items') as $i => $item)
                <div class="why-card fade-up" style="{{ $i > 0 ? 'transition-delay:' . ($i * 0.08) . 's' : '' }}">
                    <div class="why-icon">{{ $item['icon'] }}</div>
                    <div class="why-title">{{ $item['title'] }}</div>
                    <p class="why-text">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- CONTACT --}}
    {{-- CONTACT --}}
    <div id="contact" class="contact-wrap">
        <div class="contact-grid">
            <div class="fade-up">
                <p class="section-eyebrow">{{ __('front.contact_eyebrow') }}</p>
                <h2 class="section-title">{{ \App\Models\SiteSetting::get('contact_title') ?: __('front.contact_title') }}</h2>
                <p class="section-body" style="margin-bottom:36px;">
                    {{ \App\Models\SiteSetting::get('contact_desc') ?: __('front.contact_desc') }}
                </p>

                <div class="contact-info">

                    {{-- Телефон --}}
                    @php $phone = \App\Models\SiteSetting::get('phone', null, '+374 94 818 985'); @endphp
                    @if($phone)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.33a2 2 0 0 1 1.99-2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.08 6.08l.88-.88a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="contact-label">{{ __('front.contact_phone_label') }}</div>
                                <a href="tel:{{ preg_replace('/\D/', '', $phone) }}" class="contact-value contact-link">{{ $phone }}</a>
                            </div>
                        </div>
                    @endif

                    {{-- Email --}}
                    @php $email = \App\Models\SiteSetting::get('email', null, 'info@meghradzor.ru'); @endphp
                    @if($email)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div>
                                <div class="contact-label">Email</div>
                                <a href="mailto:{{ $email }}" class="contact-value contact-link">{{ $email }}</a>
                            </div>
                        </div>
                    @endif

                    {{-- Адрес --}}
                    @php $address = \App\Models\SiteSetting::get('address') ?: __('front.address'); @endphp
                    @if($address)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <div class="contact-label">{{ __('front.contact_location_label') }}</div>
                                <div class="contact-value">{{ $address }}</div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Мессенджеры и соцсети --}}
                @php
                    $socials = [
                        'whatsapp'  => ['label' => 'WhatsApp',  'url' => fn($v) => 'https://wa.me/' . preg_replace('/\D/', '', $v),   'color' => '#25D366'],
                        'viber'     => ['label' => 'Viber',     'url' => fn($v) => 'viber://chat?number=' . preg_replace('/\D/', '', $v), 'color' => '#7360F2'],
                        'telegram'  => ['label' => 'Telegram',  'url' => fn($v) => str_starts_with($v, 'http') ? $v : 'https://t.me/' . ltrim($v, '@'), 'color' => '#229ED9'],
                        'instagram' => ['label' => 'Instagram', 'url' => fn($v) => str_starts_with($v, 'http') ? $v : 'https://instagram.com/' . ltrim($v, '@'), 'color' => '#E1306C'],
                        'facebook'  => ['label' => 'Facebook',  'url' => fn($v) => str_starts_with($v, 'http') ? $v : 'https://facebook.com/' . $v, 'color' => '#1877F2'],
                    ];
                    $activeSocials = collect($socials)->filter(fn($_, $key) => \App\Models\SiteSetting::get($key));
                @endphp

                @if($activeSocials->isNotEmpty())
                    <div class="contact-socials">
                        <div class="contact-label" style="margin-bottom:12px;">{{ __('front.contact_socials_label') }}</div>
                        <div class="contact-social-links">
                            @foreach($activeSocials as $key => $social)
                                @php $val = \App\Models\SiteSetting::get($key); @endphp
                                <a href="{{ $social['url']($val) }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="contact-social-btn"
                                   style="--social-color: {{ $social['color'] }};">
                                    @if($key === 'whatsapp')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.533 5.852L.057 23.5a.5.5 0 0 0 .613.613l5.701-1.476A11.95 11.95 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.907 0-3.686-.537-5.197-1.467l-.373-.22-3.862 1 1.003-3.767-.242-.386A9.938 9.938 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                                        </svg>
                                    @elseif($key === 'viber')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.4 0C6.27.04 1.72 3.69.6 8.7c-.58 2.58-.39 5.27.54 7.72L.06 22.5a.5.5 0 0 0 .63.6l6.12-1.96c1.93.84 4.02 1.17 6.12 1.01 5.39-.41 9.69-4.72 10.07-10.11C23.43 5.42 18.08-.07 11.4 0zm.52 18.42c-1.76.13-3.52-.17-5.11-.87L3.5 19.3l.77-3.32c-.88-1.6-1.25-3.43-1.04-5.22C3.6 6.55 7.3 3.32 11.6 3.18c4.95-.15 9.01 3.72 9.04 8.67.04 4.98-3.82 9.21-8.72 9.57zm4.05-6.3c-.27-.13-1.6-.79-1.84-.88-.25-.09-.43-.13-.61.13-.18.27-.7.88-.86 1.06-.16.18-.32.2-.59.07-.27-.13-1.14-.42-2.17-1.34-.8-.71-1.35-1.6-1.5-1.87-.16-.27-.02-.42.12-.55.12-.12.27-.31.4-.47.13-.16.18-.27.27-.45.09-.18.05-.34-.02-.47-.07-.13-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47h-.52c-.16 0-.43.06-.65.31-.22.25-.87.85-.87 2.07s.89 2.4 1.01 2.57c.13.18 1.75 2.73 4.27 3.82.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.49-.61 1.7-1.2.21-.59.21-1.1.15-1.2-.06-.11-.23-.17-.5-.3z"/>
                                        </svg>
                                    @elseif($key === 'telegram')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.4 13.93l-2.93-.918c-.638-.197-.651-.638.136-.944l11.433-4.41c.53-.194.994.131.855.563z"/>
                                        </svg>
                                    @elseif($key === 'instagram')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                                        </svg>
                                    @elseif($key === 'facebook')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.514c-1.491 0-1.956.93-1.956 1.887v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                                        </svg>
                                    @endif
                                    {{ $social['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- FORM --}}
            <div class="contact-form fade-up" style="transition-delay:0.12s">
                @if(session('success'))
                    <div style="background:rgba(74,124,64,0.15); border:1px solid var(--sage); color:var(--sage); padding:14px 18px; border-radius:3px; margin-bottom:18px; font-size:14px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>{{ __('front.form_name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   placeholder="{{ __('front.form_name_ph') }}" required/>
                            @error('name')
                            <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('front.form_phone') }}</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   placeholder="+374 կամ +7..." required/>
                            @error('phone')
                            <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('front.form_tour') }}</label>
                        <select name="tour_id" id="bookingTourSelect" required>
                            <option value="">{{ __('front.form_tour_ph') }}</option>
                            @foreach(\App\Models\Tour::active()->get() as $tour)
                                <option value="{{ $tour->id }}"
                                        data-max-people="{{ $tour->getMaxCapacity() }}"
                                    {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                    {{ $tour->getName() }}
                                </option>
                            @endforeach
                        </select>
                        @error('tour_id')
                        <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>{{ __('front.form_date') }}</label>
                            <input type="text" name="date" id="bookingDate" value="{{ old('date') }}"
                                   autocomplete="off" placeholder="{{ __('front.form_date_ph') ?? '' }}" required/>
                            @error('date')
                            <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('front.form_people') }}</label>
                            <input type="number" name="people" id="bookingPeople" min="1" max="20" value="{{ old('people', 2) }}"/>
                            @error('people')
                            <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('front.form_time') }}</label>
                        <select name="time" id="bookingTimeSelect" required>
                            <option value="">{{ __('front.form_time_ph') }}</option>
                        </select>
                        <span id="bookingTimeHint" style="font-size:12px; color:var(--slate-soft, #94a3b8); margin-top:4px;"></span>
                        @error('time')
                        <span style="color:#f87171; font-size:12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group quad-picker-group" id="quadsGroup" style="display:none;">
                        <label>{{ __('front.form_quads_label') }} <span class="quad-optional-tag">{{ __('front.form_quads_optional') }}</span></label>
                        <div id="quadsPicker" class="quad-grid"></div>
                        <span id="quadsHint" class="quad-hint"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ __('front.form_comment') }}</label>
                        <textarea name="comment" placeholder="{{ __('front.form_comment_ph') }}">{{ old('comment') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="border:none; cursor:pointer; width:100%;">
                        {{ __('front.form_submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- Lightbox --}}
    <div id="lightbox"
         style="display:none; position:fixed; inset:0; z-index:999; background:rgba(0,0,0,0.92); align-items:center; justify-content:center;"
         onclick="closeLightbox()">
        <img id="lightbox-img" src="" style="max-width:90vw; max-height:90vh; border-radius:4px;"/>
    </div>

@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
    @vite(['resources/js/tours.js'])
    @php
        $toursData = $tours->map(function($t) {
            return [
                'id'           => $t->id,
                'name'         => $t->getName(),
                'route_points' => $t->getRoutePointsForFront(),
            ];
        })->values()->all();
    @endphp
    <script>
        window.TOURS_DATA = {!! json_encode($toursData) !!};
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').style.display = 'flex';
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
    </script>

    <script>
        window.QUAD_I18N = {
            timePh:          @json(__('front.form_time_ph')),
            timeLoading:     @json(__('front.form_time_loading')),
            timeChoose:      @json(__('front.form_time_choose')),
            timeError:       @json(__('front.form_time_error')),
            timeBlocked:     @json(__('front.form_time_blocked')),
            timeBlockedHint: @json(__('front.form_time_blocked_hint')),
            timeNone:        @json(__('front.form_time_none')),
            timeNoneHint:    @json(__('front.form_time_none_hint')),
            freeQuads:       @json(__('front.form_time_free_quads')),

            quadsLoading: @json(__('front.form_quads_loading')),
            quadsEmpty:   @json(__('front.form_quads_empty')),
            quadsError:   @json(__('front.form_quads_error')),
            quadsMax:     @json(__('front.form_quads_max', ['max' => ':max'])),
            quadsSelected: @json(__('front.form_quads_selected', ['count' => ':count', 'max' => ':max'])),
        };
    </script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/hy.js"></script>

    <script>
        (function () {
            // локаль Laravel -> код локали flatpickr
            const laravelLocale = @json(app()->getLocale());
            const localeMap = { ru: 'ru', hy: 'hy', am: 'hy', en: 'default' };
            const fpLocale = localeMap[laravelLocale] || 'default';

            window.bookingDatePicker = flatpickr('#bookingDate', {
                locale: fpLocale,
                dateFormat: 'Y-m-d',
                minDate: 'today',
                altInput: true,
                altFormat: 'j F Y',
                disableMobile: true
            });
        })();
    </script>
    <script>

        (function () {
            const tourSelect  = document.getElementById('bookingTourSelect');
            const dateInput   = document.getElementById('bookingDate');
            const peopleInput = document.getElementById('bookingPeople');
            const timeSelect  = document.getElementById('bookingTimeSelect');
            const timeHint    = document.getElementById('bookingTimeHint');

            const quadsGroup  = document.getElementById('quadsGroup');
            const quadsPicker = document.getElementById('quadsPicker');
            const quadsHint   = document.getElementById('quadsHint');

            let lastRequestId = 0;
            let lastQuadsRequestId = 0;

            function loadSlots() {
                const tourId = tourSelect.value;
                const date   = dateInput.value;
                const people = peopleInput.value || 1;

                resetQuads();

                if (!tourId || !date) {
                    timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timePh}</option>`;
                    timeHint.textContent = '';
                    return;
                }

                const requestId = ++lastRequestId;
                timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timeLoading}</option>`;
                timeHint.textContent = '';

                const params = new URLSearchParams({ tour_id: tourId, date: date, people: people });

                fetch(`{{ route('availability.slots') }}?` + params.toString())
                    .then(r => r.json())
                    .then(data => {
                        if (requestId !== lastRequestId) return; // stale response, ignore

                        if (data.blocked) {
                            timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timeBlocked}</option>`;
                            timeHint.textContent = window.QUAD_I18N.timeBlockedHint;
                            return;
                        }

                        const available = data.slots.filter(s => s.available);

                        if (!available.length) {
                            timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timeNone}</option>`;
                            timeHint.textContent = window.QUAD_I18N.timeNoneHint;
                            return;
                        }

                        timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timeChoose}</option>` +
                            available.map(s => `<option value="${s.time}">${s.time} (${window.QUAD_I18N.freeQuads} ${s.free_quads})</option>`).join('');
                        timeHint.textContent = '';
                    })
                    .catch(() => {
                        if (requestId !== lastRequestId) return;
                        timeSelect.innerHTML = `<option value="">${window.QUAD_I18N.timeError}</option>`;
                    });
            }

            function resetQuads() {
                quadsGroup.style.display = 'none';
                quadsPicker.innerHTML = '';
                quadsHint.textContent = '';
            }

            function loadQuads() {
                const tourId = tourSelect.value;
                const date   = dateInput.value;
                const time   = timeSelect.value;
                const people = parseInt(peopleInput.value || '1', 10);

                if (!tourId || !date || !time) {
                    resetQuads();
                    return;
                }

                const requestId = ++lastQuadsRequestId;
                quadsGroup.style.display = 'block';
                quadsPicker.innerHTML = `<div class="quad-empty">${window.QUAD_I18N.quadsLoading}</div>`;
                quadsHint.textContent = '';

                const params = new URLSearchParams({ tour_id: tourId, date: date, time: time });

                fetch(`{{ route('availability.quads') }}?` + params.toString())
                    .then(r => r.json())
                    .then(quads => {
                        if (requestId !== lastQuadsRequestId) return;

                        if (!quads.length) {
                            quadsPicker.innerHTML = `<div class="quad-empty">${window.QUAD_I18N.quadsEmpty}</div>`;
                            return;
                        }

                        quadsPicker.innerHTML = quads.map(q => `
                            <label class="quad-card" data-quad-card>
                                <input type="checkbox" name="quads[]" value="${q.id}" class="quad-cb">
                                <span class="quad-card-media">
                                    ${q.image_url
                            ? `<img src="${q.image_url}" alt="${q.name}">`
                            : `<span class="quad-card-media-ph">🏍️</span>`}
                                    <span class="quad-card-check">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </span>
                                </span>
                                <span class="quad-card-name">${q.name}</span>
                            </label>
                        `).join('');

                        enforceQuadLimit(people);
                    })
                    .catch(() => {
                        if (requestId !== lastQuadsRequestId) return;
                        quadsPicker.innerHTML = `<div class="quad-empty" style="color:#f87171;">${window.QUAD_I18N.quadsError}</div>`;
                    });
            }

            function enforceQuadLimit(maxSelect) {
                function apply() {
                    const checked = quadsPicker.querySelectorAll('.quad-cb:checked').length;

                    quadsPicker.querySelectorAll('[data-quad-card]').forEach(card => {
                        const cb = card.querySelector('.quad-cb');
                        cb.disabled = !cb.checked && checked >= maxSelect;
                        card.classList.toggle('is-selected', cb.checked);
                        card.classList.toggle('is-disabled', cb.disabled);
                    });

                    quadsHint.textContent = checked >= maxSelect
                        ? window.QUAD_I18N.quadsMax.replace(':max', maxSelect)
                        : (checked > 0 ? window.QUAD_I18N.quadsSelected.replace(':count', checked).replace(':max', maxSelect) : '');
                }
                quadsPicker.removeEventListener('change', quadsPicker._quadHandler || (() => {}));
                quadsPicker._quadHandler = apply;
                quadsPicker.addEventListener('change', apply);
                apply();
            }

            tourSelect.addEventListener('change', loadSlots);
            dateInput.addEventListener('change', loadSlots);
            peopleInput.addEventListener('change', loadSlots);
            peopleInput.addEventListener('input', debounce(loadSlots, 500));
            timeSelect.addEventListener('change', loadQuads);

            function debounce(fn, ms) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), ms);
                };
            }

            // Load initial slots if form was re-rendered after a validation error (old() values present)
            if (tourSelect.value && dateInput.value) {
                loadSlots();
            }
        })();
    </script>
@endpush
