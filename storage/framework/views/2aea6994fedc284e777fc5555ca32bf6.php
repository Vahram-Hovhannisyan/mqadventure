
<?php $__env->startSection('title', 'Շրջայցեր'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div>
            <h2>🏔️ Շրջայցեր</h2>
            <p>Բոլոր շրջայցերի կառավարում</p>
        </div>
        <a href="<?php echo e(route('admin.tours.create')); ?>" class="btn btn-primary">+ Ավելացնել</a>
    </div>

    <div class="card">
        <?php if($tours->isEmpty()): ?>
            <div style="padding: 60px 20px; text-align: center; color: var(--slate-soft);">
                <div style="font-size: 32px; margin-bottom: 12px;">🏔️</div>
                <div style="font-weight: 600;">Շրջայցեր չկան</div>
                <a href="<?php echo e(route('admin.tours.create')); ?>" class="btn btn-primary" style="margin-top: 16px;">Ստեղծել առաջինը</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th style="width: 60px;">Նկ.</th>
                    <th>Անվ.</th>
                    <th>Slug</th>
                    <th>Գին</th>
                    <th>Տևողություն</th>
                    <th>Կետեր</th>
                    <th>Կարգ.</th>
                    <th>Ակտիվ</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $tours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php if($tour->image): ?>
                                <img src="<?php echo e(asset('storage/' . $tour->image)); ?>"
                                     style="width: 48px; height: 36px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 48px; height: 36px; background: var(--bg); border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🏔️</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600;"><?php echo e($tour->getName()); ?></div>
                            <div style="font-size: 11px; color: var(--slate-soft);"><?php echo e($tour->getBadge()); ?></div>
                        </td>
                        <td style="font-size: 12px; color: var(--slate-soft);"><?php echo e($tour->slug); ?></td>
                        <td style="font-weight: 600; color: var(--blue);"><?php echo e($tour->getPriceFormatted()); ?> AMD</td>
                        <td style="font-size: 13px;"><?php echo e($tour->getDuration()); ?> ժ.</td>
                        <td>
                            <span class="badge badge-blue"><?php echo e(count($tour->route_points ?? [])); ?> կետ</span>
                        </td>
                        <td style="font-size: 13px; color: var(--slate-soft);"><?php echo e($tour->sort_order); ?></td>
                        <td>
                            <?php if($tour->is_active): ?>
                                <span class="badge badge-green">Ակտ.</span>
                            <?php else: ?>
                                <span class="badge badge-gray">Անջատ.</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="<?php echo e(route('admin.tours.edit', $tour)); ?>" class="btn btn-ghost btn-xs">✏️ Խմբ.</a>
                                <form method="POST" action="<?php echo e(route('admin.tours.destroy', $tour)); ?>"
                                      onsubmit="return confirm('Ջնջե՞լ շրջայցը')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-xs">✕</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/tours/index.blade.php ENDPATH**/ ?>