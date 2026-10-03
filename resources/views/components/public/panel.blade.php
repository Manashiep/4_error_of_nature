@props(['as' => 'section'])
<{{ $as }} {{ $attributes->class([
    'relative rounded-[28px] border border-edge bg-linear-to-br from-glass-hi via-glass via-40% to-glass backdrop-blur-[26px] backdrop-saturate-[1.7]',
    'shadow-[0_30px_60px_-20px_var(--shadow),inset_0_1px_0_rgba(255,255,255,.35)]',
    'hc:border-2 hc:border-white hc:bg-black hc:bg-none hc:shadow-none hc:backdrop-blur-none',
]) }}>
    {{ $slot }}
</{{ $as }}>
