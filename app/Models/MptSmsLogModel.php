<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MptSmsLogModel extends Model
{
       
	protected $table='tbl_mpt_sms_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','reqBody','resBody','response_status_code',
    ];
}
