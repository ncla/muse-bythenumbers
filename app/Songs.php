<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Songs extends Model
{
    public $table = 'musicbrainz_songs';

    public $fillable = [
        'mbid',
        'name',
        'name_override',
        'name_spotify_override',
        'name_lastfm_override',
        'name_setlistfm_override',
        'manually_added',
        'is_utilized'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mbid' => 'string',
        'name' => 'string',
        'name_override' => 'string',
        'manually_added' => 'boolean',
        'is_utilized' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function getFinalNameAttribute()
    {
        return $this->name_override ?? $this->name;
    }

    public function getSetlistNameAttribute()
    {
        return $this->setlist_name_override ?? $this->name_override ?? $this->name;
    }

    public function getLastfmNameAttribute()
    {
        return $this->lastfm_name_override ?? $this->name_override ?? $this->name;
    }
}
