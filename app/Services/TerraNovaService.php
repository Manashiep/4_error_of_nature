<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\TerraRequest;
use App\Models\TerraSession;

class TerraNovaService
{
    public function fetchAndSaveRequests(): void
    {
        $apiKey = env('WEBCUP_API_KEY');

        // Appel API Webcup avec le header X-API-Key (conforme doc Notion)
        $response = Http::withHeaders([
            'X-Webcup-Api-Key' => $apiKey,
        ])
       ->timeout(30)          // timeout total
->connectTimeout(15)   // timeout de connexion
->retry(3, 2000)       // 3 tentatives, 2 secondes entre chaque
        ->get('https://24h.webcup.fr/wp-json/webcup/v1/requests');

        if ($response->successful()) {
            $data = $response->json();

            // 1. Sauvegarde des métadonnées de Session
            if (isset($data['session'])) {
                $sess = $data['session'];
                TerraSession::updateOrCreate(
                    ['id' => 1],
                    [
                        'status'                  => $sess['status'] ?? 'active',
                        'is_running'              => (bool)($sess['is_running'] ?? true),
                        'current_wave'            => $sess['current_wave'] ?? 0,
                        'elapsed_minutes'         => $sess['elapsed_minutes'] ?? 0,
                        'visible_requests_count'  => $sess['visible_requests_count'] ?? 0,
                        'initial_requests_count'  => $sess['initial_requests_count'] ?? 0,
                        'wave_requests_count'     => $sess['wave_requests_count'] ?? 0,
                        'next_wave_number'        => $sess['next_wave_number'] ?? 1,
                        'minutes_until_next_wave' => $sess['minutes_until_next_wave'] ?? 0,
                    ]
                );
            }

            // 2. Sauvegarde des Demandes
            $requests = $data['requests'] ?? [];
            foreach ($requests as $req) {
                TerraRequest::updateOrCreate(
                    ['request_code' => $req['request_code']],
                    [
                        'id'                 => $req['id'],
                        'requester_name'     => $req['requester_name'] ?? null,
                        'requester_type'     => $req['requester_type'] ?? null,
                        'message_public'     => $req['message_public'] ?? '',
                        'difficulty'         => $req['difficulty'] ?? null,
                        'xp_base'            => $req['xp_base'] ?? 0,
                        'xp_time_bonus'      => $req['xp_time_bonus'] ?? 0,
                        'xp_total'           => $req['xp_total'] ?? 0,
                        'is_ai_related'      => $req['is_ai_related'] ?? 0,
                        'arrival_type'       => $req['arrival_type'] ?? null,
                        'wave_number'        => $req['wave_number'] ?? null,
                        'arrival_time'       => $req['arrival_time'] ?? '',
                        'group_name'         => $req['group_name'] ?? null,
                        'sort_order'         => $req['sort_order'] ?? 0,
                        'is_initial'         => (bool)($req['is_initial'] ?? false),
                        'is_ai_request'      => (bool)($req['is_ai_request'] ?? false),
                        'difficulty_level'   => $req['difficulty_level'] ?? 1,
                        'xp_available'       => $req['xp_available'] ?? 0,
                        'visible_since_wave' => $req['visible_since_wave'] ?? 0,
                    ]
                );
            }
        }
    }
}
