<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model {

    /**
     * Table Name
     * 
     */
    protected $table = 'tbl_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'operator_id', 'plain_msisdn', 'encrypted_msisdn',
    ];

    /**
     * One to One relationship with subscriber table
     */
    public function subscriber() {
        return $this->hasOne('App\Models\SubscriberModel', 'user_id');
    }

    public function operator() {
        return $this->belongsTo('App\Models\OperatorModel');
    }

    public function scopeEncMSISDN($query, $encrypted_msisdn) {
        return $query->where('encrypted_msisdn', '=', $encrypted_msisdn);
    }

    public function scopeMSISDN($query, $msisdn) {
        return $query->where('plain_msisdn', '=', $msisdn);
    }

    public function scopeID($query, $id) {
        return $query->where('id', '=', $id);
    }

    public function scopeEndUserId($query, $endUserId) {
        return $query->where('endUserId', '=', $endUserId);
    }

}
