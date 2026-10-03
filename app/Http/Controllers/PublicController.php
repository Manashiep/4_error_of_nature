<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Annoncements;
use App\Models\GlossaryTerm;
use App\Models\Report;
use App\Models\Service;
use App\Models\Transport;
use Illuminate\Http\Request;

/** Pages publiques (sans connexion) : tout vient de la base, rien en dur. */
class PublicController extends Controller
{
    // D07 / D05 / D06 / F28
    public function home()
    {
        return view('home', [
            'alerts' => Alert::current()->urgentFirst()->take(5)->get(),
            'services' => Service::ranked()->take(6)->get(),
            'topViewed' => Service::orderByDesc('views_count')->take(3)->get(),
            'terms' => GlossaryTerm::orderBy('sort_order')->take(6)->get(),
            'langs' => Service::languages(),
            'reports' => Report::withCount('supports')
                ->whereNotIn('status', ['resolved', 'rejected'])
                ->orderByDesc('is_urgent')
                ->orderByDesc('supports_count')->latest()->take(3)->get(),
            'annonces' => Annoncements::published()->latest('published_at')->take(4)->get(),
            'stats' => [
                'services' => Service::count(),
                'actifs' => Service::where('is_active', true)->count(),
                'annonces' => Annoncements::published()->count(),
            ],
        ]);
    }

    // D05 / F32 : liste + recherche + catégories
    public function services(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $cat = $request->query('cat');

        $services = Service::query()
            ->when($q !== '', fn ($b) => $b->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('short_description', 'like', "%{$q}%")
                ->orWhere('category', 'like', "%{$q}%")))
            ->when($cat, fn ($b) => $b->where('category', $cat))
            ->ranked()->get();

        return view('services.index', [
            'services' => $services,
            'categories' => Service::query()->distinct()->orderBy('category')->pluck('category'),
            'q' => $q,
            'cat' => $cat,
        ]);
    }

    // Page Transport : lignes, état du trafic, alertes et services
    public function transport(Request $request)
    {
        $types = Transport::active()->distinct()->orderBy('type')->pluck('type');
        $type = $request->query('type');
        $type = $types->contains($type) ? $type : null;

        return view('transport', [
            'lines' => Transport::active()
                ->when($type, fn ($b) => $b->where('type', $type))
                ->orderBy('code')
                ->get(),
            'types' => $types,
            'type' => $type,
            'disrupted' => Transport::active()->disrupted()->orderBy('code')->get(),
            'alerts' => Annoncements::published()
                ->whereIn('category', ['Travaux', 'Alerte'])
                ->latest('published_at')
                ->take(3)
                ->get(),
            'services' => Service::where('category', 'Transport')->ranked()->get(),
        ]);
    }

    // Détail d'un service (F28 : chaque consultation le fait remonter, F38 : indisponibilité)
    public function service(Service $service)
    {
        $service->increment('views_count');

        return view('services.show', [
            'service' => $service,
            'related' => Service::where('category', $service->category)->whereKeyNot($service->id)->ranked()->take(3)->get(),
        ]);
    }

    // D06 : liste des annonces
    public function announcements(Request $request)
    {
        $categories = Annoncements::published()->distinct()->orderBy('category')->pluck('category');
        $cat = $request->query('categorie');
        $cat = $categories->contains($cat) ? $cat : null;

        return view('actualites.index', [
            'items' => Annoncements::published()
                ->when($cat, fn ($b) => $b->where('category', $cat))
                ->latest('published_at')
                ->simplePaginate(8)
                ->withQueryString(),
            'categories' => $categories,
            'cat' => $cat,
        ]);
    }

    // Détail d'une annonce
    public function announcement(Annoncements $announcement)
    {
        abort_unless(
            $announcement->is_published && $announcement->published_at?->isPast(),
            404
        );

        return view('actualites.show', [
            'announcement' => $announcement,
            'others' => Annoncements::published()
                ->whereKeyNot($announcement->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    // D04
    public function contact(Request $request)
    {
        return view('contact', [
            'services' => Service::orderBy('name')->get(),
            'selected' => $request->query('service'),
        ]);
    }

    // D14 : choix de la langue
    public function lang(string $code)
    {
        abort_unless(preg_match('/^[a-z]{2}$/', $code), 404);
        session(['lang' => $code]);

        return back();
    }
}