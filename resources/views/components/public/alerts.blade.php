@props(['alerts'])

@php
    $styles = [
        'alerte'    => 'border-red-500 bg-red-500/15',
        'important' => 'border-amber-400 bg-amber-400/15',
        'info'      => 'border-cyan bg-cyan/10',
    ];
    $labels = ['alerte' => 'Alerte', 'important' => 'Important', 'info' => 'Information'];
    $sig = $alerts->map(fn ($a) => $a->id.':'.(int) $a->updated_at?->timestamp)->implode('|');
@endphp

<section id="alerts-zone" aria-label="Alertes de la ville" aria-live="polite"
         data-url="{{ route('alerts.active') }}" data-sig="{{ $sig }}"
         class="my-6" @if ($alerts->isEmpty()) hidden @endif>
    <div id="alerts-list" class="grid gap-3">
        @foreach ($alerts as $a)
            <div role="alert" data-alert-id="{{ $a->id }}"
                 class="rounded-2xl border-2 p-5 hc:border-white hc:bg-black {{ $styles[$a->level] ?? $styles['info'] }}">
                <h2 class="font-hud text-[1.05rem] font-medium">
                    <span aria-hidden="true">⚠</span>
                    <span class="mr-2 rounded-full border border-current px-2.5 py-0.5 text-[.75rem]">{{ $labels[$a->level] ?? 'Information' }}</span>
                    <a href="{{ route('alerts.show', $a) }}" class="text-ink no-underline hover:underline">{{ $a->title }}</a>
                </h2>
                @if ($a->summary)
                    <p class="mt-1">{{ $a->summary }}</p>
                @endif
                <a href="{{ route('alerts.show', $a) }}" class="mt-2 inline-block font-bold underline hover:no-underline">Voir le détail →</a>
            </div>
        @endforeach
    </div>
    <p class="mt-3 text-right text-[.95rem]"><a href="{{ route('alerts.index') }}" class="font-bold text-cyan hover:underline hc:underline">Toutes les alertes →</a></p>
</section>

<script>
(() => {
    const zone = document.getElementById('alerts-zone');
    const list = document.getElementById('alerts-list');
    if (!zone || !list) return;

    const styles = {
        alerte: 'border-red-500 bg-red-500/15',
        important: 'border-amber-400 bg-amber-400/15',
        info: 'border-cyan bg-cyan/10',
    };
    const labels = { alerte: 'Alerte', important: 'Important', info: 'Information' };

    function render(alerts) {
        list.replaceChildren(...alerts.map(a => {
            const box = document.createElement('div');
            box.setAttribute('role', 'alert');
            box.dataset.alertId = a.id;
            box.className = 'rounded-2xl border-2 p-5 hc:border-white hc:bg-black ' + (styles[a.level] || styles.info);

            const h = document.createElement('h2');
            h.className = 'font-hud text-[1.05rem] font-medium';
            const icon = document.createElement('span');
            icon.setAttribute('aria-hidden', 'true');
            icon.textContent = '⚠ ';
            const badge = document.createElement('span');
            badge.className = 'mr-2 rounded-full border border-current px-2.5 py-0.5 text-[.75rem]';
            badge.textContent = labels[a.level] || 'Information';
            const link = document.createElement('a');
            link.href = a.url;
            link.className = 'text-ink no-underline hover:underline';
            link.textContent = a.title;
            h.append(icon, badge, link);
            box.appendChild(h);

            if (a.summary) {
                const p = document.createElement('p');
                p.className = 'mt-1';
                p.textContent = a.summary;
                box.appendChild(p);
            }

            const more = document.createElement('a');
            more.href = a.url;
            more.className = 'mt-2 inline-block font-bold underline hover:no-underline';
            more.textContent = 'Voir le détail →';
            box.appendChild(more);
            return box;
        }));
        zone.hidden = alerts.length === 0;
    }

    async function refresh() {
        try {
            const r = await fetch(zone.dataset.url, { headers: { Accept: 'application/json' } });
            if (!r.ok) return;
            const { alerts } = await r.json();
            const sig = alerts.map(a => a.id + ':' + a.updated).join('|');
            if (sig !== zone.dataset.sig) {
                zone.dataset.sig = sig;
                render(alerts);
            }
        } catch (e) { /* nouvel essai au prochain tour */ }
    }

    setInterval(() => { if (!document.hidden) refresh(); }, 20000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); });
})();
</script>