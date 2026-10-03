<x-layouts::app :title="__('Mes notifications')">
    <div class="mx-auto w-full max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header>
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Espace citoyen</p>
            <h1 class="mt-1 text-3xl font-semibold tracking-tight text-zinc-950 dark:text-white">Mes notifications</h1>
            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                Retrouvez les mises à jour concernant vos signalements.
            </p>
        </header>

        @if (session('status'))
            <p role="status" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-900 dark:bg-green-950 dark:text-green-200">
                {{ session('status') }}
            </p>
        @endif

        <section aria-label="Historique des notifications" class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            @if ($notifications->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-zinc-600 dark:text-zinc-300">
                    Vous n’avez pas encore de notification.
                </p>
            @else
                <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($notifications as $notification)
                        <li class="flex flex-col gap-3 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="font-medium text-zinc-950 dark:text-white">
                                    {{ $notification->data['report_title'] ?? 'Mise à jour de votre demande' }}
                                    @unless ($notification->read_at)
                                        <span class="ms-2 inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-900 dark:bg-red-950 dark:text-red-200">Nouveau</span>
                                    @endunless
                                </p>
                                <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                                    {{ $notification->data['message'] ?? 'Le statut de votre demande a changé.' }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $notification->created_at->translatedFormat('j M Y à H:i') }}
                                    @if (! empty($notification->data['report_reference']))
                                        · Référence {{ $notification->data['report_reference'] }}
                                    @endif
                                </p>
                            </div>

                            @unless ($notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-blue-700 underline hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-100">
                                        Marquer comme lue
                                    </button>
                                </form>
                            @endunless
                        </li>
                    @endforeach
                </ul>
                <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-700">
                    {{ $notifications->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts::app>
