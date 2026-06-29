

<div class="pg-field">
    <label class="pg-label"><?php echo e($label); ?></label>
    <div class="img-field">

        
        <div class="img-thumb" id="thumb-<?php echo e($key); ?>">
            <?php if($current): ?>
                <img src="<?php echo e(asset('storage/' . $current)); ?>" alt="<?php echo e($label); ?>" />
            <?php else: ?>
                <div class="img-thumb-empty">Нет<br>фото</div>
            <?php endif; ?>
        </div>

        
        <div class="img-controls">
            <label class="img-file-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Выбрать файл
                <input type="file"
                       name="<?php echo e($key); ?>_file"
                       accept="image/jpeg,image/png,image/webp"
                       onchange="previewImg(this, '<?php echo e($key); ?>')">
            </label>

            <?php if($current): ?>
                <button type="button"
                        class="img-remove"
                        id="remove-btn-<?php echo e($key); ?>"
                        onclick="removeImg('<?php echo e($key); ?>')">
                    Удалить фото
                </button>
            <?php else: ?>
                <button type="button"
                        class="img-remove"
                        id="remove-btn-<?php echo e($key); ?>"
                        onclick="removeImg('<?php echo e($key); ?>')"
                        style="display:none;">
                    Удалить фото
                </button>
            <?php endif; ?>

            <input type="hidden" name="<?php echo e($key); ?>_remove" id="remove-<?php echo e($key); ?>" value="0">

            <?php if($hint ?? null): ?>
                <span class="pg-hint"><?php echo e($hint); ?></span>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/pages/_img_field.blade.php ENDPATH**/ ?>