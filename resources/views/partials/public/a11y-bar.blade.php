@php
    $a11yBtn = 'cursor-pointer rounded-lg border border-line bg-white/5 px-[.7rem] py-1 text-ink hover:border-cyan-neon aria-pressed:border-pink-neon aria-pressed:bg-pink-neon/15';
@endphp
<div role="region" aria-label="Options d'accessibilité" class="flex flex-wrap items-center justify-end gap-2 pb-2 pt-1 text-[.9rem]">
    <span>Affichage</span>
    <button type="button" id="a11y-minus" class="{{ $a11yBtn }}" aria-label="Réduire la taille du texte">A−</button>
    <button type="button" id="a11y-reset" class="{{ $a11yBtn }}" aria-label="Taille du texte par défaut">A</button>
    <button type="button" id="a11y-plus" class="{{ $a11yBtn }}" aria-label="Agrandir la taille du texte">A+</button>
    <button type="button" id="a11y-hc" class="{{ $a11yBtn }}" aria-pressed="false">Contraste élevé</button>
    <span id="a11y-msg" role="status" aria-live="polite" class="sr-only"></span>
</div>
