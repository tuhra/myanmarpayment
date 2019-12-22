<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelenorPaymentLogModel extends Model
{
       /**
		*Table Name
		* 
		*/
		protected $table='tbl_telenor_payment_log';
	     /**
	     * The attributes that are mass assignable.
	     *
	     * @var array
	     */
	    protected $fillable = [
	        'user_id', 'refCode', 'request_msg','response_msg','response_status_code',
	    ];
}
