<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    public $timestamps = true;

    protected $fillable = [
        'fullName',
        'username',
        'password',
        'role',
        'is_active',
    ];
}
