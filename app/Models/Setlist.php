<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yadakhov\InsertOnDuplicateKey;

class Setlist extends Model
{
    use InsertOnDuplicateKey;
    use SoftDeletes;

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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function songs()
    {
        return $this->hasMany('App\Models\SetlistSong', 'id', 'id');
    }

    public function getVenueFullNameAttribute()
    {
        return $this->venue['name'] . ', ' . $this->venue['city']['name'] . ', ' . $this->venue['city']['country']['name'];
    }
}
