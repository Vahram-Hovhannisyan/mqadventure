// admin-layout.js — переключение языковых вкладок + мобильное меню сайдбара

document.addEventListener('DOMContentLoaded', () => {
    initLangTabs();
    initMobileSidebar();
});

/* ── вкладки языков в редакторах контента ── */
function initLangTabs() {
    document.querySelectorAll('.lang-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const group = tab.closest('.lang-group');
            if (!group) return;
            group.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
            group.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            const panel = group.querySelector('.lang-panel[data-lang="' + tab.dataset.lang + '"]');
            if (panel) panel.classList.add('active');
        });
    });
}

/* ── off-canvas сайдбар на мобильных ── */
function initMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggle = document.querySelector('.menu-toggle');

    if (!sidebar || !overlay || !toggle) return;

    function openSidebar() {
        sidebar.classList.add('sidebar-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('sidebar-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);

    // закрывать меню при переходе по пункту навигации (мобилка)
    sidebar.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 900) closeSidebar();
        });
    });

    // сброс состояния при возврате к десктопной ширине
    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) closeSidebar();
    });

    // закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });
}
