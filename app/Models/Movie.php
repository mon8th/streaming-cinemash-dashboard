<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'title',
        'trailer_url',
        'watch_link',
    ];

    public function streamingProviders()
    {
        return $this->hasMany(StreamingProvider::class);
    }
}
