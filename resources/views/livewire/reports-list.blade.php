<div>
<x-public.panel class="my-6 p-6" aria-labelledby="h-d">
    <h2 id="h-d" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Demandes en cours
        <a href="{{ route('signalement') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Mon problème n'est pas dans la liste →</a>
    </h2>

    @php
        $field = 'rounded-full border border-edge bg-glass px-5 py-3 text-ink hc:border-2 hc:border-white hc:bg-black [&>option]:text-black';
        $chip = 'block rounded-full border border-edge px-4 py-1.5 text-ink hover:bg-glass-hi aria-[pressed=true]:border-cyan aria-[pressed=true]:bg-cyan/15 hc:border-2 hc:aria-[pressed=true]:bg-[#ffe600] hc:aria-[pressed=true]:text-black';
    @endphp

    {{-- Recherche, statut, tri --}}
    <div role="search" class="mb-5 grid gap-3 md:grid-cols-[1fr_auto_auto]">
        <div>
            <label for="dem-q" class="sr-only">Rechercher une demande</label>
            <input id="dem-q" type="search" wire:model.live.debounce.300ms="search" autocomplete="off"
                   placeholder="Rechercher (lieu, mot-clé, numéro SIG-…)"
                   class="{{ $field }} w-full placeholder:text-mute/70">
        </div>
        <div>
            <label for="dem-statut" class="sr-only">Filtrer par statut</label>
            <select id="dem-statut" wire:model.live="statut" class="{{ $field }} w-full">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="dem-tri" class="sr-only">Trier</label>
            <select id="dem-tri" wire:model.live="tri" class="{{ $field }} w-full">
                <option value="soutenues">Les plus soutenues</option>
                <option value="recentes">Les plus récentes</option>
            </select>
        </div>
    </div>

    {{-- Types de problème --}}
    <ul class="mb-5 flex flex-wrap gap-2" aria-label="Types de problème">
        <li><button type="button" wire:click="setCategorie('')" aria-pressed="{{ $categorie === '' ? 'true' : 'false' }}" class="{{ $chip }}">Toutes</button></li>
        @foreach ($categories as $c)
            <li><button type="button" wire:click="setCategorie('{{ addslashes($c) }}')" aria-pressed="{{ $categorie === $c ? 'true' : 'false' }}" class="{{ $chip }}">{{ $c }}</button></li>
        @endforeach
    </ul>

    <p role="status" aria-live="polite" class="mb-4 flex flex-wrap items-center gap-3 text-[.9rem] text-mute">
        @if ($items->isEmpty())
            Aucune demande ne correspond.
        @else
            {{ $items->count() }} demande{{ $items->count() > 1 ? 's' : '' }} affichée{{ $items->count() > 1 ? 's' : '' }}
        @endif
        @if ($search !== '' || $categorie !== '' || $statut !== '')
            <button type="button" wire:click="resetFilters" class="text-cyan underline">Effacer les filtres</button>
        @endif
    </p>

    @if ($items->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">
            Aucune demande pour le moment. <a href="{{ route('signalement') }}" class="text-cyan underline">Signaler un problème</a>
        </p>
    @else
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $r)
                @php
                    $isMine = auth()->check() && $r->user_id === auth()->id();
                    $on = in_array($r->id, $supportedIds, true);
                @endphp
                <li wire:key="report-{{ $r->id }}"
                    class="flex flex-col overflow-hidden rounded-3xl border border-edge bg-linear-to-br from-glass-hi to-glass hc:border-2 hc:border-white hc:bg-black hc:bg-none">
                    @if ($r->image_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($r->image_path) }}" alt="" loading="lazy" class="h-36 w-full object-cover">
                    @endif

                    <div class="flex flex-1 flex-col gap-2 p-5">
                        <p class="flex flex-wrap gap-2 text-[.75rem]">
                            <span class="rounded-full border border-edge px-2.5 py-0.5">{{ $r->category }}</span>
                            <span class="rounded-full border px-2.5 py-0.5 {{ $r->status_tone }}">{{ $r->status_label }}</span>
                        </p>

                        <h3 class="font-hud text-[.95rem] font-medium leading-snug">
                            <a href="{{ route('demandes.show', $r) }}" class="text-ink no-underline hover:text-cyan hc:underline">{{ $r->category }} – {{ $r->location }}</a>
                        </h3>
                        <p class="line-clamp-3 text-mute">{{ $r->description }}</p>
                        <time datetime="{{ $r->created_at->toDateString() }}" class="text-[.8rem] text-cyan">{{ $r->created_at->locale('fr')->translatedFormat('j M Y') }}</time>

                        <div class="mt-auto flex items-center justify-between gap-3 pt-3">
                            <a href="{{ route('demandes.show', $r) }}" class="font-bold text-cyan no-underline hover:underline">Détail →</a>

                            @if ($r->is_closed)
                                <span class="text-[.9rem] text-mute">👍 {{ $r->supports_count }} · clôturée</span>
                            @elseif ($isMine)
                                <span class="text-[.9rem] font-bold text-cyan">👍 {{ $r->supports_count }} · votre demande</span>
                            @else
                                <button type="button" wire:click="toggleSupport({{ $r->id }})" wire:loading.attr="disabled"
                                        aria-pressed="{{ $on ? 'true' : 'false' }}"
                                        aria-label="{{ $on ? 'Retirer mon soutien' : 'Soutenir cette demande' }} ({{ $r->supports_count }} soutien{{ $r->supports_count > 1 ? 's' : '' }})"
                                        class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 font-bold transition hover:-translate-y-0.5 hc:border-2 hc:border-white
                                               {{ $on ? 'border-cyan bg-cyan/15 text-cyan' : 'border-edge text-ink hover:bg-glass-hi' }}">
                                    <span aria-hidden="true">👍</span>
                                    <span>{{ $r->supports_count }}</span>
                                </button>
                            @endif
                        </div>

                        @if ($noticeFor === $r->id && $notice)
                            <p role="status" class="text-[.85rem] text-mute">{{ $notice }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($items->onFirstPage() === false || $items->hasMorePages())
        <nav aria-label="Pagination" class="mt-6 flex items-center justify-between gap-4">
            @unless ($items->onFirstPage())
                <button type="button" wire:click="previousPage" class="rounded-full border border-edge px-5 py-2 text-ink hover:bg-glass-hi">← Précédentes</button>
            @else
                <span></span>
            @endunless
            @if ($items->hasMorePages())
                <button type="button" wire:click="nextPage" class="rounded-full border border-edge px-5 py-2 text-ink hover:bg-glass-hi">Suivantes →</button>
            @endif
        </nav>
    @endif
</x-public.panel>
</div>