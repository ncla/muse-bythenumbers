<?php

namespace App\Models\Voting;

use Illuminate\Database\Eloquent\Model;

class Songs extends Model
{
    protected $table = 'voting_ballot_songs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public $incrementing = true;

    public $fillable = ['voting_ballot_id', 'song_id'];
}
