<?php

namespace App\Models;

use Eloquent as Model;
use Carbon\Carbon;

/**
 * Class Voting
 * @package App\Models
 * @version May 7, 2018, 12:44 am UTC
 *
 * @property string name
 * @property string description
 * @property string songs
 * @property boolean is_open
 * @property string|\Carbon\Carbon expires_on
 * @property string type
 */
class Voting extends Model
{

    public $table = 'voting_ballots';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'description',
        'is_open',
        'expires_on',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'is_open' => 'boolean',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function songs()
    {
        return $this->hasMany('App\Models\Voting\Songs', 'voting_ballot_id', 'id');
    }

    public function matchups()
    {
        return $this->hasMany('App\Models\Voting\Matchups', 'voting_ballot_id', 'id');
    }

    public function results()
    {
        return $this->hasMany('App\Models\Voting\Results', 'voting_ballot_id', 'id');
    }

    public function getOpenStatusAttribute()
    {
        return $this->is_open === true && Carbon::now()->lessThanOrEqualTo(Carbon::parse($this->expires_on));
    }

    public function scopeClosed($query)
    {
        return $query->where($this->table . '.is_open', '=', false)
            ->orWhere($this->table . '.expires_on', '<=', Carbon::now());
    }

}
