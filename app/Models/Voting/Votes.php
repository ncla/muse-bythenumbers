<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Votes extends Model
{
    use InsertOnDuplicateKey;

    protected $table = 'votes';

    protected $primaryKey = 'id';

    public $timestamps = true;

    public $incrementing = true;

    public $fillable = ['user_id', 'voting_matchup_id', 'winner_song_id'];
}
