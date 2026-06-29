

<?php $__env->startSection('title', __('booking.title', ['id' => $booking->id])); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div>
            <h2>📋 <?php echo e(__('booking.title', ['id' => $booking->id])); ?></h2>
            <p><?php echo e($booking->created_at->format('d.m.Y H:i')); ?></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?php echo e(route('admin.booking.index')); ?>" class="btn btn-ghost btn-sm">← <?php echo e(__('booking.back')); ?></a>

            
            <a href="<?php echo e(route('admin.booking.pdf', $booking)); ?>" class="btn btn-secondary btn-sm" target="_blank">
                📄 <?php echo e(__('booking.export_pdf')); ?>

            </a>

            <form method="POST" action="<?php echo e(route('admin.booking.destroy', $booking)); ?>"
                  onsubmit="return confirm('<?php echo e(__('booking.confirm_delete')); ?>')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('booking.delete')); ?></button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">

        
        <div style="display: flex; flex-direction: column; gap: 20px;">

            
            <div class="card">
                <div class="card-header"><h3>👤 <?php echo e(__('booking.client')); ?></h3></div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.name')); ?></div>
                            <div style="font-weight: 600; font-size: 15px;"><?php echo e($booking->name); ?></div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.phone')); ?></div>
                            <a href="tel:<?php echo e($booking->phone); ?>" style="font-weight: 600; font-size: 15px; color: var(--blue);">
                                <?php echo e($booking->phone); ?>

                            </a>
                        </div>
                        <?php if($booking->locale): ?>
                            <div>
                                <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.language')); ?></div>
                                <div><?php echo e(strtoupper($booking->locale)); ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if($booking->ip): ?>
                            <div>
                                <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">IP</div>
                                <div style="font-size: 12px; color: var(--slate-soft);"><?php echo e($booking->ip); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header"><h3>🏔️ <?php echo e(__('booking.tour')); ?></h3></div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.tour')); ?></div>
                            <div style="font-weight: 600;">
                                <?php if($booking->tour): ?>
                                    <?php echo e($booking->tour->getName()); ?>

                                <?php else: ?>
                                    <span style="color: var(--slate-soft);"><?php echo e(__('booking.not_specified')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.date')); ?></div>
                            <div style="font-weight: 600;">
                                <?php if($booking->date): ?>
                                    <?php echo e($booking->date->format('d.m.Y')); ?>

                                    <div style="font-size: 11px; color: var(--slate-soft); font-weight: 400; margin-top: 2px;">
                                        <?php echo e($booking->date->locale(app()->getLocale())->isoFormat('dddd')); ?>

                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--slate-soft);"><?php echo e(__('booking.not_specified')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.people')); ?></div>
                            <div style="font-weight: 600;">
                                <?php if($booking->people): ?>
                                    <?php echo e($booking->people); ?> <?php echo e(__('booking.people_unit')); ?>

                                <?php else: ?>
                                    <span style="color: var(--slate-soft);"><?php echo e(__('booking.not_specified')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if($booking->comment): ?>
                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border);">
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.08em;"><?php echo e(__('booking.comment')); ?></div>
                            <div style="font-size: 13px; line-height: 1.6; color: var(--slate);"><?php echo e($booking->comment); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        
        <div>
            <div class="card">
                <div class="card-header"><h3><?php echo e(__('booking.status')); ?></h3></div>
                <div class="card-body">
                    <div style="margin-bottom: 16px;">
                        <span class="badge badge-<?php echo e($booking->statusColor()); ?>" style="font-size: 13px; padding: 6px 14px;">
                            <?php echo e($booking->statusLabel()); ?>

                        </span>
                    </div>

                    <form method="POST" action="<?php echo e(route('admin.booking.updateStatus', $booking)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label><?php echo e(__('booking.change_status')); ?></label>
                            <select name="status">
                                <?php $__currentLoopData = \App\Models\Booking::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status); ?>" <?php echo e($booking->status === $status ? 'selected' : ''); ?>>
                                        <?php echo e(match($status) {
                                            'new'       => '🔵 ' . __('booking.status_new'),
                                            'confirmed' => '🟢 ' . __('booking.status_confirmed'),
                                            'cancelled' => '🔴 ' . __('booking.status_cancelled'),
                                            'completed' => '⚪ ' . __('booking.status_completed'),
                                            default     => $status
                                        }); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <?php echo e(__('booking.save')); ?>

                        </button>
                    </form>
                </div>
            </div>

            
            <?php if($booking->tour): ?>
                <div class="card" style="margin-top: 16px;">
                    <div class="card-header"><h3>💰 <?php echo e(__('booking.price')); ?></h3></div>
                    <div class="card-body">
                        <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px;"><?php echo e(__('booking.price_from')); ?></div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--blue);">
                            <?php echo e($booking->tour->getPriceFormatted()); ?> AMD
                        </div>
                        <?php if($booking->people): ?>
                            <div style="font-size: 12px; color: var(--slate-soft); margin-top: 4px;">
                                × <?php echo e($booking->people); ?> <?php echo e(__('booking.people_unit')); ?> =
                                <strong style="color: var(--slate);">
                                    <?php echo e(number_format($booking->tour->price_from * $booking->people, 0, '.', ' ')); ?> AMD
                                </strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/bookings/show.blade.php ENDPATH**/ ?>