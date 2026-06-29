<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .wrap { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 6px; overflow: hidden; }
        .header { background: #FF6B00; color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 20px; margin: 0; }
        .body { padding: 28px 32px; }
        .row { display: flex; border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
        .label { color: #888; min-width: 140px; font-size: 13px; }
        .value { font-size: 13px; font-weight: 600; color: #222; }
        .footer { padding: 16px 32px; background: #f9f9f9; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Новая заявка #<?php echo e($booking->id); ?></h1>
        <p style="margin:4px 0 0; opacity:.8; font-size:14px;">Meghradzor Quad Adventure</p>
    </div>
    <div class="body">
        <div class="row"><span class="label">Имя</span><span class="value"><?php echo e($booking->name); ?></span></div>
        <div class="row"><span class="label">Телефон</span><span class="value"><?php echo e($booking->phone); ?></span></div>
        <div class="row"><span class="label">Тур</span><span class="value"><?php echo e($tour); ?></span></div>
        <div class="row"><span class="label">Дата</span><span class="value"><?php echo e($booking->date?->format('d.m.Y') ?? 'Не указана'); ?></span></div>
        <div class="row"><span class="label">Людей</span><span class="value"><?php echo e($booking->people); ?></span></div>
        <?php if($booking->comment): ?>
            <div class="row"><span class="label">Комментарий</span><span class="value"><?php echo e($booking->comment); ?></span></div>
        <?php endif; ?>
        <div class="row"><span class="label">Получена</span><span class="value"><?php echo e($booking->created_at->format('d.m.Y H:i')); ?></span></div>
        <p style="margin-top:24px;">
            <a href="<?php echo e(route('admin.booking.show', $booking)); ?>"
               style="background:#FF6B00; color:#fff; padding:10px 20px; border-radius:3px; text-decoration:none; font-size:13px; font-weight:700;">
                Открыть в панели →
            </a>
        </p>
    </div>
    <div class="footer">Это автоматическое письмо от Meghradzor Admin</div>
</div>
</body>
</html>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/emails/new-booking.blade.php ENDPATH**/ ?>