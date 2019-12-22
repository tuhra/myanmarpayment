<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelenorChargingLogModel extends Model
{
	protected $table='tbl_charging_logs';

    protected $fillable = [
        'user_id', 'subscription_id', 'request_body','response_body', 
        'is_renewal_request', 'status_code_id', 'amount',
    ];
}
