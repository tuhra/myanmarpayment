<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
        /**
    	*Table Name
    	* 
    	*/
    	protected $table='tbl_countries';
         /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name',
        ];
}
