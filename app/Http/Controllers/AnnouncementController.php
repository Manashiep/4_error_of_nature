<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Announcement::published()->select('category')->distinct()->orderBy('category')->pluck('category');
        $cat = $request->query('categorie');
        $cat = $categories->contains($cat) ? $cat : null;

        $items = Announcement::published()
            ->when($cat, fn ($q) => $q->where('category', $cat))
            ->latest('published_at')
            ->take(30)
            ->get();

        return view('actualites.index', compact('items', 'categories', 'cat'));
    }

    public function show(Announcement $announcement): View
    {
        abort_if($announcement->published_at->isFuture(), 404);

        return view('actualites.show', compact('announcement'));
    }
}
