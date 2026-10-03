<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(): View
    {
        return view('signalement', ['categories' => Report::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category'    => ['required', 'in:'.implode(',', Report::CATEGORIES)],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'location'    => ['required', 'string', 'max:255'],
        ], [
            'required'   => 'Le champ « :attribute » est obligatoire.',
            'min.string' => 'Le champ « :attribute » doit contenir au moins :min caractères.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'in'         => 'Choisissez un type de problème dans la liste.',
        ], [
            'category' => 'type de problème', 'description' => 'description', 'location' => 'lieu',
        ]);

        $reference = 'SIG-'.strtoupper(Str::random(6));

        Report::create($data + [
            'reference' => $reference,
            'user_id'   => $request->user()->id,
            'title'     => $data['category'].' : '.Str::limit($data['location'], 60),
            'status'    => 'pending',
        ]);

        return redirect(route('signalement').'#confirmation')->with('sent', $reference);
    }

    public function index(Request $request): View
    {
        $cat = $request->query('categorie');
        if ($cat && ! in_array($cat, Report::CATEGORIES, true)) {
            $cat = null;
        }

        return view('demandes.index', [
            'items' => Report::withCount('supports')
                ->when($cat, fn ($b) => $b->where('category', $cat))
                ->orderByRaw("case when status in ('resolved','rejected') then 1 else 0 end")
                ->orderByDesc('supports_count')
                ->latest()
                ->simplePaginate(9)->withQueryString(),
            'cat'        => $cat,
            'categories' => Report::CATEGORIES,
        ]);
    }

    public function show(Request $request, Report $report): View
    {
        $user = $request->user();

        return view('demandes.show', [
            'report'    => $report->loadCount('supports'),
            'mine'      => $user && $report->user_id === $user->id,
            'supported' => $user ? $report->supports()->where('user_id', $user->id)->exists() : false,
        ]);
    }

    public function support(Request $request, Report $report): RedirectResponse
    {
        $to = route('demandes.show', $report).'#soutien';

        if ($report->is_closed) {
            return redirect($to)->with('info', 'Cette demande est clôturée : elle ne peut plus être soutenue.');
        }
        if ($report->user_id === $request->user()->id) {
            return redirect($to)->with('info', 'Vous êtes à l\'origine de cette demande : elle compte déjà.');
        }

        $support = $report->supports()->firstOrCreate(['user_id' => $request->user()->id]);
        $n = $report->supports()->count();

        return redirect($to)->with(
            $support->wasRecentlyCreated ? 'supported' : 'info',
            $support->wasRecentlyCreated
                ? "Votre soutien a bien été pris en compte. Cette demande compte maintenant {$n} soutien".($n > 1 ? 's' : '').'.'
                : 'Vous soutenez déjà cette demande.'
        );
    }
}