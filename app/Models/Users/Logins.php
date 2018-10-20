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
}
