<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class SyncMovies extends Command
{
    protected $signature = 'movies:sync';
    protected $description = 'Sync movies from Cinemesh + streaming providers from TMDB';

    protected $tmdbKey;
    protected $tmdbBase = 'https://api.themoviedb.org/3';

    public function handle()
    {
        $this->tmdbKey = config('services.tmdb.api_key');
        $page = 1;

        do {
            $baseUrl = rtrim(config('services.cinemesh_core.base_url'), '/');
            $res = Http::get($baseUrl . '/movies', [
                'page' => $page,
                'limit' => 100
            ])->json();

            foreach ($res['data'] as $m) {
                $tmdbId = $this->searchTmdbId($m['Title'], $m['ReleaseDate']);

                if (!$tmdbId) {
                    $this->warn(" No TMDB match: {$m['Title']}");
                    continue;
                }

                $movie = Movie::updateOrCreate(
                    ['tmdb_id' => $tmdbId],
                    [
                        'title'            => $m['Title'],
                        'overview'         => $m['Synopsis'],
                        'poster_path'      => $m['PosterURL'],
                        'backdrop_path'    => $m['BackdropURL'],
                        'release_date'     => $m['ReleaseDate'] ? date('Y-m-d', strtotime($m['ReleaseDate'])) : null,
                        'vote_average'     => $m['AverageRating'],
                        'genres'           => json_encode($m['Genres']),
                    ]
                );

                $providers = Http::get("{$this->tmdbBase}/movie/{$tmdbId}/watch/providers", [
                    'api_key' => $this->tmdbKey
                ])->json();

                $usData = $providers['results']['TH'] ?? [];
                $flatrate = array_merge(
                    array_map(fn($p) => array_merge($p, ['type' => 'flatrate']), $usData['flatrate'] ?? []),
                    array_map(fn($p) => array_merge($p, ['type' => 'rent']), $usData['rent'] ?? []),
                    array_map(fn($p) => array_merge($p, ['type' => 'buy']), $usData['buy'] ?? [])
                );
                $watchLink = $usData['link'] ?? null;

                $movie->streamingProviders()->delete();
                foreach ($flatrate as $p) {
                    $movie->streamingProviders()->create([
                        'provider_name' => $p['provider_name'],
                        'provider_id'   => $p['provider_id'],
                        'logo_path'     => $p['logo_path'],
                        'type'          => $p['type'],
                        'region'        => 'TH',
                    ]);
                }

                    $videos = Http::timeout(10)->get("{$this->tmdbBase}/movie/{$tmdbId}/videos", [
                        'api_key' => $this->tmdbKey
                    ])->json();

                    $trailer = collect($videos['results'] ?? [])
                        ->first(fn($v) => $v['type'] === 'Trailer' && $v['site'] === 'YouTube');

                    $movie->update([
                        'trailer_url' => $trailer ? 'https://www.youtube.com/watch?v=' . $trailer['key'] : null,
                        'watch_link'  => $watchLink
                    ]);

                $this->info("✓ {$m['Title']} (TMDB: $tmdbId, providers: " . count($flatrate) . ")");
                sleep(1);
            }

            $page++;
        } while ($page <= $res['pagination']['totalPages']);

        $this->info('Done!');
    }

    private function searchTmdbId($title, $releaseDate)
    {
        $year = $releaseDate ? date('Y', strtotime($releaseDate)) : null;

        $res = Http::get("{$this->tmdbBase}/search/movie", [
            'api_key' => $this->tmdbKey,
            'query'   => $title,
            'year'    => $year,
        ])->json();

        return $res['results'][0]['id'] ?? null;
    }
}
