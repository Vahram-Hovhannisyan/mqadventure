

<?php $__env->startSection('title', __('front.tours_page_title')); ?>
<?php $__env->startPush('head'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/flatpickr.min.css"/>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/tours.css', 'resources/css/tours-page.css']); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="tours-hero">
        <div class="tours-hero-eyebrow"><?php echo e(__('front.tours_eyebrow')); ?></div>
        <h1>
            <?php echo e(__('front.tours_page_h1_1')); ?><br>
            <span><?php echo e(__('front.tours_page_h1_2')); ?></span>
        </h1>
        <p class="tours-hero-desc"><?php echo e(__('front.tours_page_desc')); ?></p>
        <a href="#tours-list" class="btn-primary" style="border:none; cursor:pointer; display:inline-block;">
            <?php echo e(__('front.tours_page_cta')); ?> ↓
        </a>
        <div class="tours-hero-stats">
            <div>
                <div class="stat-num"><?php echo e(\App\Models\SiteSetting::get('stat1_num', null, '250+')); ?></div>
                <div class="stat-label"><?php echo e(\App\Models\SiteSetting::get('stat1_label') ?: __('front.stat1_label')); ?></div>
            </div>
            <div>
                <div class="stat-num"><?php echo e($tours->count()); ?></div>
                <div class="stat-label"><?php echo e(__('front.tours_stat_routes')); ?></div>
            </div>
            <div>
                <div class="stat-num"><?php echo e(\App\Models\SiteSetting::get('stat3_num', null, '6')); ?></div>
                <div class="stat-label"><?php echo e(\App\Models\SiteSetting::get('stat3_label') ?: __('front.stat3_label')); ?></div>
            </div>
        </div>
    </div>

    
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

    
    <?php if($tours->count() > 3): ?>
        <div class="tours-filter-wrap">
            <div class="tours-filter">
                <button class="tours-filter-btn active" data-filter="all">
                    <?php echo e(__('front.filter_all')); ?> (<?php echo e($tours->count()); ?>)
                </button>
                <?php $__currentLoopData = $tours->groupBy('badge_color'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($color): ?>
                        <button class="tours-filter-btn" data-filter="<?php echo e($color); ?>">
                            <?php echo e($group->first()->getBadge()); ?> (<?php echo e($group->count()); ?>)
                        </button>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="tours-list-wrap" id="tours-list">
        <div class="tours-list-header">
            <h2><?php echo e(__('front.tours_title')); ?></h2>
            <div class="tours-count-label"><?php echo e($tours->count()); ?> <?php echo e(__('front.tours_available')); ?></div>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $tours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="tour-card-full fade-up"
                 data-badge="<?php echo e($tour->badge_color); ?>"
                 style="<?php echo e($i > 0 ? 'transition-delay:' . ($i * 0.07) . 's' : ''); ?>">

                
                <div class="tour-card-img">
                    <?php if($tour->image): ?>
                        <img src="<?php echo e(asset('storage/' . $tour->image)); ?>"
                             alt="<?php echo e($tour->getName()); ?>"
                             loading="lazy"/>
                    <?php else: ?>
                        <div class="tour-card-img-ph">🏔</div>
                    <?php endif; ?>
                    <div class="tour-card-badge <?php echo e($tour->badge_color === 'green' ? 'green' : ''); ?>">
                        <?php echo e($tour->getBadge()); ?>

                    </div>
                </div>

                
                <div class="tour-card-body">
                    <div class="tour-card-name"><?php echo e($tour->getName()); ?></div>
                    <p class="tour-card-desc"><?php echo e($tour->getDescription()); ?></p>

                    <div class="tour-card-meta">
                        <div class="tour-card-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                            <?php echo e($tour->getDuration()); ?> <?php echo e(__('front.hours')); ?>

                        </div>
                        <div class="tour-card-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            <?php echo e(__('front.tour_up_to')); ?> <?php echo e($tour->getPeople()); ?> <?php echo e(__('front.people')); ?>

                        </div>
                        <?php if(!empty($tour->route_points) && count($tour->route_points) > 0): ?>
                            <div class="tour-card-meta-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                    <line x1="9" y1="3" x2="9" y2="18"/>
                                    <line x1="15" y1="6" x2="15" y2="21"/>
                                </svg>
                                <?php echo e(count($tour->route_points)); ?> <?php echo e(__('front.route_points_count')); ?>

                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tour-card-footer">
                        <div>
                            <div class="tour-card-price-from"><?php echo e(__('front.price_from')); ?></div>
                            <div class="tour-card-price-val">
                                <?php echo e($tour->getPriceFormatted()); ?>

                                <span>AMD / <?php echo e(__('front.per_person')); ?></span>
                            </div>
                        </div>
                        <div class="tour-card-actions">
                            <?php if(!empty($tour->route_points) && count($tour->route_points) > 0): ?>
                                <button class="btn-route-tour"
                                        onclick="openTourMap(<?php echo e($tour->id); ?>)"
                                        type="button">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                        <line x1="9" y1="3" x2="9" y2="18"/>
                                        <line x1="15" y1="6" x2="15" y2="21"/>
                                    </svg>
                                    <?php echo e(__('front.view_route')); ?>

                                </button>
                            <?php endif; ?>
                            <button class="btn-book-tour"
                                    onclick="openBookingModal(<?php echo e($tour->id); ?>, '<?php echo e(addslashes($tour->getName())); ?>')"
                                    type="button">
                                <?php echo e(__('front.tour_book_btn')); ?> →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="tours-empty">
                <div class="tours-empty-icon">🏔</div>
                <h3><?php echo e(__('front.tours_empty_title')); ?></h3>
                <p><?php echo e(__('front.tours_empty_desc')); ?></p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="tours-cta">
        <h2><?php echo e(__('front.tours_cta_title')); ?></h2>
        <p><?php echo e(__('front.tours_cta_desc')); ?></p>
        <button class="btn-book-tour"
                onclick="openBookingModal(null, null)"
                style="font-size:15px; padding:15px 36px;">
            <?php echo e(__('front.form_submit')); ?> →
        </button>
    </div>

    
    <div class="booking-modal-overlay" id="bookingOverlay" onclick="closeBookingModal(event)">
        <div class="booking-modal" id="bookingModal" role="dialog" aria-modal="true" aria-labelledby="bmTitle">

            <div class="booking-modal-header">
                <div>
                    <div class="booking-modal-title" id="bmTitle"><?php echo e(__('front.booking_modal_title')); ?></div>
                    <div class="booking-modal-subtitle"><?php echo e(__('front.booking_modal_subtitle')); ?></div>
                </div>
                <button class="booking-modal-close"
                        onclick="closeBookingModal(null)"
                        type="button"
                        aria-label="<?php echo e(__('front.close')); ?>">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <div class="booking-modal-body">

                
                <div class="bm-selected-tour" id="bmSelectedTour" style="display:none">
                    <div class="bm-selected-tour-icon">🏔️</div>
                    <div>
                        <div class="bm-selected-tour-label"><?php echo e(__('front.form_tour')); ?></div>
                        <div class="bm-selected-tour-name" id="bmSelectedTourName"></div>
                    </div>
                </div>

                
                <div class="bm-success" id="bmSuccess">
                    <div class="bm-success-icon">✅</div>
                    <h3><?php echo e(__('front.booking_success_title')); ?></h3>
                    <p><?php echo e(__('front.booking_success_desc')); ?></p>
                </div>

                
                <form action="<?php echo e(route('booking.store')); ?>" method="POST" id="bmForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="tour_id" id="bmTourId"/>

                    <div class="bm-form-row">
                        <div class="bm-form-group">
                            <label for="bm_name"><?php echo e(__('front.form_name')); ?> *</label>
                            <input type="text" id="bm_name" name="name"
                                   placeholder="<?php echo e(__('front.form_name_ph')); ?>" required/>
                        </div>
                        <div class="bm-form-group">
                            <label for="bm_phone"><?php echo e(__('front.form_phone')); ?> *</label>
                            <input type="tel" id="bm_phone" name="phone"
                                   placeholder="+374 / +7..." required/>
                        </div>
                    </div>

                    
                    <div class="bm-form-group" id="bmTourSelectWrap">
                        <label for="bmTourSelect"><?php echo e(__('front.form_tour')); ?></label>
                        <select id="bmTourSelect" name="tour_id_select">
                            <option value=""><?php echo e(__('front.form_tour_ph')); ?></option>
                            <?php $__currentLoopData = \App\Models\Tour::active()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>"><?php echo e($t->getName()); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="bm-form-row">
                        <div class="bm-form-group">
                            <label for="bm_date"><?php echo e(__('front.form_date')); ?></label>
                            <input type="text" id="bm_date" name="date" autocomplete="off" required/>
                        </div>
                        <div class="bm-form-group">
                            <label for="bm_people"><?php echo e(__('front.form_people')); ?></label>
                            <input type="number" id="bm_people" name="people" min="1" max="20" value="2"/>
                        </div>
                    </div>

                    <div class="bm-form-group">
                        <label for="bm_time"><?php echo e(__('front.form_time')); ?></label>
                        <select id="bm_time" name="time" required>
                            <option value=""><?php echo e(__('front.form_time_ph')); ?></option>
                        </select>
                        <span id="bmTimeHint"></span>
                    </div>

                    <div class="bm-form-group quad-picker-group" id="bmQuadsGroup" style="display:none;">
                        <label><?php echo e(__('front.form_quads_label')); ?> <span class="quad-optional-tag"><?php echo e(__('front.form_quads_optional')); ?></span></label>
                        <div id="bmQuadsPicker" class="quad-grid"></div>
                        <span id="bmQuadsHint" class="quad-hint"></span>
                    </div>

                    <div class="bm-form-group">
                        <label for="bm_comment"><?php echo e(__('front.form_comment')); ?></label>
                        <textarea id="bm_comment" name="comment"
                                  placeholder="<?php echo e(__('front.form_comment_ph')); ?>"></textarea>
                    </div>

                    <button type="submit" class="bm-submit"
                            data-label="<?php echo e(__('front.form_submit')); ?> →">
                        <?php echo e(__('front.form_submit')); ?> →
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div id="tourMapModal" class="tour-map-modal" role="dialog" aria-modal="true">
        <div class="tour-map-modal-inner">
            <button class="tour-map-close" onclick="closeTourMap()" type="button"
                    aria-label="<?php echo e(__('front.close')); ?>">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <div id="tourMapModalTitle" class="tour-map-modal-title"></div>
            <div id="tourMap"></div>
            <div class="tour-map-legend">
                <div class="tour-map-legend-item">
                    <span class="legend-dot start"></span><?php echo e(__('front.route_start')); ?>

                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot middle"></span><?php echo e(__('front.route_point')); ?>

                    &nbsp;
                    <svg width="12" height="12" fill="none" stroke="#c0392b" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5"/>
                    </svg>
                    &nbsp;<?php echo e(__('front.route_point_media')); ?>

                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-dot end"></span><?php echo e(__('front.route_end')); ?>

                </div>
                <div class="tour-map-legend-item">
                    <span class="legend-line"></span><?php echo e(__('front.route_path')); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div id="waypointModal" class="waypoint-modal" role="dialog" aria-modal="true">
        <div class="waypoint-modal-inner">
            <button class="waypoint-modal-close" onclick="closeWaypointModal()" type="button"
                    aria-label="<?php echo e(__('front.close')); ?>">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/hy.js"></script>

    <script>
        window.QUAD_I18N = {
            timePh:          <?php echo json_encode(__('front.form_time_ph'), 15, 512) ?>,
            timeLoading:     <?php echo json_encode(__('front.form_time_loading'), 15, 512) ?>,
            timeChoose:      <?php echo json_encode(__('front.form_time_choose'), 15, 512) ?>,
            timeError:       <?php echo json_encode(__('front.form_time_error'), 15, 512) ?>,
            timeBlocked:     <?php echo json_encode(__('front.form_time_blocked'), 15, 512) ?>,
            timeBlockedHint: <?php echo json_encode(__('front.form_time_blocked_hint'), 15, 512) ?>,
            timeNone:        <?php echo json_encode(__('front.form_time_none'), 15, 512) ?>,
            timeNoneHint:    <?php echo json_encode(__('front.form_time_none_hint'), 15, 512) ?>,
            freeQuads:       <?php echo json_encode(__('front.form_time_free_quads'), 15, 512) ?>,
            quadsLoading: <?php echo json_encode(__('front.form_quads_loading'), 15, 512) ?>,
            quadsEmpty:   <?php echo json_encode(__('front.form_quads_empty'), 15, 512) ?>,
            quadsError:   <?php echo json_encode(__('front.form_quads_error'), 15, 512) ?>,
            quadsMax:     <?php echo json_encode(__('front.form_quads_max', ['max' => ':max']), 512) ?>,
            quadsSelected: <?php echo json_encode(__('front.form_quads_selected', ['count' => ':count', 'max' => ':max'])) ?>,
        };

        (function () {
            const laravelLocale = <?php echo json_encode(app()->getLocale(), 15, 512) ?>;
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

                fetch(`<?php echo e(route('availability.slots')); ?>?` + params.toString())
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

                fetch(`<?php echo e(route('availability.quads')); ?>?` + params.toString())
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
]    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/tours.js', 'resources/js/tours-page.js']); ?>

    <?php
        $toursData = $tours->map(fn($t) => [
            'id'           => $t->id,
            'name'         => $t->getName(),
            'route_points' => $t->getRoutePointsForFront(),
        ])->values()->all();
    ?>

    <script>
        window.TOURS_DATA = <?php echo json_encode($toursData); ?>;
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
                link.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg> <?php echo e(__('front.nav_home')); ?>`;
                navRight.prepend(link);
            }

            /* Мобильное меню — убрать все ссылки кроме lang, добавить "← Главная" */
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu) {
                mobileMenu.querySelectorAll('a:not(.lang-switcher a)').forEach(a => a.remove());
                const homeLink = document.createElement('a');
                homeLink.href = '/';
                homeLink.className = 'mobile-home-link';
                homeLink.textContent = '← <?php echo e(__("front.nav_home")); ?>';
                mobileMenu.prepend(homeLink);
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/front/tours.blade.php ENDPATH**/ ?>