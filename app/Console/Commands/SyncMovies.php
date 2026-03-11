<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class SyncMovies extends Command
{
    protected $signature = 'movies:sync';
    protected $description = 'Sync streaming providers and trailers from TMDB';

    protected $tmdbKey;
    protected $tmdbBase = 'https://api.themoviedb.org/3';

    public function handle()
    {
        $this->tmdbKey = config('services.tmdb.api_key');
        $page = 1;

        do {
            $baseUrl = rtrim(env('CINEMESH_CORE_API'), '/');
            $res = Http::get($baseUrl . '/movies', [
                'page'  => $page,
                'limit' => 100,
            ])->json();

            foreach ($res['data'] as $m) {
                $title       = $m['Title'];
                $releaseDate = $m['ReleaseDate'] ?? null;

                $tmdbId = $m['tmdb_id'] ?? $this->searchTmdbId($title, $releaseDate);

                if (!$tmdbId) {
                    $this->warn("  No TMDB match: {$title}");
                    continue;
                }

                $movie = Movie::firstOrCreate(
                    ['tmdb_id' => $tmdbId],
                    ['title'   => $title]
                );

                $this->syncProviders($movie, $tmdbId);
                $this->syncTrailer($movie, $tmdbId);

                $this->info("✓ {$title} (TMDB: {$tmdbId})");
                sleep(1);
            }

            $page++;
        } while ($page <= ($res['pagination']['totalPages'] ?? 1));

        $this->info('Done!');
    }

    private function syncProviders(Movie $movie, int $tmdbId): void
    {
        $res     = Http::get("{$this->tmdbBase}/movie/{$tmdbId}/watch/providers", [
            'api_key' => $this->tmdbKey,
        ])->json();

        $thData  = $res['results']['SG'] ?? [];
        $watchLink = $thData['link'] ?? null;

        $incoming = array_merge(
            array_map(fn($p) => array_merge($p, ['type' => 'flatrate']), $thData['flatrate'] ?? []),
            array_map(fn($p) => array_merge($p, ['type' => 'rent']),     $thData['rent']     ?? []),
            array_map(fn($p) => array_merge($p, ['type' => 'buy']),      $thData['buy']      ?? []),
        );

        $incomingIds = collect($incoming)->pluck('provider_id')->toArray();

        // Remove providers TMDB no longer returns
        $movie->streamingProviders()
            ->whereNotIn('provider_id', $incomingIds)
            ->delete();

        foreach ($incoming as $p) {
            $movie->streamingProviders()->updateOrCreate(
                ['provider_id' => $p['provider_id'], 'type' => $p['type']],
                [
                    'provider_name' => $p['provider_name'],
                    'logo_path'     => $p['logo_path'],
                    'region'        => 'TH',
                    'link'          => $movie->streamingProviders()
                        ->where('provider_id', $p['provider_id'])
                        ->where('type', $p['type'])
                        ->value('link'), // preserve custom link if set
                ]
            );
        }

        $movie->update(['watch_link' => $watchLink]);
    }

    private function syncTrailer(Movie $movie, int $tmdbId): void
    {
        $res = Http::timeout(10)->get("{$this->tmdbBase}/movie/{$tmdbId}/videos", [
            'api_key' => $this->tmdbKey,
        ])->json();

        $trailer = collect($res['results'] ?? [])
            ->first(fn($v) => $v['type'] === 'Trailer' && $v['site'] === 'YouTube');

        $movie->update([
            'trailer_url' => $trailer
                ? 'https://www.youtube.com/watch?v=' . $trailer['key']
                : null,
        ]);
    }

    private function searchTmdbId(string $title, ?string $releaseDate): ?int
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
