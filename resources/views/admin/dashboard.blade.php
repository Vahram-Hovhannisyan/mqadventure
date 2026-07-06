@extends('layouts.admin')
@section('title', __('admin.dashboard.title'))
@push('styles')
    @vite(['resources/css/admin-dashboard.css'])
@endpush
@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num">{{ $stats['bookings_new'] }}</div>
            <div class="lbl">{{ __('admin.dashboard.stats.new_bookings') }}</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['bookings_total'] }}</div>
            <div class="lbl">{{ __('admin.dashboard.stats.total_bookings') }}</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['tours_active'] }}</div>
            <div class="lbl">{{ __('admin.dashboard.stats.active_tours') }}</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $stats['gallery_total'] }}</div>
            <div class="lbl">{{ __('admin.dashboard.stats.gallery_photos') }}</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $quadsUsedTotal }}/{{ $quadsCapacityTotal }}</div>
            <div class="lbl">{{ __('admin.dashboard.stats.quads_busy') }}</div>
        </div>
    </div>

    <div class="dash-grid-2col">

        {{-- ── Today's schedule ── --}}
        <div class="card">
            <div class="card-header">
                <h3>{{ __('admin.dashboard.today_schedule.title') }}</h3>
                <a href="{{ route('admin.calendar.index') }}" class="btn btn-forest btn-sm">
                    {{ __('admin.dashboard.today_schedule.open_calendar') }}
                </a>
            </div>
            @if($today->isEmpty())
                <div class="dash-empty">
                    {{ __('admin.dashboard.today_schedule.no_bookings') }}
                </div>
            @else
                <div class="table-scroll">
                    <table>
                        <thead>
                        <tr>
                            <th>{{ __('admin.dashboard.today_schedule.time') }}</th>
                            <th>{{ __('admin.dashboard.today_schedule.tour') }}</th>
                            <th>{{ __('admin.dashboard.today_schedule.client') }}</th>
                            <th>{{ __('admin.dashboard.today_schedule.people') }}</th>
                            <th>{{ __('admin.dashboard.today_schedule.quads') }}</th>
                            <th>{{ __('admin.dashboard.today_schedule.status') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($today as $b)
                            <tr class="today-row" data-time="{{ $b->time }}">
                                <td data-label="{{ __('admin.dashboard.today_schedule.time') }}" class="text-strong">
                                    {{ substr($b->time, 0, 5) }}
                                </td>
                                <td data-label="{{ __('admin.dashboard.today_schedule.tour') }}">
                                    {{ $b->tour?->getName() ?? '—' }}
                                </td>
                                <td data-label="{{ __('admin.dashboard.today_schedule.client') }}">
                                    <a href="{{ route('admin.booking.show', $b) }}" class="link-plain">
                                        {{ $b->name }}
                                    </a>
                                </td>
                                <td data-label="{{ __('admin.dashboard.today_schedule.people') }}">{{ $b->people }}</td>
                                <td data-label="{{ __('admin.dashboard.today_schedule.quads') }}">
                                    @if($b->quads->isNotEmpty())
                                        <span title="{{ $b->quads->pluck('name')->join(', ') }}">
                                            {{ $b->quads->pluck('name')->join(', ') }}
                                        </span>
                                    @elseif($b->quads_used)
                                        {{ $b->quads_used }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="{{ __('admin.dashboard.today_schedule.status') }}">
                                    <span class="badge badge-{{ $b->statusColor() }}">{{ $b->statusLabel() }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── Right column: quads usage + upcoming blocks ── --}}
        <div class="dash-right-col">

            {{-- Quad usage per tour --}}
            <div class="card">
                <div class="card-header"><h3>{{ __('admin.dashboard.quads_usage.title') }}</h3></div>
                @if(empty($quadsUsage))
                    <div class="dash-empty">
                        {{ __('admin.dashboard.quads_usage.no_tours') }}
                    </div>
                @else
                    <div class="quads-usage-list">
                        @foreach($quadsUsage as $row)
                            @php
                                $pct = $row['total'] > 0 ? round(($row['used'] / $row['total']) * 100) : 0;
                                $level = $pct >= 100 ? 'level-full' : ($pct >= 60 ? 'level-warn' : 'level-ok');
                            @endphp
                            <div>
                                <div class="quads-usage-row-top">
                                    <span class="quads-usage-tour-name">{{ $row['tour']->getName() }}</span>
                                    <span class="quads-usage-count">{{ $row['used'] }}/{{ $row['total'] }}</span>
                                </div>
                                <div class="quads-usage-bar-track">
                                    <div class="quads-usage-bar-fill {{ $level }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Upcoming manual blocks --}}
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('admin.dashboard.blocks.title') }}</h3>
                    <a href="{{ route('admin.calendar.index') }}" class="btn btn-forest btn-sm">
                        {{ __('admin.dashboard.blocks.manage') }}
                    </a>
                </div>
                @if($upcomingBlocks->isEmpty())
                    <div class="dash-empty">
                        {{ __('admin.dashboard.blocks.none') }}
                    </div>
                @else
                    <div class="blocks-list">
                        @foreach($upcomingBlocks as $block)
                            <div class="blocks-row">
                                <div>
                                    <div class="blocks-date">{{ \Carbon\Carbon::parse($block->date)->format('d.m.Y') }}</div>
                                    <div class="blocks-meta">
                                        {{ $block->isFullDay() ? __('admin.dashboard.blocks.full_day') : substr($block->start_time, 0, 5) . '–' . substr($block->end_time, 0, 5) }}
                                        · {{ $block->tour?->getName() ?? __('admin.dashboard.blocks.all_tours') }}
                                    </div>
                                </div>
                                @if($block->reason)
                                    <span class="blocks-reason">{{ $block->reason }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>{{ __('admin.dashboard.recent.title') }}</h3>
            <a href="{{ route('admin.booking.index') }}" class="btn btn-forest btn-sm">
                {{ __('admin.dashboard.recent.all') }}
            </a>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>{{ __('admin.dashboard.recent.id') }}</th>
                    <th>{{ __('admin.dashboard.recent.name') }}</th>
                    <th>{{ __('admin.dashboard.recent.phone') }}</th>
                    <th>{{ __('admin.dashboard.recent.tour') }}</th>
                    <th>{{ __('admin.dashboard.recent.date') }}</th>
                    <th>{{ __('admin.dashboard.recent.vehicle') }}</th>
                    <th>{{ __('admin.dashboard.recent.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($recent as $b)
                    <tr>
                        <td data-label="{{ __('admin.dashboard.recent.id') }}" class="text-faint">#{{ $b->id }}</td>
                        <td data-label="{{ __('admin.dashboard.recent.name') }}">{{ $b->name }}</td>
                        <td data-label="{{ __('admin.dashboard.recent.phone') }}">{{ $b->phone }}</td>
                        <td data-label="{{ __('admin.dashboard.recent.tour') }}">{{ $b->tour?->getName(app()->getLocale()) ?? '—' }}</td>
                        <td data-label="{{ __('admin.dashboard.recent.date') }}">{{ $b->date?->format('d.m.Y') ?? '—' }}</td>
                        <td data-label="{{ __('admin.dashboard.recent.vehicle') }}">
                            @if($b->quads->isNotEmpty())
                                {{ $b->quads->pluck('name')->join(', ') }}
                            @else
                                —
                            @endif
                        </td>
                        <td data-label="{{ __('admin.dashboard.recent.status') }}">
                            <span class="badge badge-{{ $b->statusColor() }}">{{ $b->statusLabel() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.booking.show', $b) }}" class="btn btn-forest btn-sm">
                                {{ __('admin.dashboard.recent.open') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="dash-empty">
                            {{ __('admin.dashboard.recent.none') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection



@push('scripts')
    @vite(['resources/js/admin-dashboard.js'])
@endpush
