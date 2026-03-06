<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StreamingProvider extends Model {
    protected $fillable = [
        'movie_id', 'provider_name', 'provider_id',
        'logo_path', 'type', 'region'
    ];

    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}
