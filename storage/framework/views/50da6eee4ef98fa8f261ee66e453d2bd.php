<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        h1   { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #888; font-size: 11px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th    { text-align: left; background: #f5f5f5; padding: 8px 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #666; }
        td    { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #e8f4fd; color: #1a6fa8; }
    </style>
</head>
<body>
<h1><?php echo e(__('booking.title', ['id' => $booking->id])); ?></h1>
<div class="meta"><?php echo e($booking->created_at->format('d.m.Y H:i')); ?></div>

<table>
    <tr><th colspan="2"><?php echo e(__('booking.client')); ?></th></tr>
    <tr><td><?php echo e(__('booking.name')); ?></td><td><strong><?php echo e($booking->name); ?></strong></td></tr>
    <tr><td><?php echo e(__('booking.phone')); ?></td><td><?php echo e($booking->phone); ?></td></tr>
    <?php if($booking->locale): ?>
        <tr><td><?php echo e(__('booking.language')); ?></td><td><?php echo e(strtoupper($booking->locale)); ?></td></tr>
    <?php endif; ?>
</table>

<table>
    <tr><th colspan="2"><?php echo e(__('booking.tour')); ?></th></tr>
    <tr><td><?php echo e(__('booking.tour')); ?></td><td><?php echo e($booking->tour?->getName() ?? __('booking.not_specified')); ?></td></tr>
    <tr><td><?php echo e(__('booking.date')); ?></td><td><?php echo e($booking->date?->format('d.m.Y') ?? __('booking.not_specified')); ?></td></tr>
    <tr><td><?php echo e(__('booking.people')); ?></td><td><?php echo e($booking->people ? $booking->people . ' ' . __('booking.people_unit') : __('booking.not_specified')); ?></td></tr>
    <?php if($booking->comment): ?>
        <tr><td><?php echo e(__('booking.comment')); ?></td><td><?php echo e($booking->comment); ?></td></tr>
    <?php endif; ?>
</table>

<table>
    <tr><th colspan="2"><?php echo e(__('booking.status')); ?></th></tr>
    <tr><td><?php echo e(__('booking.status')); ?></td><td><span class="badge"><?php echo e($booking->statusLabel()); ?></span></td></tr>
    <?php if($booking->tour): ?>
        <tr><td><?php echo e(__('booking.price')); ?></td><td><?php echo e($booking->tour->getPriceFormatted()); ?> AMD</td></tr>
    <?php endif; ?>
</table>
</body>
</html>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/bookings/pdf.blade.php ENDPATH**/ ?>