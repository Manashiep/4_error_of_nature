<?php

namespace App\Observers;

use App\Models\Report;
use App\Notifications\ReportStatusChanged;

class ReportObserver
{
    public function updated(Report $report): void
    {
        if (! $report->wasChanged('status') || ! $report->user) {
            return;
        }

        $report->user->notify(new ReportStatusChanged(
            reportReference: $report->reference,
            reportTitle: $report->title ?: $report->category ?: 'Votre signalement',
            oldStatus: $report->getOriginal('status'),
            newStatus: $report->status,
        ));
    }
}
