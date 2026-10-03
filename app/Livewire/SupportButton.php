<?php

namespace App\Livewire;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SupportButton extends Component
{
    public Report $report;
    public int $count = 0;
    public bool $supported = false;
    public ?string $message = null;

    public function mount(Report $report): void
    {
        $this->report = $report;
        $this->count = $report->supports()->count();
        $this->supported = Auth::check()
            && $report->supports()->where('user_id', Auth::id())->exists();
    }

    public function toggle()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $report = $this->report->fresh();
        $this->message = null;

        if ($report->is_closed) {
            $this->message = 'Cette demande est clôturée : elle ne peut plus être soutenue.';
            return;
        }
        if ($report->user_id === Auth::id()) {
            $this->message = 'Vous êtes à l\'origine de cette demande : elle compte déjà.';
            return;
        }

        $mine = $report->supports()->where('user_id', Auth::id());

        if ($mine->exists()) {
            $mine->delete();
            $this->supported = false;
        } else {
            $report->supports()->create(['user_id' => Auth::id()]);
            $this->supported = true;
        }

        $this->count = $report->supports()->count();
    }

    public function render()
    {
        return view('livewire.support-button');
    }
}