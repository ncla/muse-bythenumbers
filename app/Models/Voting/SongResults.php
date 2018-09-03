<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class SongResults extends Model
{
    use InsertOnDuplicateKey;

    protected $table = 'voting_ballot_song_results';

    protected $primaryKey = 'id';

    const UPDATED_AT = null;
    public $timestamps = true;

    public $incrementing = true;

    public function song()
    {
        return $this->belongsTo('App\Models\Songs', 'song_id', 'id');
    }
}
