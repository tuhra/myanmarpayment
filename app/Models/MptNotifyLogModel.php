<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MptNotifyLogModel extends Model
{
	protected $table='tbl_mpt_sms_notify_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'deliveryStatus', 'mptReqBody','ggResBody',
    ];
}
