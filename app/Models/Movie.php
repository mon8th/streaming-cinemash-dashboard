<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'release_date',
        'vote_average',
        'vote_count',
        'genres',
        'trailer_url',
        'watch_link'
    ];

    protected $casts = ['genre' => 'array'];

    public function streamingProviders()
    {
        return $this->hasMany(StreamingProvider::class);
    }
}
