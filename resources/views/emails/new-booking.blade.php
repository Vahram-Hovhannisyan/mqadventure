<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Նոր պատվեր</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9; padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">

                {{-- Header --}}
                <tr>
                    <td style="background:#4A7C40; padding:24px 28px;">
                        <div style="color:#ffffff; font-size:13px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; opacity:.85;">
                            Meghradzor Quad Adventure
                        </div>
                        <div style="color:#ffffff; font-size:20px; font-weight:800; margin-top:6px;">
                            🔔 Նոր պատվեր №{{ $booking->id }}
                        </div>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:28px;">

                        <p style="margin:0 0 20px; font-size:13px; color:#64748b;">
                            Ստացվել է {{ $booking->created_at->format('d.m.Y') }} -ին՝ {{ $booking->created_at->format('H:i') }}
                        </p>

                        {{-- Client --}}
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                            <tr>
                                <td style="padding:6px 0; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; width:40%;">Անուն</td>
                                <td style="padding:6px 0; font-size:14px; font-weight:700; color:#1e293b;">{{ $booking->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Հեռախոս</td>
                                <td style="padding:6px 0; font-size:14px; font-weight:700;">
                                    <a href="tel:{{ $booking->phone }}" style="color:#2563eb; text-decoration:none;">{{ $booking->phone }}</a>
                                </td>
                            </tr>
                            @if($booking->tour)
                                <tr>
                                    <td style="padding:6px 0; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Տուր</td>
                                    <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ $booking->tour->getName() }}</td>
                                </tr>
                            @endif
                            @if($booking->date)
                                <tr>
                                    <td style="padding:6px 0; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Ամսաթիվ</td>
                                    <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">
                                        {{ $booking->date->format('d.m.Y') }}
                                        @if($booking->time)
                                            ժամը {{ substr($booking->time, 0, 5) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if($booking->people)
                                <tr>
                                    <td style="padding:6px 0; font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Մարդ</td>
                                    <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ $booking->people }}</td>
                                </tr>
                            @endif
                        </table>

                        @if($booking->comment)
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; margin-bottom:24px;">
                                <div style="font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:6px;">Հաճախորդի մեկնաբանությունը</div>
                                <div style="font-size:13px; line-height:1.6; color:#334155;">{{ $booking->comment }}</div>
                            </div>
                        @endif

                        {{-- CTA --}}
                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin:8px 0 4px;">
                            <tr>
                                <td style="border-radius:8px; background:#4A7C40;">
                                    <a href="{{ route('admin.booking.show', $booking) }}"
                                       style="display:inline-block; padding:12px 24px; font-size:14px; font-weight:700; color:#ffffff; text-decoration:none;">
                                        Բացել պատվերը ադմինում →
                                    </a>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding:16px 28px; background:#f8fafc; border-top:1px solid #e2e8f0;">
                        <p style="margin:0; font-size:11px; color:#94a3b8;">
                            Meghradzor Quad Adventure ամրագրման համակարգի ավտոմատ ծանուցում:
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
