@php
    $btn = 'cursor-pointer rounded-full border border-edge bg-glass px-3 py-1 text-ink hover:bg-glass-hi aria-pressed:border-cyan aria-pressed:bg-cyan/20 hc:border-2';
    // Langues = celles réellement présentes dans les traductions des services (D14 / F27)
    $langs = \App\Models\Service::languages();
    $cur = session('lang', 'fr');
    $names = ['fr' => 'Français', 'en' => 'English', 'es' => 'Español', 'pt' => 'Português', 'de' => 'Deutsch', 'it' => 'Italiano', 'ar' => 'العربية'];
@endphp
<div role="region" aria-label="Options d'accessibilité" class="flex flex-wrap items-center justify-end gap-2 py-2 text-[.88rem]">
    @if (count($langs))
        <nav aria-label="Langue" class="mr-auto flex flex-wrap gap-1">
            @foreach (array_unique(array_merge(['fr'], $langs)) as $code)
                <a href="{{ route('lang', $code) }}" lang="{{ $code }}" hreflang="{{ $code }}" @if ($cur === $code) aria-current="true" @endif
                   class="rounded-full border border-transparent px-3 py-1 text-mute no-underline hover:text-ink aria-[current=true]:border-cyan aria-[current=true]:text-ink hc:underline">{{ $names[$code] ?? strtoupper($code) }}</a>
            @endforeach
        </nav>
    @endif
    <span class="text-mute">Affichage</span>
    <button type="button" id="a11y-minus" class="{{ $btn }}" aria-label="Réduire la taille du texte">A−</button>
    <button type="button" id="a11y-reset" class="{{ $btn }}" aria-label="Taille du texte par défaut">A</button>
    <button type="button" id="a11y-plus" class="{{ $btn }}" aria-label="Agrandir la taille du texte">A+</button>
    <button type="button" id="a11y-hc" class="{{ $btn }}" aria-pressed="false">Contraste élevé</button>
    <span id="a11y-msg" role="status" aria-live="polite" class="sr-only"></span>
</div>
