@extends('layouts.admin')

@section('title', __('booking.title', ['id' => $booking->id]))

@section('content')

    <div class="page-header">
        <div>
            <h2>📋 {{ __('booking.title', ['id' => $booking->id]) }}</h2>
            <p>{{ $booking->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.booking.index') }}" class="btn btn-ghost btn-sm">← {{ __('booking.back') }}</a>

            {{-- PDF Export --}}
            <a href="{{ route('admin.booking.pdf', $booking) }}" class="btn btn-secondary btn-sm" target="_blank">
                📄 {{ __('booking.export_pdf') }}
            </a>

            <form method="POST" action="{{ route('admin.booking.destroy', $booking) }}"
                  onsubmit="return confirm('{{ __('booking.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">{{ __('booking.delete') }}</button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">

        {{-- Main info --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Client --}}
            <div class="card">
                <div class="card-header"><h3>👤 {{ __('booking.client') }}</h3></div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.name') }}</div>
                            <div style="font-weight: 600; font-size: 15px;">{{ $booking->name }}</div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.phone') }}</div>
                            <a href="tel:{{ $booking->phone }}" style="font-weight: 600; font-size: 15px; color: var(--blue);">
                                {{ $booking->phone }}
                            </a>
                        </div>
                        @if($booking->locale)
                            <div>
                                <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.language') }}</div>
                                <div>{{ strtoupper($booking->locale) }}</div>
                            </div>
                        @endif
                        @if($booking->ip)
                            <div>
                                <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">IP</div>
                                <div style="font-size: 12px; color: var(--slate-soft);">{{ $booking->ip }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tour info --}}
            <div class="card">
                <div class="card-header"><h3>🏔️ {{ __('booking.tour') }}</h3></div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.tour') }}</div>
                            <div style="font-weight: 600;">
                                @if($booking->tour)
                                    {{ $booking->tour->getName() }}
                                @else
                                    <span style="color: var(--slate-soft);">{{ __('booking.not_specified') }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.date') }}</div>
                            <div style="font-weight: 600;">
                                @if($booking->date)
                                    {{ $booking->date->format('d.m.Y') }}
                                    <div style="font-size: 11px; color: var(--slate-soft); font-weight: 400; margin-top: 2px;">
                                        {{ $booking->date->locale(app()->getLocale())->isoFormat('dddd') }}
                                    </div>
                                @else
                                    <span style="color: var(--slate-soft);">{{ __('booking.not_specified') }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.people') }}</div>
                            <div style="font-weight: 600;">
                                @if($booking->people)
                                    {{ $booking->people }} {{ __('booking.people_unit') }}
                                @else
                                    <span style="color: var(--slate-soft);">{{ __('booking.not_specified') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($booking->comment)
                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border);">
                            <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.08em;">{{ __('booking.comment') }}</div>
                            <div style="font-size: 13px; line-height: 1.6; color: var(--slate);">{{ $booking->comment }}</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar: status --}}
        <div>
            <div class="card">
                <div class="card-header"><h3>{{ __('booking.status') }}</h3></div>
                <div class="card-body">
                    <div style="margin-bottom: 16px;">
                        <span class="badge badge-{{ $booking->statusColor() }}" style="font-size: 13px; padding: 6px 14px;">
                            {{ $booking->statusLabel() }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.booking.updateStatus', $booking) }}">
                        @csrf @method('PATCH')
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label>{{ __('booking.change_status') }}</label>
                            <select name="status">
                                @foreach(\App\Models\Booking::STATUSES as $status)
                                    <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>
                                        {{ match($status) {
                                            'new'       => '🔵 ' . __('booking.status_new'),
                                            'confirmed' => '🟢 ' . __('booking.status_confirmed'),
                                            'cancelled' => '🔴 ' . __('booking.status_cancelled'),
                                            'completed' => '⚪ ' . __('booking.status_completed'),
                                            default     => $status
                                        } }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            {{ __('booking.save') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tour price info --}}
            @if($booking->tour)
                <div class="card" style="margin-top: 16px;">
                    <div class="card-header"><h3>💰 {{ __('booking.price') }}</h3></div>
                    <div class="card-body">
                        <div style="font-size: 11px; color: var(--slate-soft); margin-bottom: 4px;">{{ __('booking.price_from') }}</div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--blue);">
                            {{ $booking->tour->getPriceFormatted() }} AMD
                        </div>
                        @if($booking->people)
                            <div style="font-size: 12px; color: var(--slate-soft); margin-top: 4px;">
                                × {{ $booking->people }} {{ __('booking.people_unit') }} =
                                <strong style="color: var(--slate);">
                                    {{ number_format($booking->tour->price_from * $booking->people, 0, '.', ' ') }} AMD
                                </strong>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
