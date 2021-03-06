<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Logins extends Model
{
    public $table = 'users_logins';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    protected $fillable = [
        'user_id',
        'browser_useragent',
        'ip_address'
    ];

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
