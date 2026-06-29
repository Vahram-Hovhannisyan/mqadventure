@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num">{{ $stats['bookings_new'] }}</div>
            <div class="lbl">Новых заявок</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['bookings_total'] }}</div>
            <div class="lbl">Всего заявок</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['tours_active'] }}</div>
            <div class="lbl">Активных туров</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['gallery_total'] }}</div>
            <div class="lbl">Фото в галерее</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Последние заявки</h3>
            <a href="{{ route('admin.booking.index') }}" class="btn btn-forest btn-sm">Все заявки</a>
        </div>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Тур</th>
                <th>Дата</th>
                <th>Статус</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($recent as $b)
                <tr>
                    <td style="color:rgba(237,242,232,0.3);">#{{ $b->id }}</td>
                    <td>{{ $b->name }}</td>
                    <td>{{ $b->phone }}</td>
                    <td>{{ $b->tour?->getName('ru') ?? '—' }}</td>
                    <td>{{ $b->date?->format('d.m.Y') ?? '—' }}</td>
                    <td><span class="badge badge-{{ $b->statusColor() }}">{{ $b->statusLabel() }}</span></td>
                    <td><a href="{{ route('admin.booking.show', $b) }}" class="btn btn-forest btn-sm">Открыть</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center; color:rgba(237,242,232,0.3); padding:32px;">Заявок пока нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
