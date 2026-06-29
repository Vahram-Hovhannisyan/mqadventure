@extends('layouts.admin')
@section('title', $tour->exists ? 'Редактировать тур' : 'Новый тур')

@section('content')
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.tours.index') }}" style="color:rgba(237,242,232,0.4); font-size:13px;">← Назад к турам</a>
    </div>

    <form action="{{ $tour->exists ? route('admin.tours.update', $tour) : route('admin.tours.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($tour->exists) @method('PUT') @endif

        <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px;">

            {{-- LEFT --}}
            <div>
                {{-- Name / Badge / Description with lang tabs --}}
                @foreach([
                  ['field' => 'name', 'label' => 'Название', 'type' => 'input'],
                  ['field' => 'badge', 'label' => 'Бейдж (напр. «Самый популярный»)', 'type' => 'input'],
                  ['field' => 'description', 'label' => 'Описание', 'type' => 'textarea'],
                ] as $f)
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header"><h3>{{ $f['label'] }}</h3></div>
                        <div class="card-body">
                            <div class="lang-group">
                                <div class="lang-tabs">
                                    @foreach(['hy' => '🇦🇲 Армянский', 'ru' => '🇷🇺 Русский', 'en' => '🇬🇧 English'] as $code => $label)
                                        <div class="lang-tab {{ $loop->first ? 'active' : '' }}" data-lang="{{ $code }}">{{ $label }}</div>
                                    @endforeach
                                </div>
                                @foreach(['hy', 'ru', 'en'] as $code)
                                    <div class="lang-panel {{ $loop->first ? 'active' : '' }}" data-lang="{{ $code }}">
                                        @if($f['type'] === 'textarea')
                                            <textarea name="{{ $f['field'] }}[{{ $code }}]" rows="4" style="width:100%; background:rgba(10,13,9,0.6); border:1px solid rgba(74,124,64,0.2); color:var(--light); padding:9px 12px; border-radius:0 3px 3px 3px; font-family:inherit; font-size:13px; outline:none; resize:vertical;">{{ old($f['field'] . '.' . $code, $tour->{$f['field']}[$code] ?? '') }}</textarea>
                                        @else
                                            <input type="text" name="{{ $f['field'] }}[{{ $code }}]"
                                                   value="{{ old($f['field'] . '.' . $code, $tour->{$f['field']}[$code] ?? '') }}"
                                                   style="width:100%; background:rgba(10,13,9,0.6); border:1px solid rgba(74,124,64,0.2); color:var(--light); padding:9px 12px; border-radius:0 3px 3px 3px; font-family:inherit; font-size:13px; outline:none;" />
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- RIGHT --}}
            <div>
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header"><h3>Параметры</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Slug (URL)</label>
                            <input type="text" name="slug" value="{{ old('slug', $tour->slug) }}" required />
                        </div>
                        <div class="form-group">
                            <label>Цвет бейджа</label>
                            <select name="badge_color">
                                <option value="orange" {{ old('badge_color', $tour->badge_color) === 'orange' ? 'selected' : '' }}>Оранжевый</option>
                                <option value="green"  {{ old('badge_color', $tour->badge_color) === 'green'  ? 'selected' : '' }}>Зелёный</option>
                            </select>
                        </div>
                        <div class="form-row col-2">
                            <div class="form-group">
                                <label>Часов мин</label>
                                <input type="number" name="duration_min" value="{{ old('duration_min', $tour->duration_min ?? 2) }}" min="1" required />
                            </div>
                            <div class="form-group">
                                <label>Часов макс</label>
                                <input type="number" name="duration_max" value="{{ old('duration_max', $tour->duration_max ?? 3) }}" min="1" required />
                            </div>
                        </div>
                        <div class="form-row col-2">
                            <div class="form-group">
                                <label>Людей мин</label>
                                <input type="number" name="people_min" value="{{ old('people_min', $tour->people_min ?? 1) }}" min="1" required />
                            </div>
                            <div class="form-group">
                                <label>Людей макс</label>
                                <input type="number" name="people_max" value="{{ old('people_max', $tour->people_max ?? 6) }}" min="1" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Цена от (AMD)</label>
                            <input type="number" name="price_from" value="{{ old('price_from', $tour->price_from ?? 8000) }}" min="0" required />
                        </div>
                        <div class="form-group">
                            <label>Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $tour->sort_order ?? 0) }}" />
                        </div>
                        <div class="form-group">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; text-transform:none; font-size:13px; color:var(--light);">
                                <input type="hidden" name="is_active" value="0" />
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tour->is_active ?? true) ? 'checked' : '' }} style="width:16px; height:16px;" />
                                Активен (виден на сайте)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header"><h3>Фото</h3></div>
                    <div class="card-body">
                        @if($tour->image)
                            <img src="{{ asset('storage/' . $tour->image) }}" style="width:100%; border-radius:4px; margin-bottom:12px;" />
                        @endif
                        <input type="file" name="image" accept="image/*" style="font-size:13px; color:var(--light);" />
                    </div>
                </div>

                <button type="submit" class="btn btn-orange" style="width:100%; justify-content:center; padding:12px;">
                    {{ $tour->exists ? 'Сохранить изменения' : 'Создать тур' }}
                </button>
            </div>
        </div>
    </form>
@endsection
