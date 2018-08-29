<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Results extends Model
{
    use InsertOnDuplicateKey;

    protected $table = 'voting_ballot_results';

    protected $primaryKey = 'id';

    const UPDATED_AT = null;
    public $timestamps = true;

    public $incrementing = true;

    public function scopeOfVotingBallot($query, $id)
    {
        return $query->where('voting_ballot_id', $id);
    }

    public function songResults()
    {
        return $this->hasMany('App\Models\Voting\SongResults', 'voting_results_id', 'id');
    }

    public function votingBallot()
    {
        return $this->belongsTo('App\Models\Voting', 'voting_ballot_id', 'id');
    }
}
