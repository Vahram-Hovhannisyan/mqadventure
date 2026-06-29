@extends('layouts.admin')
@section('title', 'Շրջայցեր')

@section('content')

    <div class="page-header">
        <div>
            <h2>🏔️ Շրջայցեր</h2>
            <p>Բոլոր շրջայցերի կառավարում</p>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">+ Ավելացնել</a>
    </div>

    <div class="card">
        @if($tours->isEmpty())
            <div style="padding: 60px 20px; text-align: center; color: var(--slate-soft);">
                <div style="font-size: 32px; margin-bottom: 12px;">🏔️</div>
                <div style="font-weight: 600;">Շրջայցեր չկան</div>
                <a href="{{ route('admin.tours.create') }}" class="btn btn-primary" style="margin-top: 16px;">Ստեղծել առաջինը</a>
            </div>
        @else
            <table>
                <thead>
                <tr>
                    <th style="width: 60px;">Նկ.</th>
                    <th>Անվ.</th>
                    <th>Slug</th>
                    <th>Գին</th>
                    <th>Տևողություն</th>
                    <th>Կետեր</th>
                    <th>Կարգ.</th>
                    <th>Ակտիվ</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tours as $tour)
                    <tr>
                        <td>
                            @if($tour->image)
                                <img src="{{ asset('storage/' . $tour->image) }}"
                                     style="width: 48px; height: 36px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 48px; height: 36px; background: var(--bg); border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🏔️</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $tour->getName() }}</div>
                            <div style="font-size: 11px; color: var(--slate-soft);">{{ $tour->getBadge() }}</div>
                        </td>
                        <td style="font-size: 12px; color: var(--slate-soft);">{{ $tour->slug }}</td>
                        <td style="font-weight: 600; color: var(--blue);">{{ $tour->getPriceFormatted() }} AMD</td>
                        <td style="font-size: 13px;">{{ $tour->getDuration() }} ժ.</td>
                        <td>
                            <span class="badge badge-blue">{{ count($tour->route_points ?? []) }} կետ</span>
                        </td>
                        <td style="font-size: 13px; color: var(--slate-soft);">{{ $tour->sort_order }}</td>
                        <td>
                            @if($tour->is_active)
                                <span class="badge badge-green">Ակտ.</span>
                            @else
                                <span class="badge badge-gray">Անջատ.</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="{{ route('admin.tours.edit', $tour) }}" class="btn btn-ghost btn-xs">✏️ Խմբ.</a>
                                <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}"
                                      onsubmit="return confirm('Ջնջե՞լ շրջայցը')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">✕</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
