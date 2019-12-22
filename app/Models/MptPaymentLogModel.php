<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MptPaymentLogModel extends Model
{
	protected $table='tbl_mpt_payment_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'referenceCode', 'clientCorrelator', 'reqBody', 'resBody', 'response_status_code',
    ];
}
