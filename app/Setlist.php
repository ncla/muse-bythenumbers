<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Setlist extends Model
{
    use InsertOnDuplicateKey;

    protected $table = 'setlists';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public $fillable = [
        'date',
        'venue',
        'url',
        'is_utilized'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'date' => 'date',
        'venue' => 'array',
        'url' => 'string',
        'is_utilized' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function songs()
    {
        return $this->hasMany('App\SetlistSong', 'id', 'id');
    }

    public function getVenueFullNameAttribute()
    {
        return $this->venue['name'] . ', ' . $this->venue['city']['name'] . ', ' . $this->venue['city']['country']['name'];
    }
}
