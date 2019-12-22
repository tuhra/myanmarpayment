<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    	protected $table='tbl_callback';
         /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'url',
        ];
}
