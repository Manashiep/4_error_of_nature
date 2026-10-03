<x-layouts::app :title="__('Mon espace')">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Espace citoyen</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-zinc-950 dark:text-white">
                    Bonjour {{ $displayName }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                    Retrouvez vos signalements et vos messages à la mairie, ainsi que leur état d’avancement.
                </p>
            </div>
            <nav aria-label="Actions rapides" class="flex flex-wrap gap-3">
                <flux:button :href="route('signalement')" variant="primary" icon="exclamation-triangle">
                    Signaler un problème
                </flux:button>
                <flux:button :href="route('contact')" variant="ghost" icon="envelope">
                    Contacter la mairie
                </flux:button>
            </nav>
        </header>

        <section aria-labelledby="summary-heading">
            <h2 id="summary-heading" class="sr-only">Résumé de mes démarches</h2>
            <dl class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                    <dt class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Mes démarches</dt>
                    <dd class="mt-2 text-3xl font-semibold tabular-nums text-zinc-950 dark:text-white">{{ $stats['total'] }}</dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                    <dt class="text-sm font-medium text-zinc-600 dark:text-zinc-300">En cours de suivi</dt>
                    <dd class="mt-2 text-3xl font-semibold tabular-nums text-zinc-950 dark:text-white">{{ $stats['in_progress'] }}</dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                    <dt class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Traitées</dt>
                    <dd class="mt-2 text-3xl font-semibold tabular-nums text-zinc-950 dark:text-white">{{ $stats['completed'] }}</dd>
                </div>
            </dl>
        </section>

        <section aria-labelledby="activity-heading" class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-1 border-b border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
                <h2 id="activity-heading" class="text-lg font-semibold text-zinc-950 dark:text-white">Mes dernières démarches</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Vos cinq démarches les plus récentes.</p>
            </div>

            @if ($activity->isEmpty())
                <div class="px-5 py-10 text-center sm:px-6">
                    <h3 class="text-base font-semibold text-zinc-950 dark:text-white">Vous n’avez pas encore de démarche</h3>
                    <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                        Après un signalement ou un message à la mairie, vous pourrez suivre ici son état et retrouver sa référence.
                    </p>
                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                        <flux:button :href="route('signalement')" variant="primary">Signaler un problème</flux:button>
                        <flux:button :href="route('contact')" variant="ghost">Écrire à la mairie</flux:button>
                    </div>
                </div>
            @else
                <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($activity as $item)
                        @php
                            $statusLabel = match ($item['status']) {
                                'en_cours', 'en-cours', 'in_progress' => 'En cours',
                                'traite', 'traitée', 'completed', 'resolved' => 'Traitée',
                                'rejected' => 'Refusée',
                                'nouveau', 'pending' => 'Reçue',
                                default => ucfirst($item['status']),
                            };
                        @endphp
                        <li class="flex flex-col gap-3 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div class="min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $item['type'] }}</p>
                                <h3 class="mt-1 truncate font-medium text-zinc-950 dark:text-white">{{ $item['title'] }}</h3>
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                    Référence <span class="font-medium tabular-nums">{{ $item['reference'] }}</span>
                                </p>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-x-4 gap-y-2 sm:justify-end">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium',
                                    'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-200' => in_array($item['status'], ['nouveau', 'pending']),
                                    'bg-blue-100 text-blue-900 dark:bg-blue-950 dark:text-blue-200' => in_array($item['status'], ['en_cours', 'en-cours', 'in_progress']),
                                    'bg-green-100 text-green-900 dark:bg-green-950 dark:text-green-200' => in_array($item['status'], ['traite', 'traitée', 'completed', 'resolved']),
                                    'bg-red-100 text-red-900 dark:bg-red-950 dark:text-red-200' => $item['status'] === 'rejected',
                                    'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200' => ! in_array($item['status'], ['nouveau', 'pending', 'en_cours', 'en-cours', 'in_progress', 'traite', 'traitée', 'completed', 'resolved', 'rejected']),
                                ])>{{ $statusLabel }}</span>
                                <time class="text-sm text-zinc-600 dark:text-zinc-300" datetime="{{ $item['created_at']->toIso8601String() }}">
                                    {{ $item['created_at']->translatedFormat('j M Y') }}
                                </time>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <aside aria-labelledby="profile-heading" class="flex flex-col gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-900/60 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 id="profile-heading" class="font-semibold text-zinc-950 dark:text-white">Vos informations ont changé ?</h2>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">Vérifiez que votre nom et votre adresse e-mail sont à jour.</p>
            </div>
            <flux:button :href="route('profile.edit')" variant="ghost">Gérer mon profil</flux:button>
        </aside>
    </div>
</x-layouts::app>
