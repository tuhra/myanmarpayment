<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpModel extends Model
{
    protected $table = 'tbl_mpt_otp';

    protected $fillable = [
        'otp', 'encrypted',
    ];

}
