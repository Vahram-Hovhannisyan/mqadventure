/* ================================================================
   tours.js  —  карта маршрута + модалка точки с медиа
   Leaflet 1.9.4 должен быть загружен до этого файла
   window.TOURS_DATA задаётся в Blade-шаблоне
   ================================================================ */

(function () {
    'use strict';

    let mapInstance  = null;
    let activeTourId = null;

    /* ── открыть карту тура ── */
    window.openTourMap = function (tourId) {
        const tour = (window.TOURS_DATA || []).find(t => t.id === tourId);
        if (!tour) return;

        activeTourId = tourId;
        document.getElementById('tourMapModalTitle').textContent = tour.name;
        document.getElementById('tmOverlay').classList.add('active');
        document.getElementById('tourMapModal').classList.add('active');
        document.body.style.overflow = 'hidden';

        setTimeout(() => _initMap(tour), 80);
    };

    /* ── закрыть карту ── */
    window.closeTourMap = function () {
        document.getElementById('tmOverlay').classList.remove('active');
        document.getElementById('tourMapModal').classList.remove('active');
        document.body.style.overflow = '';
        if (mapInstance) { mapInstance.remove(); mapInstance = null; }
        activeTourId = null;
    };

    /* ── инициализация Leaflet ── */
    function _initMap(tour) {
        const container = document.getElementById('tourMap');
        if (!container) return;
        if (mapInstance) { mapInstance.remove(); mapInstance = null; }

        const points = tour.route_points || [];
        if (points.length === 0) return;

        const avgLat = points.reduce((s, p) => s + p.lat, 0) / points.length;
        const avgLng = points.reduce((s, p) => s + p.lng, 0) / points.length;

        mapInstance = L.map(container, {
            center: [avgLat, avgLng],
            zoom: 13,
            scrollWheelZoom: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(mapInstance);

        /* красная линия маршрута */
        const latlngs = points.map(p => [p.lat, p.lng]);
        L.polyline(latlngs, {
            color: '#c0392b',
            weight: 3,
            opacity: 0.85,
            dashArray: '8, 5',
        }).addTo(mapInstance);

        /* пины */
        points.forEach((point, idx) => {
            const isFirst  = idx === 0;
            const isLast   = idx === points.length - 1;
            const hasMedia = point.media && point.media.length > 0;

            const marker = L.marker([point.lat, point.lng], {
                icon: _buildIcon(isFirst, isLast, hasMedia),
            }).addTo(mapInstance);

            marker.bindPopup(_buildPopupHtml(point, hasMedia), {
                maxWidth: 240,
                className: 'tour-leaflet-popup',
            });

            if (hasMedia) {
                marker.on('click', () => {
                    setTimeout(() => openWaypointModal(point), 10);
                });
            }
        });

        mapInstance.fitBounds(L.latLngBounds(latlngs), { padding: [30, 30] });
    }

    /* ── иконка пина ── */
    function _buildIcon(isFirst, isLast, hasMedia) {
        let bg;
        if (isFirst)       bg = '#27ae60';
        else if (isLast)   bg = '#2980b9';
        else if (hasMedia) bg = '#c0392b';
        else               bg = '#7f8c8d';

        const pulse = hasMedia
            ? `<span style="position:absolute;top:-4px;left:-4px;right:-4px;bottom:-4px;
           border-radius:50%;border:2px solid ${bg};opacity:.5;
           animation:pin-pulse 1.8s ease-out infinite;"></span>`
            : '';

        return L.divIcon({
            className: '',
            html: `<div style="position:relative;width:18px;height:18px">
               ${pulse}
               <div style="width:18px;height:18px;border-radius:50%;
                 background:${bg};border:3px solid #fff;
                 box-shadow:0 2px 8px rgba(0,0,0,.4);
                 cursor:pointer;position:relative;z-index:1;"></div>
             </div>`,
            iconSize: [18, 18],
            iconAnchor: [9, 9],
            popupAnchor: [0, -12],
        });
    }

    /* ── html попапа ── */
    function _buildPopupHtml(point, hasMedia) {
        const hint = hasMedia
            ? `<div style="margin-top:6px;font-size:11px;color:#c0392b;font-weight:500">
           📸 Нажмите на пин — открыть фото/видео</div>`
            : '';
        return `<div style="font-family:system-ui;max-width:220px">
      <div style="font-weight:600;font-size:13px;margin-bottom:3px">${point.label || ''}</div>
      ${point.description
            ? `<div style="font-size:12px;color:#555;line-height:1.4">${point.description}</div>`
            : ''}
      ${hint}
    </div>`;
    }

    /* ================================================================
       WAYPOINT MEDIA MODAL
    ================================================================ */

    window.openWaypointModal = function (point) {
        document.getElementById('waypointModalTitle').textContent = point.label || '';
        document.getElementById('waypointModalDesc').textContent  = point.description || '';

        const wrap = document.getElementById('waypointModalMedia');
        wrap.innerHTML = '';

        (point.media || []).forEach(item => {
            if (item.type === 'video') {
                const video = document.createElement('video');
                video.src      = item.url;
                video.controls = true;
                video.className = 'waypoint-media-video';
                wrap.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src       = item.url;
                img.className = 'waypoint-media-img';
                img.alt       = point.label || '';
                wrap.appendChild(img);
            }
        });

        document.getElementById('wpOverlay').classList.add('active');
        document.getElementById('waypointModal').classList.add('active');
    };

    window.closeWaypointModal = function () {
        document.getElementById('wpOverlay').classList.remove('active');
        document.getElementById('waypointModal').classList.remove('active');
        document.querySelectorAll('#waypointModalMedia video').forEach(v => v.pause());
    };

    /* закрытие по оверлеям */
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('tmOverlay')?.addEventListener('click', window.closeTourMap);
        document.getElementById('wpOverlay')?.addEventListener('click', window.closeWaypointModal);
    });

    /* CSS анимация пульса */
    if (!document.getElementById('pin-pulse-style')) {
        const s = document.createElement('style');
        s.id = 'pin-pulse-style';
        s.textContent = `@keyframes pin-pulse {
      0%   { transform:scale(1);   opacity:.5; }
      70%  { transform:scale(1.8); opacity:0;  }
      100% { transform:scale(1);   opacity:0;  }
    }`;
        document.head.appendChild(s);
    }

})();
