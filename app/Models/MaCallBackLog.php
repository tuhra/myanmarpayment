<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaCallBackLog extends Model
{
    	protected $table='tbl_ma_callback_log';
         /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'user_id', 'reqBody', 'resBody', 'status_code', 'tranid', 'message', 'link',
        ];
}
