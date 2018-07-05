<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;

class Matchups extends Model
{
    protected $table = 'voting_matchups';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public $incrementing = true;

    public $fillable = ['voting_ballot_id', 'songA_id', 'songB_id'];

    public function votes()
    {
        return $this->hasMany('App\Models\Voting\Votes', 'voting_matchup_id', 'id');
    }
}
