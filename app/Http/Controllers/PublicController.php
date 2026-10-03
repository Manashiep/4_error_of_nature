<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\GlossaryTerm;
use App\Models\Report;
use App\Models\Service;
use Illuminate\Http\Request;

/** Pages publiques (sans connexion) : tout vient de la base, rien en dur. */
class PublicController extends Controller
{
    private function published()
    {
        return Announcement::query()->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    // D07 / D05 / D06 / F28
    public function home()
    {
        return view('home', [
            'services' => Service::ranked()->take(6)->get(),
            'topViewed' => Service::orderByDesc('views_count')->take(3)->get(),
            'terms' => GlossaryTerm::orderBy('sort_order')->take(6)->get(),
            'langs' => Service::languages(),
            'reports' => Report::withCount('supports')
                ->whereNotIn('status', ['resolved', 'rejected'])
                ->orderByDesc('supports_count')->latest()->take(3)->get(),
            'annonces' => $this->published()->latest('published_at')->take(4)->get(),
            'stats' => [
                'services' => Service::count(),
                'actifs' => Service::where('is_active', true)->count(),
                'annonces' => $this->published()->count(),
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

    // Page Transport : les services de la catégorie « Transport »
    public function transport()
    {
        return view('transport', [
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

    // D06
    public function announcements(Request $request)
    {
        $cat = $request->query('categorie');

        return view('actualites.index', [
            'items' => $this->published()->when($cat, fn ($b) => $b->where('category', $cat))
                ->latest('published_at')->simplePaginate(8)->withQueryString(),
            'categories' => $this->published()->distinct()->orderBy('category')->pluck('category'),
            'cat' => $cat,
        ]);
    }

    public function announcement(Announcement $announcement)
    {
        abort_unless($announcement->published_at && $announcement->published_at->isPast(), 404);

        return view('actualites.show', [
            'announcement' => $announcement,
            'others' => $this->published()->whereKeyNot($announcement->id)->latest('published_at')->take(3)->get(),
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