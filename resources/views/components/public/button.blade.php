@props(['href' => null, 'variant' => 'primary'])
@php
    $base = 'inline-flex cursor-pointer items-center justify-center gap-2 rounded-full px-6 py-3 font-bold no-underline transition hover:brightness-110 hc:border-2 hc:border-white hc:bg-[#ffe600] hc:bg-none hc:text-black hc:shadow-none';
    $look = $variant === 'alt'
        ? 'border border-edge bg-glass text-ink hover:bg-glass-hi'
        : 'bg-linear-to-br from-cyan to-cyan/70 text-on-cyan shadow-[0_0_28px_var(--glow)]';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$base, $look]) }}>{{ $slot }}</a>
@else
    <button type="submit" {{ $attributes->class([$base, $look]) }}>{{ $slot }}</button>
@endif
