<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const APP_KEY = 'app_key';
    const RSA_PUBLIC = 'rsa_public';
    const APP_SECRET = 'app_secret';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const CALLBACK_URL = 'callback_url';
    const NOTIFY_URL = 'notify_url';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::ID,
        self::USER_ID,
        self::APP_KEY,
        self::APP_SECRET,
        self::RSA_PUBLIC,
        self::TITLE,
        self::DESCRIPTION,
        self::CALLBACK_URL,
        self::NOTIFY_URL,
        self::CREATED_AT,
        self::UPDATED_AT,
    ];
}
