
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num"><?php echo e($stats['bookings_new']); ?></div>
            <div class="lbl">Новых заявок</div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['bookings_total']); ?></div>
            <div class="lbl">Всего заявок</div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['tours_active']); ?></div>
            <div class="lbl">Активных туров</div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['gallery_total']); ?></div>
            <div class="lbl">Фото в галерее</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Последние заявки</h3>
            <a href="<?php echo e(route('admin.booking.index')); ?>" class="btn btn-forest btn-sm">Все заявки</a>
        </div>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Тур</th>
                <th>Дата</th>
                <th>Статус</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:rgba(237,242,232,0.3);">#<?php echo e($b->id); ?></td>
                    <td><?php echo e($b->name); ?></td>
                    <td><?php echo e($b->phone); ?></td>
                    <td><?php echo e($b->tour?->getName('ru') ?? '—'); ?></td>
                    <td><?php echo e($b->date?->format('d.m.Y') ?? '—'); ?></td>
                    <td><span class="badge badge-<?php echo e($b->statusColor()); ?>"><?php echo e($b->statusLabel()); ?></span></td>
                    <td><a href="<?php echo e(route('admin.booking.show', $b)); ?>" class="btn btn-forest btn-sm">Открыть</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center; color:rgba(237,242,232,0.3); padding:32px;">Заявок пока нет</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>