<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginUserModel extends Model
{
    protected $table = 'tbl_login_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password',
    ];
}
