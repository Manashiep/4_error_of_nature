<?php

namespace App\Notifications;

use App\Models\Alert;
use Illuminate\Notifications\Notification;

class AlertPublished extends Notification
{
    public function __construct(public Alert $alert) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->alert->title,
            'message' => $this->alert->summary,
            'level' => $this->alert->level,
            'url' => route('home'),
        ];
    }
}