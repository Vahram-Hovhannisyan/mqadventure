{{-- admin/pages/_img_field.blade.php --}}
{{-- Variables: $key, $label, $hint, $current (path string or null) --}}
<div class="pg-field">
    <label class="pg-label">{{ $label }}</label>
    <div class="img-field">

        {{-- Thumbnail --}}
        <div class="img-thumb" id="thumb-{{ $key }}">
            @if($current)
                <img src="{{ asset('storage/' . $current) }}" alt="{{ $label }}" />
            @else
                <div class="img-thumb-empty">Нет<br>фото</div>
            @endif
        </div>

        {{-- Controls --}}
        <div class="img-controls">
            <label class="img-file-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Выбрать файл
                <input type="file"
                       name="{{ $key }}_file"
                       accept="image/jpeg,image/png,image/webp"
                       onchange="previewImg(this, '{{ $key }}')">
            </label>

            @if($current)
                <button type="button"
                        class="img-remove"
                        id="remove-btn-{{ $key }}"
                        onclick="removeImg('{{ $key }}')">
                    Удалить фото
                </button>
            @else
                <button type="button"
                        class="img-remove"
                        id="remove-btn-{{ $key }}"
                        onclick="removeImg('{{ $key }}')"
                        style="display:none;">
                    Удалить фото
                </button>
            @endif

            <input type="hidden" name="{{ $key }}_remove" id="remove-{{ $key }}" value="0">

            @if($hint ?? null)
                <span class="pg-hint">{{ $hint }}</span>
            @endif
        </div>

    </div>
</div>
