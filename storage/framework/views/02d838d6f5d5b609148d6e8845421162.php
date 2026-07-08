<?php $__env->startSection('title', __('admin.dashboard.title')); ?>
<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin-dashboard.css']); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num"><?php echo e($stats['bookings_new']); ?></div>
            <div class="lbl"><?php echo e(__('admin.dashboard.stats.new_bookings')); ?></div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['bookings_total']); ?></div>
            <div class="lbl"><?php echo e(__('admin.dashboard.stats.total_bookings')); ?></div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['tours_active']); ?></div>
            <div class="lbl"><?php echo e(__('admin.dashboard.stats.active_tours')); ?></div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($stats['gallery_total']); ?></div>
            <div class="lbl"><?php echo e(__('admin.dashboard.stats.gallery_photos')); ?></div>
        </div>
        <div class="stat-card">
            <div class="num"><?php echo e($quadsUsedTotal); ?>/<?php echo e($quadsCapacityTotal); ?></div>
            <div class="lbl"><?php echo e(__('admin.dashboard.stats.quads_busy')); ?></div>
        </div>
    </div>

    <div class="dash-grid-2col">

        
        <div class="card">
            <div class="card-header">
                <h3><?php echo e(__('admin.dashboard.today_schedule.title')); ?></h3>
                <a href="<?php echo e(route('admin.calendar.index')); ?>" class="btn btn-forest btn-sm">
                    <?php echo e(__('admin.dashboard.today_schedule.open_calendar')); ?>

                </a>
            </div>
            <?php if($today->isEmpty()): ?>
                <div class="dash-empty">
                    <?php echo e(__('admin.dashboard.today_schedule.no_bookings')); ?>

                </div>
            <?php else: ?>
                <div class="table-scroll">
                    <table>
                        <thead>
                        <tr>
                            <th><?php echo e(__('admin.dashboard.today_schedule.time')); ?></th>
                            <th><?php echo e(__('admin.dashboard.today_schedule.tour')); ?></th>
                            <th><?php echo e(__('admin.dashboard.today_schedule.client')); ?></th>
                            <th><?php echo e(__('admin.dashboard.today_schedule.people')); ?></th>
                            <th><?php echo e(__('admin.dashboard.today_schedule.quads')); ?></th>
                            <th><?php echo e(__('admin.dashboard.today_schedule.status')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $today; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="today-row" data-time="<?php echo e($b->time); ?>">
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.time')); ?>" class="text-strong">
                                    <?php echo e(substr($b->time, 0, 5)); ?>

                                </td>
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.tour')); ?>">
                                    <?php echo e($b->tour?->getName() ?? '—'); ?>

                                </td>
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.client')); ?>">
                                    <a href="<?php echo e(route('admin.booking.show', $b)); ?>" class="link-plain">
                                        <?php echo e($b->name); ?>

                                    </a>
                                </td>
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.people')); ?>"><?php echo e($b->people); ?></td>
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.quads')); ?>">
                                    <?php if($b->quads->isNotEmpty()): ?>
                                        <span title="<?php echo e($b->quads->pluck('name')->join(', ')); ?>">
                                            <?php echo e($b->quads->pluck('name')->join(', ')); ?>

                                        </span>
                                    <?php elseif($b->quads_used): ?>
                                        <?php echo e($b->quads_used); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td data-label="<?php echo e(__('admin.dashboard.today_schedule.status')); ?>">
                                    <span class="badge badge-<?php echo e($b->statusColor()); ?>"><?php echo e($b->statusLabel()); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="dash-right-col">

            
            <div class="card">
                <div class="card-header"><h3><?php echo e(__('admin.dashboard.quads_usage.title')); ?></h3></div>
                <?php if(empty($quadsUsage)): ?>
                    <div class="dash-empty">
                        <?php echo e(__('admin.dashboard.quads_usage.no_tours')); ?>

                    </div>
                <?php else: ?>
                    <div class="quads-usage-list">
                        <?php $__currentLoopData = $quadsUsage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $pct = $row['total'] > 0 ? round(($row['used'] / $row['total']) * 100) : 0;
                                $level = $pct >= 100 ? 'level-full' : ($pct >= 60 ? 'level-warn' : 'level-ok');
                            ?>
                            <div>
                                <div class="quads-usage-row-top">
                                    <span class="quads-usage-tour-name"><?php echo e($row['tour']->getName()); ?></span>
                                    <span class="quads-usage-count"><?php echo e($row['used']); ?>/<?php echo e($row['total']); ?></span>
                                </div>
                                <div class="quads-usage-bar-track">
                                    <div class="quads-usage-bar-fill <?php echo e($level); ?>" style="width:<?php echo e($pct); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h3><?php echo e(__('admin.dashboard.blocks.title')); ?></h3>
                    <a href="<?php echo e(route('admin.calendar.index')); ?>" class="btn btn-forest btn-sm">
                        <?php echo e(__('admin.dashboard.blocks.manage')); ?>

                    </a>
                </div>
                <?php if($upcomingBlocks->isEmpty()): ?>
                    <div class="dash-empty">
                        <?php echo e(__('admin.dashboard.blocks.none')); ?>

                    </div>
                <?php else: ?>
                    <div class="blocks-list">
                        <?php $__currentLoopData = $upcomingBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="blocks-row">
                                <div>
                                    <div class="blocks-date"><?php echo e(\Carbon\Carbon::parse($block->date)->format('d.m.Y')); ?></div>
                                    <div class="blocks-meta">
                                        <?php echo e($block->isFullDay() ? __('admin.dashboard.blocks.full_day') : substr($block->start_time, 0, 5) . '–' . substr($block->end_time, 0, 5)); ?>

                                        · <?php echo e($block->tour?->getName() ?? __('admin.dashboard.blocks.all_tours')); ?>

                                    </div>
                                </div>
                                <?php if($block->reason): ?>
                                    <span class="blocks-reason"><?php echo e($block->reason); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><?php echo e(__('admin.dashboard.recent.title')); ?></h3>
            <a href="<?php echo e(route('admin.booking.index')); ?>" class="btn btn-forest btn-sm">
                <?php echo e(__('admin.dashboard.recent.all')); ?>

            </a>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th><?php echo e(__('admin.dashboard.recent.id')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.name')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.phone')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.tour')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.date')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.vehicle')); ?></th>
                    <th><?php echo e(__('admin.dashboard.recent.status')); ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.id')); ?>" class="text-faint">#<?php echo e($b->id); ?></td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.name')); ?>"><?php echo e($b->name); ?></td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.phone')); ?>"><?php echo e($b->phone); ?></td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.tour')); ?>"><?php echo e($b->tour?->getName(app()->getLocale()) ?? '—'); ?></td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.date')); ?>"><?php echo e($b->date?->format('d.m.Y') ?? '—'); ?></td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.vehicle')); ?>">
                            <?php if($b->quads->isNotEmpty()): ?>
                                <?php echo e($b->quads->pluck('name')->join(', ')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo e(__('admin.dashboard.recent.status')); ?>">
                            <span class="badge badge-<?php echo e($b->statusColor()); ?>"><?php echo e($b->statusLabel()); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.booking.show', $b)); ?>" class="btn btn-forest btn-sm">
                                <?php echo e(__('admin.dashboard.recent.open')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="dash-empty">
                            <?php echo e(__('admin.dashboard.recent.none')); ?>

                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin-dashboard.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>