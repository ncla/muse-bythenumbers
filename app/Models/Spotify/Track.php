<?php

namespace App\Models\Spotify;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Track extends Model
{
    use InsertOnDuplicateKey;

    public $table = 'spotify_tracks';
}
