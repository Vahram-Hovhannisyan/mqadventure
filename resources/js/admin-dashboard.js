// admin-dashboard.js — прогрессивные улучшения дашборда админки (dashboard.blade.php)

document.addEventListener('DOMContentLoaded', () => {
    animateStatNumbers();
    highlightTodaySchedule();
    initScrollShadows();
});

/* ── плавный count-up для «чистых» чисел в stat-card (не трогает "3/12") ── */
function animateStatNumbers() {
    document.querySelectorAll('.stat-card .num').forEach((el) => {
        const raw = el.textContent.trim();
        const target = parseInt(raw, 10);

        if (Number.isNaN(target) || String(target) !== raw) return;

        const duration = 600;
        const start = performance.now();

        function tick(now) {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased);
            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = target;
            }
        }

        el.textContent = '0';
        requestAnimationFrame(tick);
    });
}

/* ── подсветка текущей/следующей записи в расписании на сегодня ──
   ожидает <tr class="today-row" data-time="HH:MM:SS"> */
function highlightTodaySchedule() {
    const rows = document.querySelectorAll('.today-row[data-time]');
    if (!rows.length) return;

    const now = new Date();
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    let nextMarked = false;

    rows.forEach((row) => {
        const parts = (row.dataset.time || '').split(':').map(Number);
        const h = parts[0];
        const m = parts[1] || 0;
        if (Number.isNaN(h)) return;

        const rowMinutes = h * 60 + m;

        if (rowMinutes < nowMinutes) {
            row.classList.add('is-past');
        } else if (!nextMarked) {
            row.classList.add('is-next');
            nextMarked = true;
        }
    });
}

/* ── тени по краям горизонтально скроллящихся таблиц (актуально на планшетах) ── */
function initScrollShadows() {
    document.querySelectorAll('.table-scroll').forEach((wrap) => {
        const update = () => {
            const scrollable = wrap.scrollWidth > wrap.clientWidth + 1;
            const atEnd = wrap.scrollLeft >= wrap.scrollWidth - wrap.clientWidth - 1;
            wrap.classList.toggle('can-scroll-right', scrollable && !atEnd);
            wrap.classList.toggle('can-scroll-left', scrollable && wrap.scrollLeft > 0);
        };

        update();
        wrap.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
    });
}
