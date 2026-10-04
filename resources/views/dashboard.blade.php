<x-layouts::app :title="__('Mon espace')">
    <div class="mx-auto w-full max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Espace citoyen</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-zinc-950 dark:text-white">
                    Bonjour {{ $displayName }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                    Retrouvez vos signalements, messages et rendez-vous de service, ainsi que leur état d’avancement.
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

        <section aria-labelledby="appointments-heading" class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="border-b border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
                <h2 id="appointments-heading" class="text-lg font-semibold text-zinc-950 dark:text-white">Mes rendez-vous de service</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Consultez toutes vos demandes de rendez-vous et les réponses des services.</p>
            </div>

            @if ($appointments->isEmpty())
                <div class="px-5 py-8 text-center sm:px-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">Vous n’avez pas encore demandé de rendez-vous.</p>
                    <a href="{{ route('services.index') }}" class="mt-3 inline-flex text-sm font-medium text-blue-700 underline dark:text-blue-300">
                        Découvrir les services
                    </a>
                </div>
            @else
                <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($appointments as $appointment)
                        @php
                            [$appointmentStatus, $appointmentColor] = match ($appointment->status) {
                                'traite' => ['Accepté', 'bg-green-100 text-green-900 dark:bg-green-950 dark:text-green-200'],
                                'archive' => ['Refusé', 'bg-red-100 text-red-900 dark:bg-red-950 dark:text-red-200'],
                                'en_cours' => ['En cours de traitement', 'bg-blue-100 text-blue-900 dark:bg-blue-950 dark:text-blue-200'],
                                default => ['En attente de réponse', 'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-200'],
                            };
                        @endphp
                        <li class="flex flex-col gap-3 px-5 py-5 sm:px-6">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="font-medium text-zinc-950 dark:text-white">{{ $appointment->service?->tr('name') ?? $appointment->service?->name ?? 'Service municipal' }}</h3>
                                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ $appointment->subject }}</p>
                                </div>
                                <span @class(['inline-flex w-fit items-center rounded-full px-2.5 py-1 text-xs font-medium', $appointmentColor])>
                                    {{ $appointmentStatus }}
                                </span>
                            </div>

                            @if ($appointment->status === 'traite')
                                <p class="text-sm leading-6 text-zinc-700 dark:text-zinc-200">
                                    @if ($appointment->confirmed_at)
                                        Votre rendez-vous est confirmé pour le
                                        <time datetime="{{ $appointment->confirmed_at->toIso8601String() }}" class="font-semibold">
                                            {{ $appointment->confirmed_at->translatedFormat('l j F Y à H:i') }}
                                        </time>.
                                    @else
                                        Votre demande est acceptée. Le service vous communiquera prochainement le créneau confirmé.
                                    @endif
                                </p>
                            @elseif ($appointment->status === 'archive')
                                <p class="text-sm leading-6 text-zinc-700 dark:text-zinc-200">
                                    Votre demande de rendez-vous n’a pas été acceptée.
                                    @if (filled($appointment->rejection_reason))
                                        Motif : {{ $appointment->rejection_reason }}
                                    @endif
                                </p>
                            @else
                                <p class="text-sm text-zinc-600 dark:text-zinc-300">
                                    Créneau souhaité :
                                    @if ($appointment->requested_at)
                                        <time datetime="{{ $appointment->requested_at->toIso8601String() }}">
                                            {{ $appointment->requested_at->translatedFormat('l j F Y à H:i') }}
                                        </time>
                                    @else
                                        à préciser
                                    @endif
                                </p>
                            @endif

                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Demande envoyée le {{ $appointment->created_at->translatedFormat('j M Y') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
                @if ($appointments->hasPages())
                    <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
                        {{ $appointments->links() }}
                    </div>
                @endif
            @endif
        </section>

        <section aria-labelledby="activity-heading" class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-1 border-b border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
                <h2 id="activity-heading" class="text-lg font-semibold text-zinc-950 dark:text-white">Historique de mes démarches</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">Retrouvez tous vos signalements et messages à la mairie, du plus récent au plus ancien.</p>
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
                            $createdAt = \Illuminate\Support\Carbon::parse($item->created_at);
                            $statusLabel = match ($item->status) {
                                'en_cours', 'en-cours', 'in_progress' => 'En cours',
                                'traite', 'traitée', 'completed', 'resolved' => 'Traitée',
                                'rejected' => 'Refusée',
                                'nouveau', 'pending' => 'Reçue',
                                default => ucfirst($item['status']),
                            };
                        @endphp
                        <li class="flex flex-col gap-3 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div class="min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $item->type }}</p>
                                <h3 class="mt-1 truncate font-medium text-zinc-950 dark:text-white">{{ $item->title }}</h3>
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                    Référence <span class="font-medium tabular-nums">{{ $item->reference }}</span>
                                </p>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-x-4 gap-y-2 sm:justify-end">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium',
                                    'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-200' => in_array($item->status, ['nouveau', 'pending']),
                                    'bg-blue-100 text-blue-900 dark:bg-blue-950 dark:text-blue-200' => in_array($item->status, ['en_cours', 'en-cours', 'in_progress']),
                                    'bg-green-100 text-green-900 dark:bg-green-950 dark:text-green-200' => in_array($item->status, ['traite', 'traitée', 'completed', 'resolved']),
                                    'bg-red-100 text-red-900 dark:bg-red-950 dark:text-red-200' => $item->status === 'rejected',
                                    'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200' => ! in_array($item->status, ['nouveau', 'pending', 'en_cours', 'en-cours', 'in_progress', 'traite', 'traitée', 'completed', 'resolved', 'rejected']),
                                ])>{{ $statusLabel }}</span>
                                <time class="text-sm text-zinc-600 dark:text-zinc-300" datetime="{{ $createdAt->toIso8601String() }}">
                                    {{ $createdAt->translatedFormat('j M Y') }}
                                </time>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if ($activity->hasPages())
                    <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
                        {{ $activity->links() }}
                    </div>
                @endif
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
