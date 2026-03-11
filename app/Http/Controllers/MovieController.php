<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\StreamingProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function coreMovies(Request $request)
    {
        $url = 'http://47.129.2.187/api/public/movies';
        $response = Http::timeout(15)->get($url, ['limit' => $request->query('limit', 100)]);
        return response($response->body(), $response->status())
            ->header('Content-Type', 'application/json');
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 100);
        return response()->json(
            Movie::with('streamingProviders')->paginate($perPage)
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
        $provider->update(['link' => $request->input('link')]);
        return response()->json($provider);
    }

    public function addProvider(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $provider = $movie->streamingProviders()->create([
            'provider_name' => $request->input('provider_name'),
            'link'          => $request->input('link'),
            'type'          => $request->input('type', 'flatrate'),
            'logo_path'     => $request->input('logo_path', ''),
            'provider_id'   => 0,
            'region'        => 'SG',
        ]);

        return response()->json($provider, 201);
    }

    public function deleteProvider($id)
    {
        StreamingProvider::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
