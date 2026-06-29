
<?php $__env->startSection('title', 'Заявки'); ?>

<?php $__env->startSection('content'); ?>
    
    <div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
        <?php $__currentLoopData = ['', 'new', 'confirmed', 'cancelled', 'completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.booking.index', $s ? ['status' => $s] : [])); ?>"
               class="btn <?php echo e(request('status') === $s || (empty($s) && !request('status')) ? 'btn-orange' : 'btn-forest'); ?> btn-sm">
                <?php echo e(match($s) { '' => 'Все', 'new' => 'Новые', 'confirmed' => 'Подтверждены', 'cancelled' => 'Отменены', 'completed' => 'Завершены', default => $s }); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Тур</th>
                <th>Дата</th>
                <th>Кол-во</th>
                <th>Язык</th>
                <th>Статус</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:rgba(237,242,232,0.3);">#<?php echo e($b->id); ?></td>
                    <td><?php echo e($b->name); ?></td>
                    <td><?php echo e($b->phone); ?></td>
                    <td><?php echo e($b->tour?->getName('ru') ?? '—'); ?></td>
                    <td><?php echo e($b->date?->format('d.m.Y') ?? '—'); ?></td>
                    <td><?php echo e($b->people); ?></td>
                    <td><?php echo e(strtoupper($b->locale)); ?></td>
                    <td><span class="badge badge-<?php echo e($b->statusColor()); ?>"><?php echo e($b->statusLabel()); ?></span></td>
                    <td><a href="<?php echo e(route('admin.booking.show', $b)); ?>" class="btn btn-forest btn-sm">Открыть</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9" style="text-align:center; color:rgba(237,242,232,0.3); padding:32px;">Заявок нет</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php if($bookings->hasPages()): ?>
            <div style="padding:16px 20px; border-top:1px solid rgba(74,124,64,0.1);">
                <?php echo e($bookings->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>