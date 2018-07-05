<?php

namespace App\Models\Spotify;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Album extends Model
{
    use InsertOnDuplicateKey;

    public $table = 'spotify_albums';
}
