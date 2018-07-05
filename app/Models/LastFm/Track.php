<?php

namespace App\Models\LastFm;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Track extends Model
{
    use InsertOnDuplicateKey;

    public $table = 'lastfm_tracks';
}
