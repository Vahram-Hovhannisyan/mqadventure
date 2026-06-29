
<?php $__env->startSection('title', 'Галерея'); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/gallery.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <script>
        window.GalleryConfig = {
            orderUrl: "<?php echo e(route('admin.gallery.order')); ?>"
        };
    </script>

    
    <?php if(session('success')): ?>
        <div class="gallery-flash success">
            <span>✓</span> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="gallery-flash error">
            <span>✕</span> <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    
    <div class="gallery-page-header">
        <div>
            <h1 class="gallery-page-title">
                <span class="gallery-page-title-icon">🖼</span>
                Галерея
            </h1>
            <div class="gallery-page-meta">Управление фотографиями сайта</div>
        </div>
    </div>

    
    <div class="gallery-stats">
        <div class="gallery-stat-card">
            <div class="gallery-stat-label">Всего фото</div>
            <div class="gallery-stat-value"><?php echo e($items->count()); ?></div>
            <div class="gallery-stat-sub">во всех разделах</div>
        </div>
        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($grouped->has($key)): ?>
                <div class="gallery-stat-card">
                    <div class="gallery-stat-label"><?php echo e($label); ?></div>
                    <div class="gallery-stat-value"><?php echo e($grouped[$key]->count()); ?></div>
                    <div class="gallery-stat-sub">фото</div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="gallery-upload-card">
        <div class="gallery-upload-card-header">
            <h3>Загрузить фото</h3>
            <span class="gallery-upload-card-header-badge">4K поддерживается</span>
        </div>
        <div class="gallery-upload-card-body">
            <form action="<?php echo e(route('admin.gallery.store')); ?>"
                  method="POST"
                  enctype="multipart/form-data"
                  id="uploadForm">
                <?php echo csrf_field(); ?>

                
                <div class="gallery-drop-zone" id="galleryDropZone">
                    <input type="file"
                           name="photos[]"
                           id="photosInput"
                           multiple
                           accept="image/*" />

                    <div class="gallery-drop-zone-icon">☁️</div>
                    <div class="gallery-drop-zone-title">
                        <span>Выберите фото</span> или перетащите сюда
                    </div>
                    <div class="gallery-drop-zone-sub">
                        Можно загрузить несколько файлов сразу
                    </div>
                    <div class="gallery-drop-zone-formats">
                        <span class="gallery-format-badge">JPG</span>
                        <span class="gallery-format-badge">PNG</span>
                        <span class="gallery-format-badge">WEBP</span>
                        <span class="gallery-format-badge">HEIC</span>
                        <span class="gallery-format-badge">4K</span>
                    </div>
                </div>

                
                <div class="gallery-staging-banner" id="stagingBanner">
                    <span class="gallery-staging-banner-dot"></span>
                    <span class="gallery-staging-banner-text"></span>
                </div>

                
                <div class="gallery-staging-grid" id="stagingGrid"></div>

                
                <div class="gallery-upload-controls">
                    <div class="gallery-section-select-wrap">
                        <label for="sectionSelect">Раздел:</label>
                        <select name="section" id="sectionSelect">
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e((session('active_section') === $key || old('section') === $key) ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit"
                            class="gallery-upload-btn"
                            id="uploadSubmitBtn"
                            disabled
                            style="opacity: 0.4;">
                        ↑ Загрузить фото
                    </button>
                </div>

            </form>
        </div>
    </div>

    
    <div class="gallery-main-card">

        
        <div class="gallery-main-card-header">
            <h3 class="gallery-main-card-title">
                Фотографии
                <span class="gallery-total-badge"><?php echo e($items->count()); ?></span>
            </h3>
            <span class="gallery-drag-hint">
            ⠿ Перетащите карточки для изменения порядка
        </span>
        </div>

        
        <div class="gallery-tabs-wrap">
            <div class="gallery-tabs">
                <button class="gallery-tab active" data-filter="all">
                    Все
                    <span class="gallery-tab-count"><?php echo e($items->count()); ?></span>
                </button>
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($grouped->has($key)): ?>
                        <button class="gallery-tab" data-filter="<?php echo e($key); ?>">
                            <?php echo e($label); ?>

                            <span class="gallery-tab-count"><?php echo e($grouped[$key]->count()); ?></span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="gallery-main-card-body">

            <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionKey => $sectionItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="gallery-section-block" data-section="<?php echo e($sectionKey); ?>">

                    
                    <div class="gallery-section-heading">
                    <span class="gallery-section-heading-label">
                        <?php echo e($sections[$sectionKey] ?? $sectionKey); ?>

                    </span>
                        <span class="gallery-section-heading-count"><?php echo e($sectionItems->count()); ?> фото</span>
                        <div class="gallery-section-heading-line"></div>
                    </div>

                    
                    <div class="gallery-photo-grid" data-section="<?php echo e($sectionKey); ?>">
                        <?php $__currentLoopData = $sectionItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="gallery-photo-card" data-id="<?php echo e($item->id); ?>">

                                <div class="gallery-photo-img-wrap">
                                    <img src="<?php echo e(asset('storage/' . ($item->thumb_path ?: $item->path))); ?>"
                                         loading="lazy"
                                         alt="" />
                                    <div class="gallery-photo-overlay"></div>
                                    <div class="gallery-drag-handle">⠿</div>
                                </div>

                                <div class="gallery-photo-footer">
                                <span class="gallery-photo-section-pill">
                                    <?php echo e($sections[$item->section] ?? $item->section); ?>

                                </span>
                                    <span class="gallery-photo-order">#<?php echo e($item->sort_order); ?></span>
                                    <form action="<?php echo e(route('admin.gallery.destroy', $item)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Удалить фото?')"
                                          style="margin: 0;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="gallery-photo-delete-btn"
                                                title="Удалить">✕</button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="gallery-empty">
                    <div class="gallery-empty-icon">🖼</div>
                    <div class="gallery-empty-title">Галерея пуста</div>
                    <div class="gallery-empty-sub">Загрузите первые фото через форму выше</div>
                </div>
            <?php endif; ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
   <?php echo app('Illuminate\Foundation\Vite')(['resources/js/gallery.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/gallery/index.blade.php ENDPATH**/ ?>