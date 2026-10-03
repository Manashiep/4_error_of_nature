@php
    $user = auth()->user();
    $unreadNotifications = $user->unreadNotifications()->latest()->limit(5)->get();
    $unreadCount = $user->unreadNotifications()->count();
@endphp

<flux:dropdown position="bottom" align="end">
    <flux:button variant="ghost" size="sm" class="relative" aria-label="Notifications">
        <flux:icon name="bell" class="size-5" />
        @if ($unreadCount > 0)
            <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold leading-none text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </flux:button>

    <flux:menu>
        <div class="px-2 py-1.5 text-sm font-semibold text-zinc-950 dark:text-white">Notifications non lues</div>
        <flux:menu.separator />

        @forelse ($unreadNotifications as $notification)
            <div class="max-w-80 px-2 py-2">
                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $notification->data['report_title'] ?? 'Mise à jour de votre demande' }}
                </p>
                <p class="mt-1 text-xs leading-5 text-zinc-600 dark:text-zinc-300">
                    {{ $notification->data['message'] ?? 'Le statut de votre demande a changé.' }}
                </p>
                @if (! empty($notification->data['report_reference']))
                    <a class="mt-1 inline-block text-xs font-medium text-blue-700 underline dark:text-blue-300"
                       href="{{ route('demandes.show', $notification->data['report_reference']) }}">
                        Voir {{ $notification->data['report_reference'] }}
                    </a>
                @endif
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-zinc-600 underline hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white">
                        Marquer comme lue
                    </button>
                </form>
            </div>
            <flux:menu.separator />
        @empty
            <p class="px-2 py-3 text-sm text-zinc-600 dark:text-zinc-300">Vous êtes à jour.</p>
        @endforelse

        <flux:menu.item :href="route('notifications.index')" icon="archive-box" wire:navigate>
            Voir l’historique
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
