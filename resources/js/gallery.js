/* ═══════════════════════════════════════════════════════════
   GALLERY ADMIN — gallery.js
   Drop zone · Staging preview · Section filter · Sortable
═══════════════════════════════════════════════════════════ */

'use strict';

const Gallery = (() => {

    /* ── State ── */
    let stagedFiles = [];

    /* ── DOM refs (populated in init) ── */
    let dropZone, photosInput, stagingGrid, stagingBanner, submitBtn;

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    function init() {
        dropZone     = document.getElementById('galleryDropZone');
        photosInput  = document.getElementById('photosInput');
        stagingGrid  = document.getElementById('stagingGrid');
        stagingBanner = document.getElementById('stagingBanner');
        submitBtn    = document.getElementById('uploadSubmitBtn');

        if (!dropZone) return;

        /* Drop zone events */
        dropZone.addEventListener('dragover',  onDragOver);
        dropZone.addEventListener('dragleave', onDragLeave);
        dropZone.addEventListener('drop',      onDrop);

        /* File input change */
        photosInput.addEventListener('change', () => addFiles(photosInput.files));

        /* Section tab filter */
        document.querySelectorAll('.gallery-tab').forEach(tab => {
            tab.addEventListener('click', () => filterSection(tab.dataset.filter, tab));
        });

        /* Sortable per-section grids */
        initSortable();

        /* Auto-dismiss flash messages */
        const flash = document.querySelector('.gallery-flash');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.5s';
                flash.style.opacity    = '0';
                setTimeout(() => flash.remove(), 500);
            }, 3500);
        }
    }

    /* ══════════════════════════════════════
       DROP ZONE
    ══════════════════════════════════════ */
    function onDragOver(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    }

    function onDragLeave(e) {
        if (!dropZone.contains(e.relatedTarget)) {
            dropZone.classList.remove('drag-over');
        }
    }

    function onDrop(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        addFiles(e.dataTransfer.files);
    }

    /* ══════════════════════════════════════
       FILE STAGING
    ══════════════════════════════════════ */
    function addFiles(fileList) {
        Array.from(fileList).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const idx = stagedFiles.length;
            stagedFiles.push(file);
            renderStagingItem(file, idx);
        });
        syncInputFiles();
        updateStagingUI();
    }

    function renderStagingItem(file, idx) {
        const url  = URL.createObjectURL(file);
        const mb   = (file.size / 1024 / 1024).toFixed(1);
        const name = escapeHtml(file.name);

        const item = document.createElement('div');
        item.className = 'gallery-staging-item';
        item.id = `stg-${idx}`;
        item.innerHTML = `
            <img src="${url}" alt="${name}" />
            <span class="gallery-staging-size">${mb} MB</span>
            <button type="button" class="gallery-staging-remove" aria-label="Убрать фото" onclick="Gallery.removeStagingItem(${idx})">✕</button>
            <div class="gallery-staging-name">${name}</div>
        `;
        stagingGrid.appendChild(item);
    }

    function removeStagingItem(idx) {
        stagedFiles[idx] = null;
        const el = document.getElementById(`stg-${idx}`);
        if (el) {
            el.style.transition = 'opacity 0.2s, transform 0.2s';
            el.style.opacity    = '0';
            el.style.transform  = 'scale(0.85)';
            setTimeout(() => el.remove(), 200);
        }
        syncInputFiles();
        updateStagingUI();
    }

    function syncInputFiles() {
        const dt = new DataTransfer();
        stagedFiles.filter(Boolean).forEach(f => dt.items.add(f));
        photosInput.files = dt.files;
    }

    function updateStagingUI() {
        const count = stagedFiles.filter(Boolean).length;

        if (count > 0) {
            stagingBanner.classList.add('visible');
            stagingBanner.querySelector('.gallery-staging-banner-text').textContent =
                `${count} фото готово к загрузке`;
            submitBtn.disabled     = false;
            submitBtn.style.opacity = '1';
        } else {
            stagingBanner.classList.remove('visible');
            submitBtn.disabled      = true;
            submitBtn.style.opacity = '0.4';
            stagedFiles             = [];
        }
    }

    /* ══════════════════════════════════════
       SECTION FILTER
    ══════════════════════════════════════ */
    function filterSection(key, activeTab) {
        /* Update tab states */
        document.querySelectorAll('.gallery-tab').forEach(t => t.classList.remove('active'));
        activeTab.classList.add('active');

        /* Show / hide section blocks */
        document.querySelectorAll('.gallery-section-block').forEach(block => {
            const show = key === 'all' || block.dataset.section === key;
            block.style.display = show ? '' : 'none';
        });
    }

    /* ══════════════════════════════════════
       SORTABLE
    ══════════════════════════════════════ */
    function initSortable() {
        document.querySelectorAll('.gallery-photo-grid').forEach(grid => {
            Sortable.create(grid, {
                animation:    180,
                easing:       'cubic-bezier(.25,.1,.25,1)',
                ghostClass:   'sortable-ghost',
                chosenClass:  'sortable-chosen',
                dragClass:    'sortable-drag',
                handle:       '.gallery-photo-card',
                onEnd() {
                    const order = [...grid.querySelectorAll('.gallery-photo-card')]
                        .map(el => el.dataset.id);
                    saveOrder(order);
                }
            });
        });
    }

    function saveOrder(order) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token    = csrfMeta ? csrfMeta.content : '';

        fetch(window.GalleryConfig.orderUrl, {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  token,
            },
            body: JSON.stringify({ order }),
        })
            .then(r => r.json())
            .then(data => {
                if (!data.ok) console.warn('Order save failed', data);
            })
            .catch(err => console.error('Order save error:', err));
    }

    /* ══════════════════════════════════════
       UTILS
    ══════════════════════════════════════ */
    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ══════════════════════════════════════
       PUBLIC API
    ══════════════════════════════════════ */
    return {
        init,
        removeStagingItem,
    };

})();

/* Boot after DOM ready */
document.addEventListener('DOMContentLoaded', Gallery.init);
