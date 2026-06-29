

<div class="pg-field">
    <label class="pg-label"><?php echo e($label); ?></label>
    <div class="lang-group">
        <div class="lang-tabs">
            <?php $__currentLoopData = ['hy' => '🇦🇲 HY', 'ru' => '🇷🇺 RU', 'en' => '🇬🇧 EN']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="lang-tab <?php echo e($loop->first ? 'active' : ''); ?>" data-lang="<?php echo e($code); ?>"><?php echo e($flag); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php $__currentLoopData = ['hy', 'ru', 'en']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="lang-panel <?php echo e($loop->first ? 'active' : ''); ?>" data-lang="<?php echo e($code); ?>">
                <?php if(($type ?? 'input') === 'textarea'): ?>
                    <textarea name="<?php echo e($key); ?>[<?php echo e($code); ?>]"
                              rows="2"><?php echo e(old("{$key}.{$code}", $setting?->value[$code] ?? '')); ?></textarea>
                <?php else: ?>
                    <input type="text" name="<?php echo e($key); ?>[<?php echo e($code); ?>]"
                           value="<?php echo e(old("{$key}.{$code}", $setting?->value[$code] ?? '')); ?>" />
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/pages/_lang_field.blade.php ENDPATH**/ ?>