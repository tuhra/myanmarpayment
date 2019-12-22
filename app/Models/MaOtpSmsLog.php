<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaOtpSmsLog extends Model
{
	protected $table='tbl_ma_otpsms_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tranId', 'reqBody', 'resBody', 'response_status_code',
    ];
}
