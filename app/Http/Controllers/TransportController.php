<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\View\View;

/** F36 : horaires et état des transports municipaux, sur une seule page. */
class TransportController extends Controller
{
    private const FIRST = 300;   // 05:00
    private const LAST = 1500;   // 01:00 le lendemain
    private const PEAK_HOURS = [7, 8, 17, 18];

    public function __invoke(): View
    {
        $now = Carbon::now(); // force un Carbon mutable

        $lines = collect($this->lines())->map(function (array $l) use ($now) {
            $l['next'] = $this->nextDepartures($l, $now);
            $l['headway_now'] = $this->headway($l, (int) $now->format('G'));

            return $l;
        });

        return view('transports', [
            'lines' => $lines,
            'updatedAt' => $now,
            'disrupted' => $lines->where('status', 'perturbe')->count(),
        ]);
    }

    /** Données provisoires : à remplacer par une table si besoin. */
    private function lines(): array
    {
        return [
            ['code' => 'T1', 'name' => 'Tram 1', 'color' => '#38e8ff', 'route' => 'Dôme Centre ⇄ Port Aqua', 'peak' => 8, 'offpeak' => 15, 'hours' => '5 h – 1 h', 'status' => 'normal', 'note' => null],
            ['code' => 'T2', 'name' => 'Tram 2', 'color' => '#ff7ad0', 'route' => 'Secteur Nord ⇄ Gare Centrale', 'peak' => 10, 'offpeak' => 20, 'hours' => '5 h – 1 h', 'status' => 'normal', 'note' => null],
            ['code' => 'B12', 'name' => 'Bus 12', 'color' => '#ffd36e', 'route' => 'Quartier Sud ⇄ Dôme Centre', 'peak' => 12, 'offpeak' => 25, 'hours' => '5 h – 1 h', 'status' => 'perturbe', 'note' => 'Itinéraire dévié dans le quartier sud : les arrêts des berges ne sont pas desservis.'],
            ['code' => 'N1', 'name' => 'Navette Est', 'color' => '#2dff9a', 'route' => 'Secteur Est ⇄ Dôme Centre', 'peak' => 15, 'offpeak' => 30, 'hours' => '5 h – 1 h', 'status' => 'normal', 'note' => null],
        ];
    }

    private function headway(array $line, int $hour): int
    {
        return in_array($hour, self::PEAK_HOURS, true) ? $line['peak'] : $line['offpeak'];
    }

    /** Prochains départs, alignés sur la fréquence de la ligne (pointe / hors pointe). */
    private function nextDepartures(array $line, Carbon $now, int $count = 3): array
    {
        $out = [];
        $start = $now->copy()->startOfMinute();

        for ($i = 1; $i <= 360 && count($out) < $count; $i++) {
            $t = $start->copy()->addMinutes($i);
            $m = $t->hour * 60 + $t->minute;
            $window = $m < self::FIRST ? $m + 1440 : $m;

            if ($window > self::LAST) {
                continue;
            }
            if ($m % $this->headway($line, $t->hour) === 0) {
                $out[] = $t->format('H:i');
            }
        }

        return $out;
    }
}