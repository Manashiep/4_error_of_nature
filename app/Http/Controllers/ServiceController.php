<?php

namespace App\Http\Controllers;

use App\Support\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $cat = (string) $request->query('cat', 'all');
        if ($cat !== 'all' && ! array_key_exists($cat, ServiceCatalog::CATEGORIES)) {
            $cat = 'all';
        }

        return view('services.index', [
            'services' => ServiceCatalog::search($q, $cat),
            'q' => $q,
            'cat' => $cat,
            'categories' => ServiceCatalog::CATEGORIES,
        ]);
    }
}
