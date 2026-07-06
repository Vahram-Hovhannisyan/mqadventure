@extends('layouts.admin')
@section('title', 'Տեխնիկա')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>🏍️ Տեխնիկա</h3>
            <a href="{{ route('admin.quads.create') }}" class="btn btn-forest btn-sm">Добавить квадроцикл</a>
        </div>

        @if(session('success'))
            <div style="padding:12px 16px; color:#16a34a; font-size:13px;">{{ session('success') }}</div>
        @endif

        @if($quads->isEmpty())
            <div style="text-align:center; color:rgba(237,242,232,0.3); padding:32px;">
                Квадроциклов пока нет
            </div>
        @else
            <table>
                <thead>
                <tr>
                    <th style="width:70px;">Фото</th>
                    <th>Название</th>
                    <th>Статус</th>
                    <th style="width:160px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($quads as $quad)
                    <tr>
                        <td>
                            @if($quad->image_url)
                                <img src="{{ $quad->image_url }}" alt="{{ $quad->name }}" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                            @else
                                <div style="width:50px; height:50px; border-radius:6px; background:rgba(237,242,232,0.08); display:flex; align-items:center; justify-content:center; font-size:11px; color:rgba(237,242,232,0.3);">нет фото</div>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $quad->name }}</td>
                        <td>
                            @if($quad->is_active)
                                <span class="badge badge-green">Активен</span>
                            @else
                                <span class="badge badge-gray">Выключен</span>
                            @endif
                        </td>
                        <td style="display:flex; gap:8px;">
                            <a href="{{ route('admin.quads.edit', $quad) }}" class="btn btn-forest btn-sm">Изменить</a>
                            <form action="{{ route('admin.quads.destroy', $quad) }}" method="POST" onsubmit="return confirm('Удалить этот квадроцикл?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,0.15); color:#ef4444;">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
