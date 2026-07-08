@extends('layouts.admin')

@section('title', __('booking.title', ['id' => $booking->id]))

@push('styles')
    <style>
        /* ── Responsive layout for booking detail page ── */
        .booking-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .booking-header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .booking-detail-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
            align-items: start;
        }

        .info-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .info-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }
        .schedule-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 900px) {
            .booking-detail-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .booking-page-header {
                flex-direction: column;
                align-items: stretch;
            }
            .booking-header-actions {
                width: 100%;
            }
            .booking-header-actions a,
            .booking-header-actions form,
            .booking-header-actions button {
                flex: 1;
            }
            .booking-header-actions form {
                display: flex;
            }
            .booking-header-actions .btn {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            .info-grid-2,
            .info-grid-3,
            .schedule-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 420px) {
            .booking-page-header h2 {
                font-size: 17px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-header booking-page-header">
        <div>
            <h2>📋 {{ __('booking.title', ['id' => $booking->id]) }}</h2>
            <p>{{ $booking->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="booking-header-actions">
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

    @if($errors->any())
        <div class="card" style="border-color:#EF4444; margin-bottom:16px;">
            <div class="card-body" style="color:#EF4444; font-size:13px;">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="booking-detail-grid">

        {{-- Main info --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Client --}}
            <div class="card">
                <div class="card-header"><h3>👤 {{ __('booking.client') }}</h3></div>
                <div class="card-body">
                    <div class="info-grid-2">
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
                                <div style="font-size: 12px; color: var(--slate-soft); word-break: break-all;">{{ $booking->ip }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tour info --}}
            <div class="card">
                <div class="card-header"><h3>🏔️ {{ __('booking.tour') }}</h3></div>
                <div class="card-body">
                    <div class="info-grid-3">
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
                            <div style="font-size: 13px; line-height: 1.6; color: var(--slate); word-break: break-word;">{{ $booking->comment }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════
                 NEW: Schedule (date / time / duration / quads)
            ══════════════════════════════════════ --}}
            <div class="card">
                <div class="card-header"><h3>🕐 Расписание и квадроциклы</h3></div>
                <div class="card-body">

                    @if($booking->tour)
                        <div style="font-size:12px; color:var(--slate-soft); margin-bottom:16px; background:var(--bg); border:1px solid var(--border); border-radius:8px; padding:10px 12px;">
                            Тур «{{ $booking->tour->getName() }}»: всего квадроциклов —
                            <strong>{{ $booking->tour->quads_total }}</strong>,
                            мест на одном — <strong>{{ $booking->tour->seats_per_quad }}</strong>
                            (макс. вместимость: {{ $booking->tour->getMaxCapacity() }} чел.)
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.booking.updateSchedule', $booking) }}">
                        @csrf @method('PATCH')

                        <div class="form-row schedule-row">
                            <div class="form-group">
                                <label>Дата</label>
                                <input type="date" name="date"
                                       value="{{ old('date', $booking->date?->format('Y-m-d')) }}" required/>
                            </div>
                            <div class="form-group">
                                <label>Время начала</label>
                                <input type="time" name="time"
                                       value="{{ old('time', $booking->time ? substr($booking->time, 0, 5) : '') }}" required/>
                            </div>
                        </div>

                        <div class="form-row schedule-row" style="margin-top:14px;">
                            <div class="form-group">
                                <label>Людей</label>
                                <input type="number" name="people" min="1" max="20"
                                       value="{{ old('people', $booking->people) }}" required/>
                            </div>
                            <div class="form-group">
                                <label>Факт. длительность (ч)</label>
                                <input type="number" name="duration_hours" step="0.5" min="0.5"
                                       value="{{ old('duration_hours', $booking->duration_hours) }}"/>
                                <span style="font-size:11px; color:var(--slate-soft);">
                                    Пусто = использовать duration_max тура автоматически
                                </span>
                            </div>
                        </div>

                        <div style="margin-top:14px; font-size:12px; color:var(--slate-soft);">
                            Квадроциклов будет задействовано:
                            <strong style="color:var(--slate);">{{ $booking->quads_used ?? '—' }}</strong>
                            (пересчитывается автоматически при сохранении, исходя из количества людей)
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:16px;">
                            💾 Сохранить расписание
                        </button>
                    </form>
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
