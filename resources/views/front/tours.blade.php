@extends('layouts.app')

@section('title', __('front.tours_page_title'))
@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/flatpickr.min.css"/>
@endpush
@push('styles')
    @vite(['resources/css/tours.css', 'resources/css/tours-page.css'])
    <style>
        /* ── tours page: скрыть nav-ссылки и CTA, оставить только лого и "← Главная" ── */
        #mainNav .nav-links,
        #mainNav .nav-cta,
        #mainNav .nav-auth {
            display: none !important;
        }
        .nav-back-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(237,242,232,0.7);
            text-decoration: none;
            letter-spacing: 0.03em;
            transition: color .2s;
        }
        .nav-back-home:hover { color: #edf2e8; }
        .nav-back-home svg { flex-shrink: 0; }

        /* мобильное меню — скрыть все ссылки кроме "на главную" */
        #mobileMenu a:not(.mobile-home-link) { display: none !important; }
        .mobile-home-link {
            display: block !important;
            font-size: 18px;
            color: #edf2e8;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 0;
        }
    </style>
@endpush

@section('content')

    {{-- ══════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════ --}}
    <div class="tours-hero">
        <div class="tours-hero-eyebrow">{{ __('front.tours_eyebrow') }}</div>
        <h1>
            {{ __('front.tours_page_h1_1') }}<br>
            <span>{{ __('front.tours_page_h1_2') }}</span>
        </h1>
        <p class="tours-hero-desc">{{ __('front.tours_page_desc') }}</p>
        <a href="#tours-list" class="btn-primary" style="border:none; cursor:pointer; display:inline-block;">
            {{ __('front.tours_page_cta') }} ↓
        </a>
        <div class="tours-hero-stats">
            <div>
                <div class="stat-num">{{ \App\Models\SiteSetting::get('stat1_num', null, '250+') }}</div>
                <div class="stat-label">{{ \App\Models\SiteSetting::get('stat1_label') ?: __('front.stat1_label') }}</div>
            </div>
            <div>
                <div class="stat-num">{{ $tours->count() }}</div>
                <div class="stat-label">{{ __('front.tours_stat_routes') }}</div>
            </div>
            <div>
                <div class="stat-num">{{ \App\Models\SiteSetting::get('stat3_num', null, '6') }}</div>
                <div class="stat-label">{{ \App\Models\SiteSetting::get('stat3_label') ?: __('front.stat3_label') }}</div>
            </div>
        </div>
    </div>

    {{-- Mountains divider --}}
    <div class="tours-divider">
        <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="100%" height="60">
            <path fill="#f9faf7" d="M0,40 L30,20 L60,40 L90,15 L120,40 L150,25 L180,40 L210,18 L240,40 L270,22
                 L300,40 L330,16 L360,40 L390,24 L420,40 L450,19 L480,40 L510,21 L540,40 L570,17
                 L600,40 L630,23 L660,40 L690,20 L720,40 L750,18 L780,40 L810,22 L840,40 L870,16
                 L900,40 L930,24 L960,40 L990,19 L1020,40 L1050,21 L1080,40 L1110,17 L1140,40
                 L1170,23 L1200,40 L1230,20 L1260,40 L1290,18 L1320,40 L1350,22 L1380,40 L1410,16
                 L1440,40 L1440,60 L0,60 Z"/>
        </svg>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTER BAR
    ══════════════════════════════════════════════ --}}
    @if($tours->count() > 3)
        <div class="tours-filter-wrap">
            <div class="tours-filter">
                <button class="tours-filter-btn active" data-filter="all">
                    {{ __('front.filter_all') }} ({{ $tours->count() }})
                </button>
                @foreach($tours->groupBy('badge_color') as $color => $group)
                    @if($color)
                        <button class="tours-filter-btn" data-filter="{{ $color }}">
                            {{ $group->first()->getBadge() }} ({{ $group->count() }})
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════
         TOURS LIST
    ══════════════════════════════════════════════ --}}
    <div class="tours-list-wrap" id="tours-list">
        <div class="tours-list-header">
            <h2>{{ __('front.tours_title') }}</h2>
            <div class="tours-count-label">{{ $tours->count() }} {{ __('front.tours_available') }}</div>
        </div>

        @forelse($tours as $i => $tour)
            <div class="tour-card-full fade-up"
                 data-badge="{{ $tour->badge_color }}"
                 style="{{ $i > 0 ? 'transition-delay:' . ($i * 0.07) . 's' : '' }}">

                {{-- Image --}}
                <div class="tour-card-img">
                    @if($tour->image)
                        <img src="{{ asset('storage/' . $tour->image) }}"
                             alt="{{ $tour->getName() }}"
                             loading="lazy"/>
                    @else
                        <div class="tour-card-img-ph">🏔</div>
                    @endif
                    <div class="tour-card-badge {{ $tour->badge_color === 'green' ? 'green' : '' }}">
                        {{ $tour->getBadge() }}
                    </div>
                </div>

                {{-- Body --}}
                <div class="tour-card-body">
                    <div class="tour-card-name">{{ $tour->getName() }}</div>
                    <p class="tour-card-desc">{{ $tour->getDescription() }}</p>

                    <div class="tour-card-meta">
                        <div class="tour-card-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                            {{ $tour->getDuration() }} {{ __('front.hours') }}
                        </div>
                        <div class="tour-card-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            {{ __('front.tour_up_to') }} {{ $tour->getPeople() }} {{ __('front.people') }}
                        </div>
                        @if(!empty($tour->route_points) && count($tour->route_points) > 0)
                            <div class="tour-card-meta-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                    <line x1="9" y1="3" x2="9" y2="18"/>
                                    <line x1="15" y1="6" x2="15" y2="21"/>
                                </svg>
                                {{ count($tour->route_points) }} {{ __('front.route_points_count') }}
                            </div>
                        @endif
                    </div>

                    <div class="tour-card-footer">
                        <div>
                            <div class="tour-card-price-from">{{ __('front.price_from') }}</div>
                            <div class="tour-card-price-val">
                                {{ $tour->getPriceFormatted() }}
                                <span>AMD / {{ __('front.per_person') }}</span>
                            </div>
                        </div>
                        <div class="tour-card-actions">
                            @if(!empty($tour->route_points) && count($tour->route_points) > 0)
                                <button class="btn-route-tour"
                                        onclick="openTourMap({{ $tour->id }})"
                                        type="button">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                        <line x1="9" y1="3" x2="9" y2="18"/>
                                        <line x1="15" y1="6" x2="15" y2="21"/>
                                    </svg>
                                    {{ __('front.view_route') }}
                                </button>
                            @endif
                            <button class="btn-book-tour"
                                    onclick="openBookingModal({{ $tour->id }}, '{{ addslashes($tour->getName()) }}')"
                                    type="button">
                                {{ __('front.tour_book_btn') }} →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="tours-empty">
                <div class="tours-empty-icon">🏔</div>
                <h3>{{ __('front.tours_empty_title') }}</h3>
                <p>{{ __('front.tours_empty_desc') }}</p>
            </div>
        @endforelse
    </div>

    {{-- ══════════════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════════════ --}}
    <div class="tours-cta">
        <h2>{{ __('front.tours_cta_title') }}</h2>
        <p>{{ __('front.tours_cta_desc') }}</p>
        <button class="btn-book-tour"
                onclick="openBookingModal(null, null)"
                style="font-size:15px; padding:15px 36px;">
            {{ __('front.form_submit') }} →
        </button>
    </div>

    {{-- ══════════════════════════════════════════════
         BOOKING MODAL
    ══════════════════════════════════════════════ --}}
    <div class="booking-modal-overlay" id="bookingOverlay" onclick="closeBookingModal(event)">
        <div class="booking-modal" id="bookingModal" role="dialog" aria-modal="true" aria-labelledby="bmTitle">

            <div class="booking-modal-header">
                <div>
                    <div class="booking-modal-title" id="bmTitle">{{ __('front.booking_modal_title') }}</div>
                    <div class="booking-modal-subtitle">{{ __('front.booking_modal_subtitle') }}</div>
                </div>
                <button class="booking-modal-close"
                        onclick="closeBookingModal(null)"
                        type="button"
                        aria-label="{{ __('front.close') }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="booking-modal-body">

                {{-- Selected tour pill --}}
                <div class="bm-selected-tour" id="bmSelectedTour" style="display:none">
                    <div class="bm-selected-tour-icon">🏔️</div>
                    <div>
                        <div class="bm-selected-tour-label">{{ __('front.form_tour') }}</div>
                        <div class="bm-selected-tour-name" id="bmSelectedTourName"></div>
                    </div>
                </div>

                {{-- Success state --}}
                <div class="bm-success" id="bmSuccess">
                    <div class="bm-success-icon">✅</div>
                    <h3>{{ __('front.booking_success_title') }}</h3>
                    <p>{{ __('front.booking_success_desc') }}</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('booking.store') }}" method="POST" id="bmForm">
                    @csrf
                    <input type="hidden" name="tour_id" id="bmTourId"/>

                    <div class="bm-form-row">
                        <div class="bm-form-group">
                            <label for="bm_name">{{ __('front.form_name') }} *</label>
                            <input type="text" id="bm_name" name="name"
                                   placeholder="{{ __('front.form_name_ph') }}" required/>
                        </div>
                        <div class="bm-form-group">
                            <label for="bm_phone">{{ __('front.form_phone') }} *</label>
                            <input type="tel" id="bm_phone" name="phone"
                                   placeholder="+374 / +7..." required/>
                        </div>
                    </div>

                    {{-- Tour select (показывается только при открытии без пре-выбора) --}}
                    <div class="bm-form-group" id="bmTourSelectWrap">
                        <label for="bmTourSelect">{{ __('front.form_tour') }}</label>
                        <select id="bmTourSelect" name="tour_id_select">
                            <option value="">{{ __('front.form_tour_ph') }}</option>
                            @foreach(\App\Models\Tour::active()->get() as $t)
                                <option value="{{ $t->id }}">{{ $t->getName() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bm-form-row">
                        <div class="bm-form-group">
                            <label for="bm_date">{{ __('front.form_date') }}</label>
                            <input type="text" id="bm_date" name="date" autocomplete="off" required/>
                        </div>
                        <div class="bm-form-group">
                            <label for="bm_people">{{ __('front.form_people') }}</label>
                            <input type="number" id="bm_people" name="people" min="1" max="20" value="2"/>
                        </div>
                    </div>

                    <div class="bm-form-group">
                        <label for="bm_time">{{ __('front.form_time') }}</label>
                        <select id="bm_time" name="time" required>
                            <option value="">{{ __('front.form_time_ph') }}</option>
                        </select>
                        <span id="bmTimeHint"></span>
                    </div>

                    <div class="bm-form-group quad-picker-group" id="bmQuadsGroup" style="display:none;">
                        <label>{{ __('front.form_quads_label') }} <span class="quad-optional-tag">{{ __('front.form_quads_optional') }}</span></label>
                        <div id="bmQuadsPicker" class="quad-grid"></div>
                        <span id="bmQuadsHint" class="quad-hint"></span>
                    </div>

                    <div class="bm-form-group">
                        <label for="bm_comment">{{ __('front.form_comment') }}</label>
                        <textarea id="bm_comment" name="comment"
                                  placeholder="{{ __('front.form_comment_ph') }}"></textarea>
                    </div>

                    <button type="submit" class="bm-submit"
                            data-label="{{ __('front.form_submit') }} →">
                        {{ __('front.form_submit') }} →
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         TOUR MAP MODAL
    ══════════════════════════════════════════════ --}}
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
                    <span class="legend-dot start"></span>{{ __('front.route_start') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot middle"></span>{{ __('front.route_point') }}
                    &nbsp;
                    <svg width="12" height="12" fill="none" stroke="#c0392b" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5"/>
                    </svg>
                    &nbsp;{{ __('front.route_point_media') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot end"></span>{{ __('front.route_end') }}
                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-line"></span>{{ __('front.route_path') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Waypoint modal --}}
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

@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/hy.js"></script>

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

        (function () {
            const laravelLocale = @json(app()->getLocale());
            const localeMap = { ru: 'ru', hy: 'hy', am: 'hy', en: 'default' };
            const fpLocale = localeMap[laravelLocale] || 'default';

            const dateInput   = document.getElementById('bm_date');
            const peopleInput = document.getElementById('bm_people');
            const timeSelect  = document.getElementById('bm_time');
            const timeHint    = document.getElementById('bmTimeHint');

            const quadsGroup  = document.getElementById('bmQuadsGroup');
            const quadsPicker = document.getElementById('bmQuadsPicker');
            const quadsHint   = document.getElementById('bmQuadsHint');

            const bmTourIdInput = document.getElementById('bmTourId');
            const bmTourSelect  = document.getElementById('bmTourSelect');

            if (!dateInput) return;

            const bmDatePicker = flatpickr(dateInput, {
                locale: fpLocale,
                dateFormat: 'Y-m-d',
                minDate: 'today',
                altInput: true,
                altFormat: 'j F Y',
                disableMobile: true,
                static: true,
                onChange: loadSlots
            });
            // делаем доступным снаружи, если нужно сбрасывать при открытии модалки
            window.bmDatePicker = bmDatePicker;

            function currentTourId() {
                return (bmTourIdInput && bmTourIdInput.value)
                    || (bmTourSelect && bmTourSelect.value)
                    || '';
            }

            let lastRequestId = 0;
            let lastQuadsRequestId = 0;

            function resetQuads() {
                quadsGroup.style.display = 'none';
                quadsPicker.innerHTML = '';
                quadsHint.textContent = '';
            }

            function loadSlots() {
                const tourId = currentTourId();
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
                        if (requestId !== lastRequestId) return;

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

            function loadQuads() {
                const tourId = currentTourId();
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
                        quadsPicker.innerHTML = `<div class="quad-empty" style="color:#c0392b;">${window.QUAD_I18N.quadsError}</div>`;
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

            function debounce(fn, ms) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), ms);
                };
            }

            if (bmTourSelect) bmTourSelect.addEventListener('change', loadSlots);
            peopleInput.addEventListener('change', loadSlots);
            peopleInput.addEventListener('input', debounce(loadSlots, 500));
            timeSelect.addEventListener('change', loadQuads);

            // если ваш openBookingModal() устанавливает bmTourId программно —
            // вызывайте window.reloadBookingSlots() сразу после этого
            window.reloadBookingSlots = loadSlots;
        })();
    </script>
]    {{-- tours.js — общий (карта, waypoint-модалка) --}}
    @vite(['resources/js/tours.js', 'resources/js/tours-page.js'])

    @php
        $toursData = $tours->map(fn($t) => [
            'id'           => $t->id,
            'name'         => $t->getName(),
            'route_points' => $t->getRoutePointsForFront(),
        ])->values()->all();
    @endphp

    <script>
        window.TOURS_DATA = {!! json_encode($toursData) !!};
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        /* ── Вставить кнопку "← Главная" в nav ── */
        document.addEventListener('DOMContentLoaded', function () {
            const navRight = document.querySelector('#mainNav > div');
            if (navRight) {
                const link = document.createElement('a');
                link.href = '/';
                link.className = 'nav-back-home';
                link.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg> {{ __('front.nav_home') }}`;
                navRight.prepend(link);
            }

            /* Мобильное меню — убрать все ссылки кроме lang, добавить "← Главная" */
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu) {
                mobileMenu.querySelectorAll('a:not(.lang-switcher a)').forEach(a => a.remove());
                const homeLink = document.createElement('a');
                homeLink.href = '/';
                homeLink.className = 'mobile-home-link';
                homeLink.textContent = '← {{ __("front.nav_home") }}';
                mobileMenu.prepend(homeLink);
            }
        });
    </script>
@endpush
