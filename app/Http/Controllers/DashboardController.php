<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMovies = Movie::count();
        $recentlyAdded = Movie::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $topRated = Movie::where('vote_average', '>=', 8.0)->count();

        $latestMovies = Movie::with('streamingProviders')
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('index', [
            'stats' => [
                'totalMovies' => $totalMovies,
                'recentlyAdded' => $recentlyAdded,
                'topRated' => $topRated,
            ],
            'latestMovies' => $latestMovies,
        ]);
    }
}
