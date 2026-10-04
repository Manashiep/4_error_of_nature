<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMessage;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $appointments = Contact::query()
            ->with('service')
            ->where('user_id', $userId)
            ->where('type', 'service')
            ->orderByRaw(
                "CASE WHEN status = 'traite' AND confirmed_at >= ? THEN 0 WHEN status = 'nouveau' THEN 1 WHEN status = 'en_cours' THEN 2 WHEN status = 'traite' THEN 3 ELSE 4 END",
                [now()],
            )
            ->orderBy('confirmed_at')
            ->latest()
            ->paginate(10, ['*'], 'appointmentsPage')
            ->withQueryString();

        $reportActivity = DB::table('reports')
            ->where('user_id', $userId)
            ->selectRaw("'Signalement' as type, COALESCE(NULLIF(title, ''), category) as title, reference, status, created_at");
        $messageActivity = DB::table('contact_messages')
            ->where('user_id', $userId)
            ->selectRaw("'Message à la mairie' as type, COALESCE(NULLIF(service, ''), 'Demande de contact') as title, reference, status, created_at");
        $activity = DB::query()
            ->fromSub($reportActivity->unionAll($messageActivity), 'activity')
            ->orderByDesc('created_at')
            ->orderByDesc('reference')
            ->paginate(10, ['*'], 'activityPage')
            ->withQueryString();

        $reportCount = $reports->count();
        $messageCount = $messages->count();
        $activeCount = (clone $reports)->whereIn('status', $activeStatuses)->count()
            + (clone $messages)->whereIn('status', $activeStatuses)->count();
        $completedCount = (clone $reports)->whereIn('status', $completedStatuses)->count()
            + (clone $messages)->whereIn('status', $completedStatuses)->count();

        return view('dashboard', [
            'displayName' => $request->user()->firstname ?: $request->user()->name,
            'activity' => $activity,
            'appointments' => $appointments,
            'stats' => [
                'total' => $reportCount + $messageCount,
                'in_progress' => $activeCount,
                'completed' => $completedCount,
            ],
        ]);
    }
}
