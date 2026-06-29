<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #333; }
        h1   { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #888; font-size: 11px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th    { text-align: left; background: #f5f5f5; padding: 8px 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #666; }
        td    { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #e8f4fd; color: #1a6fa8; }
    </style>
</head>
<body>
<h1>{{ __('booking.title', ['id' => $booking->id]) }}</h1>
<div class="meta">{{ $booking->created_at->format('d.m.Y H:i') }}</div>

<table>
    <tr><th colspan="2">{{ __('booking.client') }}</th></tr>
    <tr><td>{{ __('booking.name') }}</td><td><strong>{{ $booking->name }}</strong></td></tr>
    <tr><td>{{ __('booking.phone') }}</td><td>{{ $booking->phone }}</td></tr>
    @if($booking->locale)
        <tr><td>{{ __('booking.language') }}</td><td>{{ strtoupper($booking->locale) }}</td></tr>
    @endif
</table>

<table>
    <tr><th colspan="2">{{ __('booking.tour') }}</th></tr>
    <tr><td>{{ __('booking.tour') }}</td><td>{{ $booking->tour?->getName() ?? __('booking.not_specified') }}</td></tr>
    <tr><td>{{ __('booking.date') }}</td><td>{{ $booking->date?->format('d.m.Y') ?? __('booking.not_specified') }}</td></tr>
    <tr><td>{{ __('booking.people') }}</td><td>{{ $booking->people ? $booking->people . ' ' . __('booking.people_unit') : __('booking.not_specified') }}</td></tr>
    @if($booking->comment)
        <tr><td>{{ __('booking.comment') }}</td><td>{{ $booking->comment }}</td></tr>
    @endif
</table>

<table>
    <tr><th colspan="2">{{ __('booking.status') }}</th></tr>
    @if($booking->tour)
        <tr><td>{{ __('booking.price') }}</td><td>{{ $booking->tour->getPriceFormatted() }} AMD</td></tr>
    @endif
</table>
</body>
</html>
