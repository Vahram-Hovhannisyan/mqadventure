/* ================================================================
   tours-page.js  —  логика страницы /tours
   Подключать ПОСЛЕ tours.js (общий: карта, lightbox и т.д.)
   ================================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════════════════════════════
       FILTER TABS
    ══════════════════════════════════════════════ */
    const filterBtns = document.querySelectorAll('.tours-filter-btn');
    const tourCards  = document.querySelectorAll('.tour-card-full');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;
            tourCards.forEach(card => {
                const show = filter === 'all' || card.dataset.badge === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    });

    /* ══════════════════════════════════════════════
       BOOKING MODAL
    ══════════════════════════════════════════════ */
    const overlay    = document.getElementById('bookingOverlay');
    const pill       = document.getElementById('bmSelectedTour');
    const form       = document.getElementById('bmForm');
    const successBox = document.getElementById('bmSuccess');
    const selectWrap = document.getElementById('bmTourSelectWrap');
    const tourSelect = document.getElementById('bmTourSelect');
    const hiddenId   = document.getElementById('bmTourId');

    /* открыть модалку */
    window.openBookingModal = function (tourId, tourName) {
        if (!overlay) return;

        /* сброс */
        if (form) form.style.display = '';
        if (successBox) successBox.style.display = 'none';
        const submitBtn = form ? form.querySelector('.bm-submit') : null;
        if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = submitBtn.dataset.label || submitBtn.textContent; }

        if (tourId) {
            if (hiddenId) hiddenId.value = tourId;
            const nameEl = document.getElementById('bmSelectedTourName');
            if (nameEl) nameEl.textContent = tourName || '';
            if (pill) pill.style.display = 'flex';
            if (selectWrap) selectWrap.style.display = 'none';
        } else {
            if (hiddenId) hiddenId.value = '';
            if (pill) pill.style.display = 'none';
            if (selectWrap) selectWrap.style.display = '';
        }

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    /* закрыть модалку */
    window.closeBookingModal = function (e) {
        if (e && e.target !== overlay) return;
        if (!overlay) return;
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    /* синхронизация select → hidden input */
    if (tourSelect && hiddenId) {
        tourSelect.addEventListener('change', function () {
            hiddenId.value = this.value;
        });
    }

    /* submit через fetch (без перезагрузки) */
    if (form) {
        const submitBtn = form.querySelector('.bm-submit');
        const originalLabel = submitBtn ? submitBtn.textContent.trim() : '';

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!submitBtn) return;

            submitBtn.disabled = true;
            submitBtn.textContent = '...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken.content } : {},
                    body: new FormData(form),
                });

                if (res.ok || res.redirected) {
                    form.style.display    = 'none';
                    if (pill) pill.style.display = 'none';
                    if (successBox) successBox.style.display = 'block';

                    /* закрыть через 3.5 с */
                    setTimeout(() => {
                        window.closeBookingModal(null);
                        /* небольшая задержка перед сбросом формы */
                        setTimeout(() => {
                            form.reset();
                            if (form) form.style.display = '';
                            if (successBox) successBox.style.display = 'none';
                            if (submitBtn) {
                                submitBtn.disabled    = false;
                                submitBtn.textContent = originalLabel;
                            }
                        }, 400);
                    }, 3500);
                } else {
                    submitBtn.disabled    = false;
                    submitBtn.textContent = originalLabel;
                    /* TODO: показать ошибку валидации если нужно */
                }
            } catch (err) {
                console.error('Booking submit error:', err);
                submitBtn.disabled    = false;
                submitBtn.textContent = originalLabel;
            }
        });
    }

    /* ESC — закрыть модалку бронирования и карту */
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        window.closeBookingModal(null);
        if (typeof window.closeTourMap === 'function') window.closeTourMap();
        if (typeof window.closeWaypointModal === 'function') window.closeWaypointModal();
    });

    /* ══════════════════════════════════════════════
       FADE-UP INTERSECTION OBSERVER
    ══════════════════════════════════════════════ */
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        fadeEls.forEach(el => observer.observe(el));
    }

    /* ══════════════════════════════════════════════
       SMOOTH SCROLL для кнопки hero → #tours-list
    ══════════════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

});
