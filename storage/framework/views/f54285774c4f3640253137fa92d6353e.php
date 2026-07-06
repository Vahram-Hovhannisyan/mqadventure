<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Կառավարում — <?php echo $__env->yieldContent('title', 'Վահանակ'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css', 'resources/js/admin-layout.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">🏔️</div>
        <div class="sidebar-logo-text">
            ՄԵՂՐԱՁՈՐ
            <span>ԿԱՌԱՎԱՐՄԱՆ ՎԱՀԱՆԱԿ</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Ընդհանուր</div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <span class="icon">📊</span> Վահանակ
        </a>

        <div class="nav-section">Բովանդակություն</div>
        <a href="<?php echo e(route('admin.tours.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.tours*') ? 'active' : ''); ?>">
            <span class="icon">🏔️</span> Շրջայցեր
        </a>
        <a href="<?php echo e(route('admin.calendar.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('admin.calendar.*') ? 'active' : ''); ?>">
            <span class="icon">📅</span> Օրացույց
        </a>
        <a href="<?php echo e(route('admin.booking.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.booking*') ? 'active' : ''); ?>">
            <span class="icon">📋</span> Հայտեր
            <?php $newCount = \App\Models\Booking::where('status','new')->count(); ?>
            <?php if($newCount): ?>
                <span class="badge badge-blue" style="margin-left:auto; font-size:11px;"><?php echo e($newCount); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('admin.gallery.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.gallery*') ? 'active' : ''); ?>">
            <span class="icon">🖼️</span> Պատկերասրահ
        </a>
        <a href="<?php echo e(route('admin.pages.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.pages*') ? 'active' : ''); ?>">
            <span class="icon">⚙️</span> Կարգավորումներ
        </a>
        <a href="<?php echo e(route('admin.quads.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.quads.*') ? 'active' : ''); ?>">
            🏍️ Տեխնիկա
        </a>
    </nav>

    <div class="sidebar-footer">
        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit">
                <span>→</span> Դուրս գալ
            </button>
        </form>
    </div>
</aside>


<div class="sidebar-overlay"></div>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <button type="button" class="menu-toggle" aria-label="Բացել մենյուն">☰</button>
            <div class="topbar-title"><?php echo $__env->yieldContent('title', 'Վահանակ'); ?></div>
        </div>

        <div class="topbar-right">

            
            <a href="<?php echo e(url('/')); ?>" target="_blank" class="site-link">
                🌐 <span>Կայք</span> ↗
            </a>

            
            <div class="lang-switcher">
                <?php $__currentLoopData = ['hy' => 'ՀՅ', 'ru' => 'RU', 'en' => 'EN']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.lang', $locale)); ?>"
                       class="<?php echo e(app()->getLocale() === $locale ? 'active' : ''); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="topbar-user">
                <div class="topbar-avatar"><?php echo e(strtoupper(substr(auth('admin')->user()->name, 0, 1))); ?></div>
                <span class="topbar-user-name"><?php echo e(auth('admin')->user()->name); ?></span>
            </div>

        </div>
    </div>

    <div class="page-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success">✓ <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-error">
                ✕ <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($e); ?><br> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\OSPanel\home\mqadventure\resources\views/layouts/admin.blade.php ENDPATH**/ ?>