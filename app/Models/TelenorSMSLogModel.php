<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelenorSMSLogModel extends Model
{
     /**
	*Table Name
	* 
	*/
	protected $table='tbl_telenor_sms_logs';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','request_msg','response_msg','response_status_code',
    ];
}
