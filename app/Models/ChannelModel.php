<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelModel extends Model
{
	/**
	*Table Name
	* 
	*/
	protected $table='tbl_channel_name';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function scopeChannel($query,$name)
    {
        return $query->where('name', '=', $name);
    }
}
