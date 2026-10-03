<div>
    <button type="button" wire:click="toggle" wire:loading.attr="disabled"
            aria-pressed="{{ $supported ? 'true' : 'false' }}"
            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 font-bold transition hover:-translate-y-0.5 hc:border-2 hc:border-white
                   {{ $supported ? 'border-cyan bg-cyan/15 text-cyan' : 'border-edge text-ink hover:bg-glass-hi' }}">
        <span aria-hidden="true">👍</span>
        <span>{{ $supported ? 'Soutenu' : 'Soutenir' }}</span>
        <span class="rounded-full bg-glass px-2 text-[.85rem]" aria-label="{{ $count }} soutien{{ $count > 1 ? 's' : '' }}">{{ $count }}</span>
    </button>

    @if ($message)
        <p role="status" class="mt-2 text-[.9rem] text-mute">{{ $message }}</p>
    @endif
</div>