@extends('layouts.admin')
@section('title', 'Заявки')

@section('content')
    {{-- Filter --}}
    <div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
        @foreach(['', 'new', 'confirmed', 'cancelled', 'completed'] as $s)
            <a href="{{ route('admin.booking.index', $s ? ['status' => $s] : []) }}"
               class="btn {{ request('status') === $s || (empty($s) && !request('status')) ? 'btn-orange' : 'btn-forest' }} btn-sm">
                {{ match($s) { '' => 'Все', 'new' => 'Новые', 'confirmed' => 'Подтверждены', 'cancelled' => 'Отменены', 'completed' => 'Завершены', default => $s } }}
            </a>
        @endforeach
    </div>

    <div class="card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Тур</th>
                <th>Дата</th>
                <th>Кол-во</th>
                <th>Язык</th>
                <th>Статус</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($bookings as $b)
                <tr>
                    <td style="color:rgba(237,242,232,0.3);">#{{ $b->id }}</td>
                    <td>{{ $b->name }}</td>
                    <td>{{ $b->phone }}</td>
                    <td>{{ $b->tour?->getName('ru') ?? '—' }}</td>
                    <td>{{ $b->date?->format('d.m.Y') ?? '—' }}</td>
                    <td>{{ $b->people }}</td>
                    <td>{{ strtoupper($b->locale) }}</td>
                    <td><span class="badge badge-{{ $b->statusColor() }}">{{ $b->statusLabel() }}</span></td>
                    <td><a href="{{ route('admin.booking.show', $b) }}" class="btn btn-forest btn-sm">Открыть</a></td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center; color:rgba(237,242,232,0.3); padding:32px;">Заявок нет</td></tr>
            @endforelse
            </tbody>
        </table>
        @if($bookings->hasPages())
            <div style="padding:16px 20px; border-top:1px solid rgba(74,124,64,0.1);">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection
