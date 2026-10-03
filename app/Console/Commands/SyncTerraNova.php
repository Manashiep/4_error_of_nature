<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\TerraNovaService;

class SyncTerraNova extends Command
{
    protected $signature = 'terra:sync';

    protected $description = 'Synchronise les demandes et la session depuis l\'API Terra Nova';

    public function handle(TerraNovaService $service): int
    {
        $this->info('Synchronisation avec l\'API Terra Nova...');

        try {
            $service->fetchAndSaveRequests();

            $count = \App\Models\TerraRequest::count();
            $session = \App\Models\TerraSession::first();

            $this->info("✅ Synchronisation réussie !");
            $this->line("Demandes en base : {$count}");

            if ($session) {
                $this->line("Vague actuelle : {$session->current_wave}");
                $this->line("Prochaine vague dans : {$session->minutes_until_next_wave} min");
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erreur : ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
