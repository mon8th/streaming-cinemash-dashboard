<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\StreamingProvider;
use Illuminate\Http\Request;

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

    public function updateProvider(Request $request, $id)
    {
        $provider = StreamingProvider::findOrFail($id);
        $provider->update([
            'link' => $request->input('link')
        ]);
        return response()->json($provider);
    }

    public function addProvider(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $provider = StreamingProvider::create([
            'movie_id' => $movie->id,
            'provider_name' => $request->input('provider_name'),
            'link' => $request->input('link'),
            'type' => $request->input('type', 'flatrate'),
            'logo_path' => $request->input('logo_path', ''),
            'provider_id' => 0,
            'region' => 'US'
        ]);
        return response()->json($provider);
    }

    public function deleteProvider($id)
    {
        $provider = StreamingProvider::findOrFail($id);
        $provider->delete();
        return response()->json(['success' => true]);
    }
}
