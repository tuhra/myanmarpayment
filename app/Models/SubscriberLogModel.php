<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberLogModel extends Model
{
    /**
	* Table Name
	* 
	*/
	protected $table='tbl_subscribers_logs';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','attempt_type','channel_id','attempt_type_status', 'subscription_id','event'
    ];
}
