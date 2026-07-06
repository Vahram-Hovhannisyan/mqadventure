{{--
    Video upload field.
    Usage:
    @include('admin.pages._video_field', [
        'key'     => 'hero_bg_video',
        'label'   => 'Фоновое видео',
        'hint'    => 'MP4/WebM, до 30 сек, рекомендуется до 20 МБ',
        'current' => $settings->get('hero_bg_video')?->value['value'] ?? null,
    ])
--}}
<div class="pg-field">
    <label class="pg-label">{{ $label }}</label>

    <div class="img-field">
        <div class="img-thumb" id="thumb-{{ $key }}">
            @if($current)
                <video src="{{ asset('storage/' . $current) }}" muted loop style="width:100%;height:100%;object-fit:cover;display:block;"></video>
            @else
                <div class="img-thumb-empty">Нет видео</div>
            @endif
        </div>

        <div class="img-controls">
            <label class="img-file-btn">
                📹 {{ $current ? 'Заменить видео' : 'Загрузить видео' }}
                <input type="file" name="{{ $key }}_file" accept="video/mp4,video/webm"
                       onchange="previewVideo(this, '{{ $key }}')" />
            </label>

            @if($hint ?? null)
                <span class="pg-hint">{{ $hint }}</span>
            @endif

            <input type="hidden" name="{{ $key }}_remove" id="remove-{{ $key }}" value="0" />

            <button type="button" class="img-remove" id="remove-btn-{{ $key }}"
                    style="{{ $current ? '' : 'display:none;' }}"
                    onclick="removeMedia('{{ $key }}', 'video')">
                Удалить видео
            </button>
        </div>
    </div>
</div>
