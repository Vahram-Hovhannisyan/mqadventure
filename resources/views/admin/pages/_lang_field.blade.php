{{-- admin/pages/_lang_field.blade.php --}}
{{-- Variables: $key, $label, $type ('input'|'textarea'), $setting --}}
<div class="pg-field">
    <label class="pg-label">{{ $label }}</label>
    <div class="lang-group">
        <div class="lang-tabs">
            @foreach(['hy' => '🇦🇲 HY', 'ru' => '🇷🇺 RU', 'en' => '🇬🇧 EN'] as $code => $flag)
                <div class="lang-tab {{ $loop->first ? 'active' : '' }}" data-lang="{{ $code }}">{{ $flag }}</div>
            @endforeach
        </div>
        @foreach(['hy', 'ru', 'en'] as $code)
            <div class="lang-panel {{ $loop->first ? 'active' : '' }}" data-lang="{{ $code }}">
                @if(($type ?? 'input') === 'textarea')
                    <textarea name="{{ $key }}[{{ $code }}]"
                              rows="2">{{ old("{$key}.{$code}", $setting?->value[$code] ?? '') }}</textarea>
                @else
                    <input type="text" name="{{ $key }}[{{ $code }}]"
                           value="{{ old("{$key}.{$code}", $setting?->value[$code] ?? '') }}" />
                @endif
            </div>
        @endforeach
    </div>
</div>
