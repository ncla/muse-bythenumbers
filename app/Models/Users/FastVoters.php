<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class FastVoters extends Model
{
    public $table = 'users_fast_voters';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    protected $fillable = [
        'user_id',
        'time_between_votes',
        'time_between_votes_client',
        'browser_useragent',
        'ip_address'
    ];

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
