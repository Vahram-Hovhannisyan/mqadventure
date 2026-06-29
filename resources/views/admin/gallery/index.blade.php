@extends('layouts.admin')
@section('title', 'Галерея')

@push('styles')
    @vite(['resources/css/gallery.css'])
@endpush

@section('content')

    {{-- Config for JS (URLs, CSRF) --}}
    <script>
        window.GalleryConfig = {
            orderUrl: "{{ route('admin.gallery.order') }}"
        };
    </script>

    {{-- ── Flash message ── --}}
    @if(session('success'))
        <div class="gallery-flash success">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="gallery-flash error">
            <span>✕</span> {{ $errors->first() }}
        </div>
    @endif

    {{-- ── Page header ── --}}
    <div class="gallery-page-header">
        <div>
            <h1 class="gallery-page-title">
                <span class="gallery-page-title-icon">🖼</span>
                Галерея
            </h1>
            <div class="gallery-page-meta">Управление фотографиями сайта</div>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="gallery-stats">
        <div class="gallery-stat-card">
            <div class="gallery-stat-label">Всего фото</div>
            <div class="gallery-stat-value">{{ $items->count() }}</div>
            <div class="gallery-stat-sub">во всех разделах</div>
        </div>
        @foreach($sections as $key => $label)
            @if($grouped->has($key))
                <div class="gallery-stat-card">
                    <div class="gallery-stat-label">{{ $label }}</div>
                    <div class="gallery-stat-value">{{ $grouped[$key]->count() }}</div>
                    <div class="gallery-stat-sub">фото</div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- ── Upload card ── --}}
    <div class="gallery-upload-card">
        <div class="gallery-upload-card-header">
            <h3>Загрузить фото</h3>
            <span class="gallery-upload-card-header-badge">4K поддерживается</span>
        </div>
        <div class="gallery-upload-card-body">
            <form action="{{ route('admin.gallery.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="uploadForm">
                @csrf

                {{-- Drop zone --}}
                <div class="gallery-drop-zone" id="galleryDropZone">
                    <input type="file"
                           name="photos[]"
                           id="photosInput"
                           multiple
                           accept="image/*" />

                    <div class="gallery-drop-zone-icon">☁️</div>
                    <div class="gallery-drop-zone-title">
                        <span>Выберите фото</span> или перетащите сюда
                    </div>
                    <div class="gallery-drop-zone-sub">
                        Можно загрузить несколько файлов сразу
                    </div>
                    <div class="gallery-drop-zone-formats">
                        <span class="gallery-format-badge">JPG</span>
                        <span class="gallery-format-badge">PNG</span>
                        <span class="gallery-format-badge">WEBP</span>
                        <span class="gallery-format-badge">HEIC</span>
                        <span class="gallery-format-badge">4K</span>
                    </div>
                </div>

                {{-- Staging banner --}}
                <div class="gallery-staging-banner" id="stagingBanner">
                    <span class="gallery-staging-banner-dot"></span>
                    <span class="gallery-staging-banner-text"></span>
                </div>

                {{-- Staging preview grid --}}
                <div class="gallery-staging-grid" id="stagingGrid"></div>

                {{-- Section + submit --}}
                <div class="gallery-upload-controls">
                    <div class="gallery-section-select-wrap">
                        <label for="sectionSelect">Раздел:</label>
                        <select name="section" id="sectionSelect">
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}"
                                    {{ (session('active_section') === $key || old('section') === $key) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="gallery-upload-btn"
                            id="uploadSubmitBtn"
                            disabled
                            style="opacity: 0.4;">
                        ↑ Загрузить фото
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ── Gallery card ── --}}
    <div class="gallery-main-card">

        {{-- Card header --}}
        <div class="gallery-main-card-header">
            <h3 class="gallery-main-card-title">
                Фотографии
                <span class="gallery-total-badge">{{ $items->count() }}</span>
            </h3>
            <span class="gallery-drag-hint">
            ⠿ Перетащите карточки для изменения порядка
        </span>
        </div>

        {{-- Section filter tabs --}}
        <div class="gallery-tabs-wrap">
            <div class="gallery-tabs">
                <button class="gallery-tab active" data-filter="all">
                    Все
                    <span class="gallery-tab-count">{{ $items->count() }}</span>
                </button>
                @foreach($sections as $key => $label)
                    @if($grouped->has($key))
                        <button class="gallery-tab" data-filter="{{ $key }}">
                            {{ $label }}
                            <span class="gallery-tab-count">{{ $grouped[$key]->count() }}</span>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Gallery body --}}
        <div class="gallery-main-card-body">

            @forelse($grouped as $sectionKey => $sectionItems)
                <div class="gallery-section-block" data-section="{{ $sectionKey }}">

                    {{-- Section heading --}}
                    <div class="gallery-section-heading">
                    <span class="gallery-section-heading-label">
                        {{ $sections[$sectionKey] ?? $sectionKey }}
                    </span>
                        <span class="gallery-section-heading-count">{{ $sectionItems->count() }} фото</span>
                        <div class="gallery-section-heading-line"></div>
                    </div>

                    {{-- Photo grid --}}
                    <div class="gallery-photo-grid" data-section="{{ $sectionKey }}">
                        @foreach($sectionItems as $item)
                            <div class="gallery-photo-card" data-id="{{ $item->id }}">

                                <div class="gallery-photo-img-wrap">
                                    <img src="{{ asset('storage/' . ($item->thumb_path ?: $item->path)) }}"
                                         loading="lazy"
                                         alt="" />
                                    <div class="gallery-photo-overlay"></div>
                                    <div class="gallery-drag-handle">⠿</div>
                                </div>

                                <div class="gallery-photo-footer">
                                <span class="gallery-photo-section-pill">
                                    {{ $sections[$item->section] ?? $item->section }}
                                </span>
                                    <span class="gallery-photo-order">#{{ $item->sort_order }}</span>
                                    <form action="{{ route('admin.gallery.destroy', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Удалить фото?')"
                                          style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="gallery-photo-delete-btn"
                                                title="Удалить">✕</button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="gallery-empty">
                    <div class="gallery-empty-icon">🖼</div>
                    <div class="gallery-empty-title">Галерея пуста</div>
                    <div class="gallery-empty-sub">Загрузите первые фото через форму выше</div>
                </div>
            @endforelse

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
   @vite(['resources/js/gallery.js'])
@endpush
