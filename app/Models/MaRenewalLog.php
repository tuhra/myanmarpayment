<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaRenewalLog extends Model
{
	protected $table='tbl_ma_renewal_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'msisdn', 'tranid', 'chargeAmount', 'validityDays', 'operationId', 'status_code', 'result',
    ];
}
