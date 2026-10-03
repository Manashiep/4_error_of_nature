{{-- D15 : fil d'Ariane. items = [['Libellé', '/url' ou null], ...] (le dernier = page courante) --}}
@props(['items' => []])
<nav aria-label="Fil d'Ariane" class="my-2 px-1 text-[.9rem] text-mute">
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
        <li><a href="{{ url('/') }}" class="text-cyan-neon hover:underline hc:underline">Accueil</a></li>
        @foreach ($items as $item)
            <li aria-hidden="true">›</li>
            <li>
                @if (! $loop->last && ! empty($item[1]))
                    <a href="{{ url($item[1]) }}" class="text-cyan-neon hover:underline hc:underline">{{ $item[0] }}</a>
                @else
                    <span aria-current="page" class="text-ink">{{ $item[0] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
