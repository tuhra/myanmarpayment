<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoUnsubLog extends Model
{
	protected $table='tbl_ma_unsub_log';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tranid', 'reqBody', 'resBody', 'status_code',
    ];
}
