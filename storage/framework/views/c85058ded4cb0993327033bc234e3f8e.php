
<?php $__env->startSection('title', isset($tour->id) ? 'Խմբ. շրջայց' : 'Նոր շրջայց'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .map-container { position: relative; }
        #routeMap { width: 100%; height: 420px; border-radius: 8px; border: 1.5px solid var(--border); }

        .point-card {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
        }
        .point-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .point-number {
            width: 28px; height: 28px;
            background: var(--blue);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }
        .map-instruction {
            font-size: 12px;
            color: var(--slate-soft);
            padding: 8px 12px;
            background: var(--bg);
            border-radius: 6px;
            margin-bottom: 12px;
            border: 1px solid var(--border);
        }

        /* ── Drop Zone ── */
        .drop-zone {
            border: 1.5px dashed var(--border);
            border-radius: 10px;
            padding: 22px 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
            background: var(--bg);
            position: relative;
            margin-top: 12px;
        }
        .drop-zone.drag-over {
            border-color: var(--blue);
            background: color-mix(in srgb, var(--blue) 8%, transparent);
        }
        .drop-zone input[type=file] {
            position: absolute; inset: 0; opacity: 0;
            cursor: pointer; width: 100%; height: 100%;
        }
        .drop-zone-icon { font-size: 26px; color: var(--slate-soft); margin-bottom: 6px; }
        .drop-zone-label { font-size: 12px; color: var(--slate-soft); line-height: 1.6; }
        .drop-zone-label strong { color: var(--blue); font-weight: 600; }
        .drop-zone-hint { font-size: 10px; color: var(--slate-soft); margin-top: 4px; opacity: 0.7; }

        /* ── Main image drop zone (larger) ── */
        .drop-zone-main {
            border: 1.5px dashed var(--border);
            border-radius: 10px;
            padding: 0;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }
        .drop-zone-main.drag-over {
            border-color: var(--blue);
            background: color-mix(in srgb, var(--blue) 8%, transparent);
        }
        .drop-zone-main input[type=file] {
            position: absolute; inset: 0; opacity: 0;
            cursor: pointer; width: 100%; height: 100%; z-index: 2;
        }
        .drop-zone-main .dz-placeholder {
            padding: 28px 16px;
            display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .drop-zone-main .dz-placeholder-icon { font-size: 32px; color: var(--slate-soft); }
        .drop-zone-main .dz-placeholder-label { font-size: 12px; color: var(--slate-soft); line-height: 1.6; }
        .drop-zone-main .dz-placeholder-label strong { color: var(--blue); font-weight: 600; }
        .drop-zone-main .dz-placeholder-hint { font-size: 10px; color: var(--slate-soft); opacity: 0.7; }

        /* Preview image inside main drop zone */
        #mainImagePreviewWrap {
            position: relative;
            width: 100%;
        }
        #mainImagePreview {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
            border-radius: 8px;
        }
        .main-image-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s;
        }
        #mainImagePreviewWrap:hover .main-image-overlay {
            background: rgba(0,0,0,0.38);
        }
        .main-image-overlay span {
            color: white;
            font-size: 12px;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }
        #mainImagePreviewWrap:hover .main-image-overlay span {
            opacity: 1;
        }
        .main-image-change-btn {
            position: absolute;
            bottom: 8px; right: 8px;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 11px;
            cursor: pointer;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 3;
        }
        #mainImagePreviewWrap:hover .main-image-change-btn {
            opacity: 1;
            pointer-events: auto;
        }
        .main-image-remove-btn {
            position: absolute;
            top: 8px; right: 8px;
            width: 24px; height: 24px;
            background: rgba(239,68,68,0.85);
            color: white; border: none; border-radius: 50%;
            cursor: pointer; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
            z-index: 4;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
        }
        #mainImagePreviewWrap:hover .main-image-remove-btn {
            opacity: 1;
            pointer-events: auto;
        }

        /* ── Preview grid (route point media) ── */
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }
        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
            background: var(--bg);
            border: 1px solid var(--border);
        }
        .preview-item img, .preview-item video {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .preview-delete {
            position: absolute; top: 4px; right: 4px;
            width: 20px; height: 20px;
            background: rgba(239,68,68,0.9);
            color: white; border: none; border-radius: 50%;
            cursor: pointer; font-size: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .preview-badge {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.5);
            color: #fff; font-size: 9px; padding: 3px 5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .preview-count {
            font-size: 11px; color: var(--slate-soft);
            margin-bottom: 6px; margin-top: 2px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div>
            <h2><?php echo e(isset($tour->id) ? '✏️ Խմբ. շրջայց' : '+ Նոր շրջայց'); ?></h2>
        </div>
        <a href="<?php echo e(route('admin.tours.index')); ?>" class="btn btn-ghost btn-sm">← Հետ</a>
    </div>

    <form method="POST"
          action="<?php echo e(isset($tour->id) ? route('admin.tours.update', $tour) : route('admin.tours.store')); ?>"
          enctype="multipart/form-data"
          id="tourForm">
        <?php echo csrf_field(); ?>
        <?php if(isset($tour->id)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start;">

            
            <div style="display: flex; flex-direction: column; gap: 20px;">

                
                <div class="card">
                    <div class="card-header"><h3>Հիմնական</h3></div>
                    <div class="card-body">
                        <div class="form-row col-2">
                            <div class="form-group">
                                <label>Slug *</label>
                                <input type="text" name="slug" value="<?php echo e(old('slug', $tour->slug)); ?>" required placeholder="quad-basic"/>
                            </div>
                            <div class="form-group">
                                <label>Badge գույն *</label>
                                <select name="badge_color">
                                    <option value="orange" <?php echo e(old('badge_color', $tour->badge_color) === 'orange' ? 'selected' : ''); ?>>🟠 Orange</option>
                                    <option value="green"  <?php echo e(old('badge_color', $tour->badge_color) === 'green'  ? 'selected' : ''); ?>>🟢 Green</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row col-3">
                            <div class="form-group">
                                <label>Գին (AMD) *</label>
                                <input type="number" name="price_from" value="<?php echo e(old('price_from', $tour->price_from)); ?>" required min="0"/>
                            </div>
                            <div class="form-group">
                                <label>Տևողություն min (ժ) *</label>
                                <input type="number" name="duration_min" value="<?php echo e(old('duration_min', $tour->duration_min)); ?>" required min="1"/>
                            </div>
                            <div class="form-group">
                                <label>Տևողություն max (ժ) *</label>
                                <input type="number" name="duration_max" value="<?php echo e(old('duration_max', $tour->duration_max)); ?>" required min="1"/>
                            </div>
                        </div>

                        <div class="form-row col-3">
                            <div class="form-group">
                                <label>Մարդ min *</label>
                                <input type="number" name="people_min" value="<?php echo e(old('people_min', $tour->people_min)); ?>" required min="1"/>
                            </div>
                            <div class="form-group">
                                <label>Մարդ max *</label>
                                <input type="number" name="people_max" value="<?php echo e(old('people_max', $tour->people_max)); ?>" required min="1"/>
                            </div>
                            <div class="form-group">
                                <label>Հ/կ (sort)</label>
                                <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $tour->sort_order ?? 0)); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php $__currentLoopData = ['hy' => 'Հայ.', 'ru' => 'Рус.', 'en' => 'Eng.']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card">
                        <div class="card-header">
                            <h3>🌐 <?php echo e($label); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-row col-2">
                                <div class="form-group">
                                    <label>Անուն *</label>
                                    <input type="text" name="name[<?php echo e($locale); ?>]"
                                           value="<?php echo e(old('name.' . $locale, $tour->name[$locale] ?? '')); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label>Badge</label>
                                    <input type="text" name="badge[<?php echo e($locale); ?>]"
                                           value="<?php echo e(old('badge.' . $locale, $tour->badge[$locale] ?? '')); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Նկարագրություն *</label>
                                <textarea name="description[<?php echo e($locale); ?>]" rows="3" required><?php echo e(old('description.' . $locale, $tour->description[$locale] ?? '')); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <div class="card">
                    <div class="card-header">
                        <h3>🗺️ Երթուղի</h3>
                        <button type="button" class="btn btn-outline btn-sm" onclick="addPoint()">+ Ավ. կետ</button>
                    </div>
                    <div class="card-body">
                        <div class="map-instruction">
                            💡 Քարտեզի վրա սեղմելով ավելացրեք կետ, կամ ձեռքով լրացրեք կոորդինատները
                        </div>

                        <div class="map-container" style="margin-bottom: 20px;">
                            <div id="routeMap"></div>
                        </div>

                        <div id="pointsList">
                            <?php $__currentLoopData = $tour->route_points ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="point-card" id="point-<?php echo e($i); ?>">
                                    <div class="point-card-header">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div class="point-number"><?php echo e($i + 1); ?></div>
                                            <span style="font-size: 13px; font-weight: 600;">Կետ <?php echo e($i + 1); ?></span>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="removePoint(<?php echo e($i); ?>)">✕ Հեռ.</button>
                                    </div>

                                    <div class="form-row col-2" style="margin-bottom: 12px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label>Lat</label>
                                            <input type="number" step="any"
                                                   name="route_points[<?php echo e($i); ?>][lat]"
                                                   value="<?php echo e($point['lat']); ?>"
                                                   class="point-lat" data-index="<?php echo e($i); ?>"/>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label>Lng</label>
                                            <input type="number" step="any"
                                                   name="route_points[<?php echo e($i); ?>][lng]"
                                                   value="<?php echo e($point['lng']); ?>"
                                                   class="point-lng" data-index="<?php echo e($i); ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-row col-3" style="margin-bottom: 12px;">
                                        <?php $__currentLoopData = ['hy' => 'Հայ.', 'ru' => 'Рус.', 'en' => 'Eng.']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label><?php echo e($lbl); ?></label>
                                                <input type="text"
                                                       name="route_points[<?php echo e($i); ?>][label][<?php echo e($loc); ?>]"
                                                       value="<?php echo e($point['label'][$loc] ?? ''); ?>"
                                                       placeholder="<?php echo e($lbl); ?>"/>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    
                                    <input type="hidden"
                                           name="route_points[<?php echo e($i); ?>][media_existing]"
                                           value="<?php echo e(json_encode($point['media'] ?? [])); ?>"
                                           class="media-existing-input"/>

                                    
                                    <?php if(!empty($point['media'])): ?>
                                        <div class="preview-count">Сохранено: <?php echo e(count($point['media'])); ?> файл(ов)</div>
                                        <div class="preview-grid" id="media-grid-<?php echo e($i); ?>">
                                            <?php $__currentLoopData = $point['media']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="preview-item" id="media-<?php echo e($i); ?>-<?php echo e($loop->index); ?>">
                                                    <?php if($m['type'] === 'video'): ?>
                                                        <video src="<?php echo e(asset('storage/' . $m['path'])); ?>" muted playsinline></video>
                                                    <?php else: ?>
                                                        <img src="<?php echo e(asset('storage/' . $m['path'])); ?>" alt="">
                                                    <?php endif; ?>
                                                    <button type="button" class="preview-delete"
                                                            onclick="deleteMedia(<?php echo e($i); ?>, '<?php echo e($m['path']); ?>', this)">✕</button>
                                                    <div class="preview-badge"><?php echo e(basename($m['path'])); ?></div>
                                                    <input type="hidden"
                                                           name="route_points[<?php echo e($i); ?>][media_delete][]"
                                                           value="<?php echo e($m['path']); ?>" disabled
                                                           class="media-delete-input"/>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <div class="drop-zone" id="dz-<?php echo e($i); ?>"
                                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                                         ondragleave="this.classList.remove('drag-over')"
                                         ondrop="handleDrop(event,<?php echo e($i); ?>)">
                                        <input type="file"
                                               name="route_points[<?php echo e($i); ?>][media_new][]"
                                               multiple accept="image/*,video/*"
                                               onchange="handleFileSelect(<?php echo e($i); ?>, this.files)"/>
                                        <div class="drop-zone-icon">☁️</div>
                                        <div class="drop-zone-label">
                                            <strong>Выберите файлы</strong> или перетащите сюда
                                        </div>
                                        <div class="drop-zone-hint">JPG · PNG · WEBP · MP4 · MOV — 4K поддерживается</div>
                                    </div>
                                    <div class="preview-grid" id="new-preview-<?php echo e($i); ?>"></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

            </div>

            
            <div style="display: flex; flex-direction: column; gap: 16px;">

                
                <div class="card">
                    <div class="card-header"><h3>Գործողություններ</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1"
                                       <?php echo e(old('is_active', $tour->is_active ?? true) ? 'checked' : ''); ?>

                                       style="width: auto;"/>
                                Ակտիվ
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <?php echo e(isset($tour->id) ? '💾 Պահ.' : '✅ Ստ.'); ?>

                        </button>
                        <?php if(isset($tour->id)): ?>
                            <a href="<?php echo e(route('admin.tours.index')); ?>" class="btn btn-ghost"
                               style="width: 100%; margin-top: 8px; text-align: center;">Չեղ.</a>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card">
                    <div class="card-header"><h3>📷 Գл. նկ.</h3></div>
                    <div class="card-body" style="padding-bottom: 12px;">

                        <div class="drop-zone-main" id="mainDz"
                             ondragover="event.preventDefault();this.classList.add('drag-over')"
                             ondragleave="this.classList.remove('drag-over')"
                             ondrop="handleMainImageDrop(event)">

                            <input type="file" name="image" accept="image/*"
                                   id="mainImageInput"
                                   onchange="handleMainImageSelect(this.files)"/>

                            
                            <div class="dz-placeholder" id="mainDzPlaceholder"
                                 style="<?php echo e($tour->image ? 'display:none' : ''); ?>">
                                <div class="dz-placeholder-icon">🖼️</div>
                                <div class="dz-placeholder-label">
                                    <strong>Выберите фото</strong> или перетащите<br>сюда главное изображение
                                </div>
                                <div class="dz-placeholder-hint">JPG · PNG · WEBP — 4K без ограничений</div>
                            </div>

                            
                            <div id="mainImagePreviewWrap"
                                 style="<?php echo e($tour->image ? '' : 'display:none'); ?>">
                                <img id="mainImagePreview"
                                     src="<?php echo e($tour->image ? asset('storage/' . $tour->image) : ''); ?>"
                                     alt="Главное фото"/>
                                <div class="main-image-overlay">
                                    <span>Нажмите, чтобы заменить</span>
                                </div>
                                <div class="main-image-change-btn">✏️ Заменить</div>
                                <button type="button" class="main-image-remove-btn"
                                        onclick="clearMainImage(event)">✕</button>
                            </div>
                        </div>

                        
                        <input type="hidden" name="image_clear" id="imageClearInput" value="0"/>

                        <div style="font-size: 10px; color: var(--slate-soft); margin-top: 8px; text-align: center;">
                            Рекомендуется 16:9 · минимум 1280×720
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        /* ════════════════════════════════════════
           MAP
        ════════════════════════════════════════ */
        let map, markers = [], polyline;
        let pointCount = <?php echo e(count($tour->route_points ?? [])); ?>;

        const existingPoints = <?php echo json_encode(
            collect($tour->route_points ?? [])->map(fn($p) => ['lat' => $p['lat'], 'lng' => $p['lng']]), 512) ?>;

        document.addEventListener('DOMContentLoaded', function () {
            map = L.map('routeMap').setView([39.5, 46.3], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            existingPoints.forEach((p, i) => addMarkerToMap(p.lat, p.lng, i));
            updatePolyline();

            // Sync coordinate inputs → map for Blade-rendered points
            document.querySelectorAll('.point-lat, .point-lng').forEach(input => {
                input.addEventListener('change', () => {
                    updateMarkerFromInput(parseInt(input.dataset.index));
                });
            });

            map.on('click', function (e) {
                const idx = pointCount;
                addPointCard(e.latlng.lat, e.latlng.lng);
                addMarkerToMap(e.latlng.lat, e.latlng.lng, idx);
                updatePolyline();
            });
        });

        function addMarkerToMap(lat, lng, idx) {
            const colors = ['#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];
            const color  = idx === 0 ? '#10B981' : colors[idx % colors.length];

            const icon = L.divIcon({
                html: `<div style="width:28px;height:28px;background:${color};border:3px solid white;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.3);">${idx + 1}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14],
                className: ''
            });

            const marker = L.marker([lat, lng], { icon, draggable: true }).addTo(map);

            marker.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                const latInput = document.querySelector(`[name="route_points[${idx}][lat]"]`);
                const lngInput = document.querySelector(`[name="route_points[${idx}][lng]"]`);
                if (latInput) latInput.value = pos.lat.toFixed(7);
                if (lngInput) lngInput.value = pos.lng.toFixed(7);
                updatePolyline();
            });

            markers[idx] = marker;
        }

        function updatePolyline() {
            if (polyline) map.removeLayer(polyline);
            const latlngs = markers.filter(Boolean).map(m => m.getLatLng());
            if (latlngs.length > 1) {
                polyline = L.polyline(latlngs, { color: '#3B82F6', weight: 3, opacity: 0.7 }).addTo(map);
            }
        }

        function updateMarkerFromInput(idx) {
            const lat = parseFloat(document.querySelector(`[name="route_points[${idx}][lat]"]`)?.value);
            const lng = parseFloat(document.querySelector(`[name="route_points[${idx}][lng]"]`)?.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                if (markers[idx]) {
                    markers[idx].setLatLng([lat, lng]);
                } else {
                    addMarkerToMap(lat, lng, idx);
                }
                updatePolyline();
            }
        }

        /* ════════════════════════════════════════
           ROUTE POINT CARDS
        ════════════════════════════════════════ */
        function addPoint() {
            addPointCard(0, 0);
        }

        function addPointCard(lat, lng) {
            const idx = pointCount;
            const html = `
                <div class="point-card" id="point-${idx}">
                    <div class="point-card-header">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="point-number">${idx + 1}</div>
                            <span style="font-size:13px;font-weight:600;">Կետ ${idx + 1}</span>
                        </div>
                        <button type="button" class="btn btn-danger btn-xs" onclick="removePoint(${idx})">✕ Հեռ.</button>
                    </div>

                    <div class="form-row col-2" style="margin-bottom:12px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Lat</label>
                            <input type="number" step="any" name="route_points[${idx}][lat]"
                                   value="${lat.toFixed ? lat.toFixed(7) : lat}"
                                   class="point-lat" data-index="${idx}"
                                   onchange="updateMarkerFromInput(${idx})"/>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Lng</label>
                            <input type="number" step="any" name="route_points[${idx}][lng]"
                                   value="${lng.toFixed ? lng.toFixed(7) : lng}"
                                   class="point-lng" data-index="${idx}"
                                   onchange="updateMarkerFromInput(${idx})"/>
                        </div>
                    </div>

                    <div class="form-row col-3" style="margin-bottom:12px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Հայ.</label>
                            <input type="text" name="route_points[${idx}][label][hy]" placeholder="Հայ."/>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Рус.</label>
                            <input type="text" name="route_points[${idx}][label][ru]" placeholder="Рус."/>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Eng.</label>
                            <input type="text" name="route_points[${idx}][label][en]" placeholder="Eng."/>
                        </div>
                    </div>

                    <input type="hidden" name="route_points[${idx}][media_existing]" value="[]" class="media-existing-input"/>

                    <div class="drop-zone" id="dz-${idx}"
                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                         ondragleave="this.classList.remove('drag-over')"
                         ondrop="handleDrop(event,${idx})">
                        <input type="file" name="route_points[${idx}][media_new][]"
                               multiple accept="image/*,video/*"
                               onchange="handleFileSelect(${idx}, this.files)"/>
                        <div class="drop-zone-icon">☁️</div>
                        <div class="drop-zone-label"><strong>Выберите файлы</strong> или перетащите сюда</div>
                        <div class="drop-zone-hint">JPG · PNG · WEBP · MP4 · MOV — 4K поддерживается</div>
                    </div>
                    <div class="preview-grid" id="new-preview-${idx}"></div>
                </div>
            `;
            document.getElementById('pointsList').insertAdjacentHTML('beforeend', html);
            pointCount++;
        }

        function removePoint(idx) {
            const card = document.getElementById('point-' + idx);
            if (card) card.remove();
            if (markers[idx]) {
                map.removeLayer(markers[idx]);
                markers[idx] = null;
            }
            updatePolyline();
        }

        /* ════════════════════════════════════════
           ROUTE POINT MEDIA — drag & drop + preview
        ════════════════════════════════════════ */
        const newFilesMap = {};

        function handleFileSelect(idx, files) {
            if (!newFilesMap[idx]) newFilesMap[idx] = [];
            Array.from(files).forEach(f => {
                newFilesMap[idx].push(f);
                renderNewPreview(idx, f, newFilesMap[idx].length - 1);
            });
        }

        function handleDrop(e, idx) {
            e.preventDefault();
            document.getElementById('dz-' + idx)?.classList.remove('drag-over');
            handleFileSelect(idx, e.dataTransfer.files);
        }

        function renderNewPreview(idx, file, fi) {
            const grid = document.getElementById('new-preview-' + idx);
            if (!grid) return;
            const url     = URL.createObjectURL(file);
            const isVideo = file.type.startsWith('video/');
            const item    = document.createElement('div');
            item.className = 'preview-item';
            item.id = `np-${idx}-${fi}`;
            item.innerHTML = `
                ${isVideo
                ? `<video src="${url}" muted playsinline></video>`
                : `<img src="${url}" alt="${escapeHtml(file.name)}">`}
                <button type="button" class="preview-delete" onclick="removeNewFile(${idx},${fi})">✕</button>
                <div class="preview-badge">${escapeHtml(file.name)}</div>
            `;
            grid.appendChild(item);
        }

        function removeNewFile(idx, fi) {
            if (newFilesMap[idx]) newFilesMap[idx][fi] = null;
            document.getElementById(`np-${idx}-${fi}`)?.remove();
        }

        function deleteMedia(pointIdx, path, btn) {
            const item  = btn.closest('.preview-item');
            const input = item.querySelector('.media-delete-input');
            if (input) input.disabled = false;
            item.style.opacity = '0.3';
            btn.disabled = true;
        }

        /* ════════════════════════════════════════
           MAIN IMAGE — drag & drop + 4K preview
        ════════════════════════════════════════ */
        function handleMainImageSelect(files) {
            if (!files || !files[0]) return;
            showMainImagePreview(files[0]);
        }

        function handleMainImageDrop(e) {
            e.preventDefault();
            document.getElementById('mainDz').classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            // Put the dropped file into the real input via DataTransfer
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('mainImageInput').files = dt.files;

            showMainImagePreview(file);
        }

        function showMainImagePreview(file) {
            const url = URL.createObjectURL(file);
            document.getElementById('mainImagePreview').src = url;
            document.getElementById('mainImagePreviewWrap').style.display = '';
            document.getElementById('mainDzPlaceholder').style.display   = 'none';
            document.getElementById('imageClearInput').value = '0';
        }

        function clearMainImage(e) {
            e.stopPropagation();
            document.getElementById('mainImagePreviewWrap').style.display = 'none';
            document.getElementById('mainDzPlaceholder').style.display   = '';
            document.getElementById('mainImageInput').value              = '';
            document.getElementById('mainImagePreview').src              = '';
            document.getElementById('imageClearInput').value             = '1';
        }

        /* ════════════════════════════════════════
           UTILS
        ════════════════════════════════════════ */
        function escapeHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/tours/form.blade.php ENDPATH**/ ?>