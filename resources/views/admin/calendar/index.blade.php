@extends('layouts.admin')
@section('title', 'Календарь бронирований')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <style>
        .cal-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; gap:12px; flex-wrap:wrap; }
        .cal-filter { display:flex; align-items:center; gap:10px; }
        .cal-filter select {
            background:var(--surface); border:1.5px solid var(--border); color:var(--slate);
            padding:8px 12px; border-radius:7px; font-size:13px; min-width:220px;
        }
        .cal-legend { display:flex; gap:16px; font-size:12px; color:var(--slate-mid); }
        .cal-legend span { display:inline-flex; align-items:center; gap:6px; }
        .cal-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }

        #calendar { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:16px; }

        /* ── Красивые кнопки FullCalendar ── */
        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }
        .fc .fc-toolbar-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--slate);
            text-transform: capitalize;
        }
        .fc .fc-button {
            background: var(--surface);
            border: 1.5px solid var(--border);
            color: var(--slate-mid, var(--slate));
            font-size: 13px;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 8px;
            text-transform: none;
            box-shadow: none;
            transition: all .15s ease;
        }
        .fc .fc-button:hover {
            background: rgba(74,124,64,0.08);
            border-color: #4A7C40;
            color: #4A7C40;
        }
        .fc .fc-button:focus,
        .fc .fc-button:active {
            box-shadow: 0 0 0 3px rgba(74,124,64,0.15) !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: #4A7C40;
            border-color: #4A7C40;
            color: #fff;
        }
        .fc .fc-button-primary:disabled {
            opacity: .45;
        }
        .fc .fc-today-button {
            background: rgba(74,124,64,0.1);
            border-color: rgba(74,124,64,0.3);
            color: #4A7C40;
            font-weight: 700;
        }
        .fc .fc-today-button:hover:not(:disabled) {
            background: #4A7C40;
            color: #fff;
        }
        .fc .fc-icon {
            font-size: 15px;
        }
        .fc .fc-button-group {
            gap: 4px;
        }
        .fc .fc-button-group .fc-button {
            border-radius: 8px !important;
        }
        .fc-prev-button, .fc-next-button {
            width: 34px;
            padding: 7px 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* мобильная адаптация тулбара */
        @media (max-width: 640px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
            }
            .fc .fc-toolbar-title {
                text-align: center;
                font-size: 15px;
            }
            .fc .fc-button {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        #calendar-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Modal */
        .cal-modal-overlay {
            display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); z-index:1000;
            align-items:center; justify-content:center;
        }
        .cal-modal-overlay.open { display:flex; }
        .cal-modal {
            background:var(--surface); border-radius:12px; padding:24px; width:100%; max-width:420px;
            display:flex; flex-direction:column; gap:14px;
        }
        .cal-modal h3 { font-size:15px; font-weight:700; color:var(--slate); }
        .cal-modal-field { display:flex; flex-direction:column; gap:5px; }
        .cal-modal-field label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--slate-soft); }
        .cal-modal-field input, .cal-modal-field select {
            background:var(--bg); border:1.5px solid var(--border); color:var(--slate);
            padding:8px 12px; border-radius:7px; font-size:13px;
        }
        .cal-modal-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .cal-modal-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:6px; }
        .cal-checkbox-row { display:flex; align-items:center; gap:8px; font-size:13px; color:var(--slate); }
    </style>
@endpush

