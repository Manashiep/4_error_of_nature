<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->id;
        $activeStatuses = ['nouveau', 'en_cours', 'en-cours', 'pending', 'in_progress'];
        $completedStatuses = ['traite', 'traitée', 'completed', 'resolved', 'rejected'];

        $reports = Report::query()->where('user_id', $userId);
        $messages = ContactMessage::query()->where('user_id', $userId);

        $activity = (clone $reports)->latest()->limit(5)->get()
            ->map(fn (Report $report) => [
                'type' => 'Signalement',
                'title' => $report->title ?: $report->category,
                'reference' => $report->reference,
                'status' => $report->status,
                'created_at' => $report->created_at,
            ])
            ->concat((clone $messages)->latest()->limit(5)->get()->map(fn (ContactMessage $message) => [
                'type' => 'Message à la mairie',
                'title' => $message->service ?: 'Demande de contact',
                'reference' => $message->reference,
                'status' => $message->status,
                'created_at' => $message->created_at,
            ]))
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        $reportCount = $reports->count();
        $messageCount = $messages->count();
        $activeCount = (clone $reports)->whereIn('status', $activeStatuses)->count()
            + (clone $messages)->whereIn('status', $activeStatuses)->count();
        $completedCount = (clone $reports)->whereIn('status', $completedStatuses)->count()
            + (clone $messages)->whereIn('status', $completedStatuses)->count();

        return view('dashboard', [
            'displayName' => $request->user()->firstname ?: $request->user()->name,
            'activity' => $activity,
            'stats' => [
                'total' => $reportCount + $messageCount,
                'in_progress' => $activeCount,
                'completed' => $completedCount,
            ],
        ]);
    }
}
