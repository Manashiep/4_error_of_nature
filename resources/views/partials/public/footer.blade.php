<footer class="mt-10 flex flex-wrap items-center justify-between gap-4 px-2 text-[.9rem] text-mute">
    <span class="flex items-center gap-3">
        <x-public.logo class="h-7 w-auto" />
        Terra Nova · Portail officiel des habitants
    </span>
    <nav aria-label="Pied de page">
        <ul class="flex flex-wrap gap-4">
            <li><a href="{{ route('services.index') }}" class="text-cyan hover:underline hc:underline">Services</a></li>
            <li><a href="{{ route('annonces.index') }}" class="text-cyan hover:underline hc:underline">Annonces</a></li>
            <li><a href="{{ route('contact') }}" class="text-cyan hover:underline hc:underline">Contact</a></li>
        </ul>
    </nav>
</footer>
