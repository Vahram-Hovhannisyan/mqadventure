
<?php $__env->startSection('title', 'Настройки страниц'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* ── Layout ── */
        .pg-wrap { display:grid; grid-template-columns:210px 1fr; gap:24px; align-items:start; }

        /* ── Sidebar ── */
        .pg-nav { position:sticky; top:72px; background:var(--surface); border:1px solid var(--border); border-radius:10px; overflow:hidden; }
        .pg-nav-item { display:flex; align-items:center; gap:9px; padding:10px 14px; font-size:13px; font-weight:500; color:var(--slate-mid); border-left:2px solid transparent; text-decoration:none; transition:all .15s; }
        .pg-nav-item:hover { color:var(--slate); background:var(--bg); }
        .pg-nav-item.active { color:var(--blue); background:var(--blue-light); border-left-color:var(--blue); font-weight:600; }
        .pg-nav-icon { font-size:14px; width:17px; text-align:center; flex-shrink:0; }

        /* ── Section card ── */
        .pg-section { background:var(--surface); border:1px solid var(--border); border-radius:10px; overflow:hidden; margin-bottom:20px; scroll-margin-top:72px; }
        .pg-section-head { padding:13px 20px; border-bottom:1px solid var(--border); background:var(--bg); display:flex; align-items:center; gap:10px; }
        .pg-section-head h3 { font-size:13px; font-weight:700; color:var(--slate); }
        .pg-section-head p { font-size:11px; color:var(--slate-soft); margin-top:1px; }
        .pg-section-body { padding:20px; display:flex; flex-direction:column; gap:16px; }

        /* ── Fields ── */
        .pg-row { display:grid; gap:14px; }
        .pg-row.col-2 { grid-template-columns:1fr 1fr; }
        .pg-row.col-3 { grid-template-columns:1fr 1fr 1fr; }

        .pg-field { display:flex; flex-direction:column; gap:5px; }
        .pg-label { font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:var(--slate-soft); }
        .pg-hint { font-size:11px; color:var(--slate-soft); margin-top:3px; }

        .pg-field input[type=text],
        .pg-field input[type=email],
        .pg-field input[type=url],
        .pg-field input[type=number],
        .pg-field textarea {
            background:var(--bg); border:1.5px solid var(--border); color:var(--slate);
            padding:8px 12px; border-radius:7px; font-family:inherit; font-size:13px;
            outline:none; transition:border-color .2s,box-shadow .2s; width:100%;
        }
        .pg-field input:focus,
        .pg-field textarea:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.08); background:var(--surface); }
        .pg-field textarea { resize:vertical; min-height:68px; line-height:1.5; }

        /* ── Lang tabs ── */
        .lang-group { display:flex; flex-direction:column; }
        .lang-tabs { display:flex; border-bottom:1.5px solid var(--border); }
        .lang-tab { padding:4px 12px; font-size:11px; font-weight:700; letter-spacing:.06em; cursor:pointer; border:1.5px solid transparent; border-bottom:none; border-radius:5px 5px 0 0; background:transparent; color:var(--slate-soft); transition:all .15s; margin-bottom:-1.5px; }
        .lang-tab:hover { color:var(--slate-mid); }
        .lang-tab.active { background:var(--surface); color:var(--blue); border-color:var(--border); border-bottom-color:var(--surface); }
        .lang-panel { display:none; }
        .lang-panel.active { display:block; }
        .lang-panel input, .lang-panel textarea { border-radius:0 4px 4px 4px !important; }

        /* ── Image upload ── */
        .img-field { display:flex; gap:14px; align-items:flex-start; }

        .img-thumb {
            width:130px; height:86px; flex-shrink:0;
            border-radius:8px; border:1.5px solid var(--border);
            background:var(--bg); overflow:hidden;
            display:flex; align-items:center; justify-content:center;
            position:relative;
        }
        .img-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .img-thumb-empty { font-size:11px; color:var(--slate-soft); text-align:center; padding:8px; line-height:1.4; }

        .img-controls { flex:1; display:flex; flex-direction:column; gap:8px; }

        .img-file-btn {
            display:flex; align-items:center; justify-content:center; gap:7px;
            padding:9px 14px; border-radius:7px; font-size:12px; font-weight:600;
            cursor:pointer; border:1.5px dashed var(--border);
            background:var(--bg); color:var(--slate-mid);
            transition:border-color .2s, color .2s; text-align:center;
            position:relative; overflow:hidden;
        }
        .img-file-btn:hover { border-color:var(--blue); color:var(--blue); }
        .img-file-btn input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }

        .img-remove {
            font-size:11px; color:var(--red); background:var(--red-light);
            border:1px solid #FECACA; border-radius:5px; padding:4px 10px;
            cursor:pointer; font-weight:600; align-self:flex-start;
            transition:background .15s;
        }
        .img-remove:hover { background:#FEE2E2; }

        /* ── Stat group ── */
        .stat-group { background:var(--bg); border:1px solid var(--border); border-radius:8px; padding:14px 16px; }
        .stat-group-title { font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--slate-soft); margin-bottom:12px; }

        /* ── Save bar ── */
        .save-bar { position:sticky; bottom:0; background:var(--surface); border-top:1px solid var(--border); padding:14px 0; margin-top:4px; display:flex; align-items:center; justify-content:space-between; z-index:5; }
        .save-bar-hint { font-size:12px; color:var(--slate-soft); }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('admin.pages.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="pg-wrap">

            
            <nav class="pg-nav">
                <a href="#sec-general"  class="pg-nav-item active"><span class="pg-nav-icon">⚙️</span>  Общие</a>
                <a href="#sec-hero"     class="pg-nav-item"><span class="pg-nav-icon">🏔️</span>  Главный экран</a>
                <a href="#sec-about"    class="pg-nav-item"><span class="pg-nav-icon">📖</span>  О нас</a>
                <a href="#sec-contacts" class="pg-nav-item"><span class="pg-nav-icon">📞</span>  Контакты</a>
            </nav>

            <div>

                
                <div class="pg-section" id="sec-general">
                    <div class="pg-section-head">
                        <span>⚙️</span>
                        <div><h3>Общие</h3><p>Контактные данные и системные параметры</p></div>
                    </div>
                    <div class="pg-section-body">

                        <div class="pg-row col-2">
                            <div class="pg-field">
                                <label class="pg-label">Название сайта</label>
                                <input type="text" name="site_title"
                                       value="<?php echo e(old('site_title', $settings->get('site_title')?->value['value'] ?? '')); ?>"
                                       placeholder="Meghradzor Quad Adventure" />
                            </div>
                            <div class="pg-field">
                                <label class="pg-label">Email администратора</label>
                                <input type="email" name="admin_email"
                                       value="<?php echo e(old('admin_email', $settings->get('admin_email')?->value['value'] ?? '')); ?>"
                                       placeholder="admin@meghradzor.ru" />
                            </div>
                        </div>

                        <div class="pg-row col-3">
                            <div class="pg-field">
                                <label class="pg-label">Телефон</label>
                                <input type="text" name="phone"
                                       value="<?php echo e(old('phone', $settings->get('phone')?->value['value'] ?? '')); ?>"
                                       placeholder="+374 94 818 985" />
                            </div>
                            <div class="pg-field">
                                <label class="pg-label">WhatsApp (только цифры)</label>
                                <input type="text" name="whatsapp"
                                       value="<?php echo e(old('whatsapp', $settings->get('whatsapp')?->value['value'] ?? '')); ?>"
                                       placeholder="37494818985" />
                            </div>
                            <div class="pg-field">
                                <label class="pg-label">Email публичный</label>
                                <input type="email" name="email"
                                       value="<?php echo e(old('email', $settings->get('email')?->value['value'] ?? '')); ?>"
                                       placeholder="info@meghradzor.ru" />
                            </div>
                        </div>

                        <?php echo $__env->make('admin.pages._lang_field', [
                            'key'     => 'address',
                            'label'   => 'Адрес',
                            'type'    => 'input',
                            'setting' => $settings->get('address'),
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    </div>
                </div>

                <div class="stat-group">
                    <div class="stat-group-title">Социальные сети</div>
                    <div class="pg-row col-2">
                        <div class="pg-field">
                            <label class="pg-label">Instagram</label>
                            <input type="text" name="instagram"
                                   value="<?php echo e(old('instagram', \App\Models\SiteSetting::get('instagram')?->value['value'] ?? '')); ?>"
                                   placeholder="@username или https://instagram.com/..." />
                            <span class="pg-hint">Username или полная ссылка</span>
                        </div>
                        <div class="pg-field">
                            <label class="pg-label">Facebook</label>
                            <input type="text" name="facebook"
                                   value="<?php echo e(old('facebook', \App\Models\SiteSetting::get('facebook')?->value['value'] ?? '')); ?>"
                                   placeholder="pagename или https://facebook.com/..." />
                            <span class="pg-hint">Название страницы или полная ссылка</span>
                        </div>
                    </div>
                </div>


                
                <div class="pg-section" id="sec-hero">
                    <div class="pg-section-head">
                        <span>🏔️</span>
                        <div><h3>Главный экран</h3><p>Фон, заголовки и статистика</p></div>
                    </div>
                    <div class="pg-section-body">

                        
                        <?php echo $__env->make('admin.pages._img_field', [
                            'key'    => 'hero_bg_image',
                            'label'  => 'Фоновое изображение',
                            'hint'   => 'JPG/PNG/WebP, рекомендуется 1920×1080 px',
                            'current' => $settings->get('hero_bg_image')?->value['value'] ?? null,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('admin.pages._lang_field', [
                            'key'     => 'hero_eyebrow',
                            'label'   => 'Надпись над заголовком',
                            'type'    => 'input',
                            'setting' => $settings->get('hero_eyebrow'),
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('admin.pages._lang_field', [
                            'key'     => 'hero_desc',
                            'label'   => 'Описание',
                            'type'    => 'textarea',
                            'setting' => $settings->get('hero_desc'),
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <div class="stat-group">
                            <div class="stat-group-title">Блок статистики</div>
                            <div class="pg-row col-3">
                                <?php $__currentLoopData = [1, 2, 3]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div style="display:flex; flex-direction:column; gap:10px;">
                                        <div class="pg-field">
                                            <label class="pg-label">Цифра <?php echo e($n); ?></label>
                                            <input type="text" name="stat<?php echo e($n); ?>_num"
                                                   value="<?php echo e(old("stat{$n}_num", $settings->get("stat{$n}_num")?->value['value'] ?? '')); ?>"
                                                   placeholder="<?php echo e(['250+','12','6'][$n-1]); ?>" />
                                        </div>
                                        <?php echo $__env->make('admin.pages._lang_field', [
                                            'key'     => "stat{$n}_label",
                                            'label'   => "Подпись {$n}",
                                            'type'    => 'input',
                                            'setting' => $settings->get("stat{$n}_label"),
                                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="pg-section" id="sec-about">
                    <div class="pg-section-head">
                        <span>📖</span>
                        <div><h3>О нас</h3><p>Фото и текстовое содержимое блока</p></div>
                    </div>
                    <div class="pg-section-body">

                        
                        <?php echo $__env->make('admin.pages._img_field', [
                            'key'    => 'about_image',
                            'label'  => 'Фотография раздела',
                            'hint'   => 'JPG/PNG/WebP, рекомендуется 800×600 px',
                            'current' => $settings->get('about_image')?->value['value'] ?? null,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php $__currentLoopData = [
                            ['key' => 'about_tag',   'label' => 'Тег',       'type' => 'input'],
                            ['key' => 'about_title', 'label' => 'Заголовок', 'type' => 'input'],
                            ['key' => 'about_text1', 'label' => 'Текст 1',   'type' => 'textarea'],
                            ['key' => 'about_text2', 'label' => 'Текст 2',   'type' => 'textarea'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.pages._lang_field', [
                                'key'     => $f['key'],
                                'label'   => $f['label'],
                                'type'    => $f['type'],
                                'setting' => $settings->get($f['key']),
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>

                
                <div class="pg-section" id="sec-contacts">
                    <div class="pg-section-head">
                        <span>📞</span>
                        <div><h3>Контакты</h3><p>Заголовок, описание и карта</p></div>
                    </div>
                    <div class="pg-section-body">

                        <?php $__currentLoopData = [
                            ['key' => 'contact_title', 'label' => 'Заголовок раздела', 'type' => 'input'],
                            ['key' => 'contact_desc',  'label' => 'Описание',          'type' => 'textarea'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.pages._lang_field', [
                                'key'     => $f['key'],
                                'label'   => $f['label'],
                                'type'    => $f['type'],
                                'setting' => $settings->get($f['key']),
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="pg-field">
                            <label class="pg-label">URL карты (Google Maps embed)</label>
                            <input type="url" name="map_embed_url"
                                   value="<?php echo e(old('map_embed_url', $settings->get('map_embed_url')?->value['value'] ?? '')); ?>"
                                   placeholder="https://www.google.com/maps/embed?pb=..." />
                            <span class="pg-hint">Вставьте src из кода встраивания Google Maps</span>
                        </div>

                    </div>
                </div>

                
                <div class="save-bar">
                    <span class="save-bar-hint">Изменения применяются сразу после сохранения</span>
                    <button type="submit" class="btn btn-primary" style="padding:10px 28px;">Сохранить</button>
                </div>

            </div>
        </div>
    </form>

    <?php $__env->startPush('scripts'); ?>
        <script>
            /* Lang tabs */
            document.querySelectorAll('.lang-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const group = tab.closest('.lang-group');
                    group.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
                    group.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
                    tab.classList.add('active');
                    group.querySelector(`.lang-panel[data-lang="${tab.dataset.lang}"]`).classList.add('active');
                });
            });

            /* Image preview */
            function previewImg(input, key) {
                const file = input.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const thumb = document.getElementById('thumb-' + key);
                    thumb.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;display:block;">`;
                };
                reader.readAsDataURL(file);
            }

            /* Remove image */
            function removeImg(key) {
                document.getElementById('remove-' + key).value = '1';
                document.getElementById('thumb-' + key).innerHTML =
                    '<div class="img-thumb-empty">Нет фото</div>';
                document.getElementById('remove-btn-' + key).style.display = 'none';
            }

            /* Active sidebar on scroll */
            const sections = document.querySelectorAll('.pg-section');
            const navLinks  = document.querySelectorAll('.pg-nav-item');
            window.addEventListener('scroll', () => {
                let cur = '';
                sections.forEach(s => { if (window.scrollY >= s.offsetTop - 90) cur = s.id; });
                navLinks.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + cur));
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\OSPanel\home\mqadventure\resources\views/admin/pages/index.blade.php ENDPATH**/ ?>