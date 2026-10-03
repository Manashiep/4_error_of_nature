@props(['href' => null, 'variant' => 'primary'])
@php
    $base = 'inline-block cursor-pointer rounded-[.6rem] border-2 bg-transparent px-[1.4rem] py-[.7rem] font-hud text-[.9rem] font-bold tracking-[.05em] text-white no-underline transition-colors hc:bg-[#ffe600] hc:text-black hc:shadow-none hc:hover:bg-white';
    $look = $variant === 'alt'
        ? 'border-cyan-neon shadow-[0_0_14px_rgba(56,232,255,.4),inset_0_0_14px_rgba(56,232,255,.15)] hover:bg-cyan-neon/15'
        : 'border-pink-neon shadow-[0_0_18px_rgba(255,45,138,.5),inset_0_0_18px_rgba(255,45,138,.22)] hover:bg-pink-neon/20';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$base, $look]) }}>{{ $slot }}</a>
@else
    <button type="submit" {{ $attributes->class([$base, $look]) }}>{{ $slot }}</button>
@endif