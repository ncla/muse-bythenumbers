<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetlistSong extends Model
{
    protected $table = 'setlist_songs';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    public function setlist()
    {
        return $this->belongsTo('App\Models\Setlist', 'name');
    }
}
