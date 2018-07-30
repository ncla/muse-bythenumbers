<?php

namespace App\Models\Spotify;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class TopTrack extends Model
{
    use InsertOnDuplicateKey;

    public $table = 'spotify_top_tracks';
}
