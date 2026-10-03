<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TerraNovaService;

class TerraWatch extends Command
{
    protected $signature = 'terra:watch';
    protected $description = 'Surveille l\'API Terra Nova en continu (toutes les 30s)';

    public function handle(TerraNovaService $service): int
    {
        $this->info('Surveillance Terra Nova démarrée... (Ctrl+C pour arrêter)');
        $this->newLine();

        while (true) {
            try {
                $service->fetchAndSaveRequests();

                $count = \App\Models\TerraRequest::count();
                $session = \App\Models\TerraSession::first();

                $wave = $session?->current_wave ?? '?';
                $next = $session?->minutes_until_next_wave ?? '?';

                $this->line('[' . now()->format('H:i:s') . "] ✅ Sync OK — {$count} demandes | Vague {$wave} | Prochaine dans {$next} min");
            } catch (\Exception $e) {
                $this->error('[' . now()->format('H:i:s') . '] ❌ ' . $e->getMessage());
            }

            sleep(30);
        }

        return self::SUCCESS;
    }
}
