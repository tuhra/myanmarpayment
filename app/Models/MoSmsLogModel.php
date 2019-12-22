<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoSmsLogModel extends Model
{
	protected $table='tbl_mo_sms_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','mptReqBody','ggResBody',
    ];
}
