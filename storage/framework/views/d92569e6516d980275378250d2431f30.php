<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('login.title')); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Inter, system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #f0f4ee 0%, #e8f0e4 50%, #f5f0ea 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-mountains {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .sun {
            position: fixed;
            top: 60px; right: 100px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,107,0,0.12) 0%, rgba(255,107,0,0) 70%);
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
            margin: 24px;
            background: #ffffff;
            border-radius: 16px;
            border: 0.5px solid rgba(74,124,64,0.2);
            padding: 40px 36px 36px;
            box-shadow: 0 4px 40px rgba(26,46,22,0.08), 0 1px 4px rgba(26,46,22,0.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 42px; height: 42px;
            background: #1a2e16;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .brand-text-top {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #1a2e16;
            line-height: 1.2;
        }

        .brand-text-sub {
            font-size: 10px;
            letter-spacing: 0.08em;
            color: #7fa46e;
            font-weight: 600;
        }

        .divider {
            height: 0.5px;
            background: rgba(74,124,64,0.15);
            margin-bottom: 28px;
        }

        .access-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f7ee;
            border: 0.5px solid #cdddc8;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            color: #3d6b34;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .access-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #4a7c40;
        }

        .heading { font-size: 22px; font-weight: 600; color: #1a2e16; margin-bottom: 4px; }
        .subheading { font-size: 13px; color: #6b7f64; margin-bottom: 28px; }

        .error-box {
            background: #fef2f2;
            border: 0.5px solid #fecaca;
            color: #b91c1c;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #6b7f64;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 13px;
            border: 0.5px solid #cdddc8;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #1a2e16;
            background: #f9fcf7;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            margin-bottom: 16px;
        }

        input:focus {
            border-color: #4a7c40;
            box-shadow: 0 0 0 3px rgba(74,124,64,0.1);
            background: #fff;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #1a2e16;
            color: #edf2e8;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.06em;
            cursor: pointer;
            transition: background .15s;
            margin-top: 4px;
            font-family: inherit;
        }

        .btn:hover { background: #2d4a24; }
        .btn:active { transform: scale(0.99); }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #9ab88a;
        }
    </style>
</head>
<body>

<svg class="bg-mountains" viewBox="0 0 1200 800" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
    <path fill="#2d5a27" opacity="0.06"
          d="M0,800 L0,450 L200,250 L380,360 L560,160 L740,300 L920,140 L1100,260 L1200,200 L1200,800Z"/>
    <path fill="#1a3a16" opacity="0.04"
          d="M0,800 L0,550 L250,420 L450,490 L650,360 L850,440 L1050,380 L1200,410 L1200,800Z"/>
</svg>
<div class="sun"></div>

<div class="card">
    <div class="brand">
        <div class="brand-icon">🏔️</div>
        <div>
            <div class="brand-text-top">MEGHRADZOR</div>
            <div class="brand-text-sub">QUAD ADVENTURE</div>
        </div>
    </div>
    <div class="divider"></div>

    <div class="access-badge">
        <span class="access-badge-dot"></span>
        <?php echo e(__('login.badge')); ?>

    </div>

    <h1 class="heading"><?php echo e(__('login.heading')); ?></h1>
    <p class="subheading"><?php echo e(__('login.subheading')); ?></p>

    <?php if($errors->any()): ?>
        <div class="error-box">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r=".5" fill="currentColor"/>
            </svg>
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.login')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label><?php echo e(__('login.email')); ?></label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
               placeholder="<?php echo e(__('login.email_ph')); ?>" autofocus required />

        <label><?php echo e(__('login.password')); ?></label>
        <input type="password" name="password"
               placeholder="<?php echo e(__('login.password_ph')); ?>" required />

        <button type="submit" class="btn"><?php echo e(__('login.submit')); ?></button>
    </form>

    <p class="footer-note"><?php echo e(__('login.footer', ['year' => date('Y')])); ?></p>
</div>

</body>
</html>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>