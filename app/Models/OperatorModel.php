<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorModel extends Model {

    /**
     * Table Name
     * 
     */
    protected $table = 'tbl_operators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'operator_prefix', 'country_id',
    ];

    public function users() {
        return $this->hasMany('App\Models\UserModel', 'operator_id');
    }

    public function scopeGetOptName($query, $id) {
        return $query->where('id', '=', $id);
    }

}
