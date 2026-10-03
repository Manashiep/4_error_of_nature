<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public string $reportReference,
        public string $reportTitle,
        public ?string $oldStatus,
        public string $newStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_reference' => $this->reportReference,
            'report_title' => $this->reportTitle,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_label' => Report::STATUSES[$this->newStatus] ?? $this->newStatus,
            'message' => 'Le statut de votre signalement a été mis à jour : '.(Report::STATUSES[$this->newStatus] ?? $this->newStatus).'.',
        ];
    }
}
