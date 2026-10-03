<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\ReportSupport;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsList extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'categorie', except: '')]
    public string $categorie = '';

    #[Url(as: 'statut', except: '')]
    public string $statut = '';

    #[Url(as: 'tri', except: 'soutenues')]
    public string $tri = 'soutenues';

    public ?int $noticeFor = null;
    public ?string $notice = null;

    public function updated($name): void
    {
        if (in_array($name, ['search', 'categorie', 'statut', 'tri'], true)) {
            $this->resetPage();
        }
    }

    public function setCategorie(string $value): void
    {
        $this->categorie = $value;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'categorie', 'statut']);
        $this->tri = 'soutenues';
        $this->resetPage();
    }

    public function toggleSupport(int $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $report = Report::findOrFail($id);
        $this->noticeFor = $id;
        $this->notice = null;

        if ($report->is_closed) {
            $this->notice = 'Cette demande est clôturée : elle ne peut plus être soutenue.';
            return;
        }
        if ($report->user_id === Auth::id()) {
            $this->notice = 'Vous êtes à l\'origine de cette demande : elle compte déjà.';
            return;
        }

        $mine = $report->supports()->where('user_id', Auth::id());

        if ($mine->exists()) {
            $mine->delete();
        } else {
            $report->supports()->create(['user_id' => Auth::id()]);
        }
    }

    public function render()
    {
        $s = trim($this->search);

        $query = Report::query()
            ->withCount('supports')
            ->when($s !== '', function ($q) use ($s) {
                $q->where(function ($w) use ($s) {
                    $w->where('title', 'like', "%{$s}%")
                        ->orWhere('description', 'like', "%{$s}%")
                        ->orWhere('location', 'like', "%{$s}%")
                        ->orWhere('reference', 'like', "%{$s}%");
                });
            })
            ->when(
                in_array($this->categorie, Report::CATEGORIES, true),
                fn ($q) => $q->where('category', $this->categorie)
            )
            ->when(
                array_key_exists($this->statut, Report::STATUSES),
                fn ($q) => $q->where('status', $this->statut)
            );

        if ($this->tri === 'recentes') {
            $query->latest();
        } else {
            $query->orderByRaw("case when status in ('resolved','rejected') then 1 else 0 end")
                ->orderByDesc('supports_count')
                ->latest();
        }

        return view('livewire.reports-list', [
            'items' => $query->simplePaginate(9),
            'categories' => Report::CATEGORIES,
            'statuses' => Report::STATUSES,
            'supportedIds' => Auth::check()
                ? ReportSupport::where('user_id', Auth::id())->pluck('report_id')->all()
                : [],
        ]);
    }
}