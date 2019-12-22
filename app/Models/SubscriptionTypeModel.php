<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTypeModel extends Model
{
    /**
	*Table Name
	* 
	*/
	protected $table='tbl_subscription_type';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'amount', 'extra', 
    ];
    public function scopeName($query,$name)
    {
        return $query->where('name', '=', $name);
    }
}
