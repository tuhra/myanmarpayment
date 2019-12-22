<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaOtpValidLog extends Model
{
	protected $table='tbl_ma_otpvalid_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tranId', 'reqBody', 'resBody', 'response_status_code',
    ];
}
