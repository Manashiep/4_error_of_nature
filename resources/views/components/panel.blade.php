@props(['as' => 'section'])
<{{ $as }} {{ $attributes->class([
    'relative rounded-2xl border border-line bg-[rgba(8,16,48,.14)] backdrop-blur-[5px] backdrop-saturate-[1.3]',
    'shadow-[0_0_24px_rgba(56,232,255,.18),inset_0_0_40px_rgba(255,255,255,.04)]',
    "after:absolute after:-top-px after:left-[1.1rem] after:h-0.5 after:w-[4.4rem] after:bg-cyan-neon after:shadow-[0_0_10px_var(--color-cyan-neon)] after:content-['']",
    'hc:border-2 hc:border-white hc:bg-black hc:shadow-none hc:backdrop-blur-none hc:after:hidden',
]) }}>
    {{ $slot }}
</{{ $as }}>