@section('content')

    <div class="cal-toolbar">
        <div class="cal-filter">
            <label class="pg-label" style="margin:0;">Тур:</label>
            <select id="tourFilter">
                <option value="">Все туры</option>
                @foreach($tours as $tour)
                    <option value="{{ $tour->id }}">{{ $tour->getName() }} (кв: {{ $tour->quads_total }})</option>
                @endforeach
            </select>
        </div>

        <div class="cal-legend">
            <span><span class="cal-dot" style="background:#f59e0b;"></span> Новая заявка</span>
            <span><span class="cal-dot" style="background:#16a34a;"></span> Подтверждена</span>
            <span><span class="cal-dot" style="background:#6b7280;"></span> Завершена</span>
            <span><span class="cal-dot" style="background:#ef4444;"></span> Закрыто</span>
        </div>

        <button type="button" class="btn btn-primary" id="openBlockModalBtn" style="padding:9px 18px;">
            🚫 Закрыть время
        </button>
    </div>

    <div id="calendar-wrap">
        <div id="calendar"></div>
    </div>

    {{-- Block time modal --}}
    <div class="cal-modal-overlay" id="blockModalOverlay">
        <form class="cal-modal" id="blockForm" method="POST" action="{{ route('admin.calendar.block.store') }}">
            @csrf
            <h3>Закрыть время для бронирования</h3>

            <div class="cal-modal-field">
                <label>Тур</label>
                <select name="tour_id">
                    <option value="">Все туры (полное закрытие)</option>
                    @foreach($tours as $tour)
                        <option value="{{ $tour->id }}">{{ $tour->getName() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="cal-modal-field">
                <label>Дата</label>
                <input type="date" name="date" id="blockDate" required min="{{ date('Y-m-d') }}">
            </div>

            <div class="cal-checkbox-row">
                <input type="checkbox" name="full_day" id="fullDayCheckbox" value="1" checked>
                <label for="fullDayCheckbox">Закрыть весь день</label>
            </div>

            <div class="cal-modal-row" id="timeRangeFields" style="display:none;">
                <div class="cal-modal-field">
                    <label>С</label>
                    <input type="time" name="start_time" id="blockStartTime">
                </div>
                <div class="cal-modal-field">
                    <label>До</label>
                    <input type="time" name="end_time" id="blockEndTime">
                </div>
            </div>

            <div class="cal-modal-field">
                <label>Причина (необязательно)</label>
                <input type="text" name="reason" placeholder="Например: техобслуживание, праздник...">
            </div>

            <div class="cal-modal-actions">
                <button type="button" class="btn btn-outline" id="closeBlockModalBtn" style="padding:8px 16px;">Отмена</button>
                <button type="submit" class="btn btn-primary" style="padding:8px 16px;">Закрыть время</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/ru.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const tourFilter = document.getElementById('tourFilter');
            const isMobile = window.matchMedia('(max-width: 640px)').matches;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                headerToolbar: isMobile
                    ? { left: 'prev,next', center: 'title', right: 'today' }
                    : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                locale: 'ru',
                buttonText: {
                    today: 'Сегодня',
                    month: 'Месяц',
                    week: 'Неделя',
                    day: 'День',
                    list: 'Список'
                },
                buttonIcons: {
                    prev: 'chevron-left',
                    next: 'chevron-right'
                },
                firstDay: 1,
                height: 'auto',
                windowResize: function () {
                    calendar.updateSize();
                },
                events: function (info, successCallback, failureCallback) {
                    const params = new URLSearchParams();
                    if (tourFilter.value) params.set('tour_id', tourFilter.value);

                    fetch(`{{ route('admin.calendar.events') }}?` + params.toString())
                        .then(r => r.json())
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                },
                eventDidMount: function (info) {
                    if (info.event.extendedProps.type === 'blocked') {
                        info.el.style.cursor = 'pointer';
                        info.el.title = 'Кликните, чтобы снять блокировку';
                    }
                },
                eventClick: function (info) {
                    const props = info.event.extendedProps;
                    if (props.type === 'blocked') {
                        info.jsEvent.preventDefault();
                        if (confirm('Снять эту блокировку времени?')) {
                            fetch(`/admin/calendar/block/${props.blocked_id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            }).then(() => calendar.refetchEvents());
                        }
                    }
                    // booking events keep their default url navigation
                },
                dateClick: function (info) {
                    document.getElementById('blockDate').value = info.dateStr;
                    document.getElementById('blockModalOverlay').classList.add('open');
                }
            });

            calendar.render();
            setTimeout(() => calendar.updateSize(), 100);

            tourFilter.addEventListener('change', () => calendar.refetchEvents());

            // ── Block modal controls ──
            const overlay = document.getElementById('blockModalOverlay');
            document.getElementById('openBlockModalBtn').addEventListener('click', () => overlay.classList.add('open'));
            document.getElementById('closeBlockModalBtn').addEventListener('click', () => overlay.classList.remove('open'));
            overlay.addEventListener('click', (e) => { if (e.target === overlay) overlay.classList.remove('open'); });

            const fullDayCheckbox = document.getElementById('fullDayCheckbox');
            const timeRangeFields = document.getElementById('timeRangeFields');
            fullDayCheckbox.addEventListener('change', () => {
                timeRangeFields.style.display = fullDayCheckbox.checked ? 'none' : 'grid';
            });
        });
    </script>
@endpush
