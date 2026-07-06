@extends('layouts.admin')
@section('title', $quad->exists ? 'Изменить квадроцикл' : 'Новый квадроцикл')

@section('content')
    <div class="card" style="max-width:520px;">
        <div class="card-header">
            <h3>{{ $quad->exists ? '✏️ Изменить квадроцикл' : '➕ Новый квадроцикл' }}</h3>
        </div>

        <form action="{{ $quad->exists ? route('admin.quads.update', $quad) : route('admin.quads.store') }}"
              method="POST" enctype="multipart/form-data" style="padding:20px; display:flex; flex-direction:column; gap:16px;">
            @csrf
            @if($quad->exists) @method('PUT') @endif

            <div>
                <label style="display:block; margin-bottom:6px; font-size:13px; color:rgba(237,242,232,0.6);">Название</label>
                <input type="text" name="name" value="{{ old('name', $quad->name) }}"
                       style="width:100%; padding:10px 12px; border-radius:6px; background:rgba(237,242,232,0.06); border:1px solid rgba(237,242,232,0.12); color:inherit;">
                @error('name')<div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; margin-bottom:6px; font-size:13px; color:rgba(237,242,232,0.6);">Фото</label>
                @if($quad->image_url)
                    <img src="{{ $quad->image_url }}" style="width:100px; height:100px; object-fit:cover; border-radius:8px; margin-bottom:8px; display:block;">
                @endif
                <input type="file" name="image" accept="image/*"
                       style="width:100%; padding:8px 0; color:inherit;">
                @error('image')<div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <label style="display:flex; align-items:center; gap:8px; font-size:14px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quad->is_active ?? true) ? 'checked' : '' }}>
                Активен (доступен для бронирования)
            </label>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-forest btn-sm">Сохранить</button>
                <a href="{{ route('admin.quads.index') }}" class="btn btn-sm" style="background:rgba(237,242,232,0.08); color:inherit;">Отмена</a>
            </div>
        </form>
    </div>
@endsection
