<?php
namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        return response()->json(
            Movie::with('streamingProviders')->paginate(20)
        );
    }

    public function show($id)
    {
        $movie = Movie::with('streamingProviders')->findOrFail($id);
        return response()->json($movie);
    }
}
