<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMovies   = Movie::count();
        $recentlyAdded = Movie::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $withTrailer   = Movie::whereNotNull('trailer_url')->count();
        $latestMovies  = Movie::with('streamingProviders')->latest()->take(6)->get();

        return Inertia::render('index', [
            'stats' => [
                'totalMovies'   => $totalMovies,
                'recentlyAdded' => $recentlyAdded,
                'withTrailer'   => $withTrailer,
            ],
            'latestMovies' => $latestMovies,
        ]);
    }
}
