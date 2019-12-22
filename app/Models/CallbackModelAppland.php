<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallbackModelAppland extends Model
{
        /**
    	*Table Name
    	* 
    	*/
    	protected $table='tbl_callback_token_logs';
         /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'user_id', 'user_info', 'raw_request', 'raw_response'
        ];
}
