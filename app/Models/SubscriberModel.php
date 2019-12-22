<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class SubscriberModel extends Model {

    /**
     * Table Name
     * 
     */
    protected $table = 'tbl_subscribers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'subscription_id', 'subscription_type_id', 'is_new_user', 'is_subscribed', 'is_active', 'valid_date', 'return_date',
    ];

    /**
     * Inverse of the relationship UserModel
     */
    public function user() {
        return $this->belongsTo('App\Models\UserModel');
    }

    public function scopeSubscribe($query, $status) {
        return $query->where('is_subscribed', '=', $status);
    }

    public function scopeUserId($query, $user_id) {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeSubscriptionID($query, $sub_id) {
        return $query->where('subscription_id', '=', $sub_id);
    }

    public function subscribe($user_id) {
        $valid_date = Carbon::now()->AddDays(7);
        $row = SubscriberModel::where('user_id', $user_id)->first();
        $row = new SubscriberModel;
        $row->user_id = $user_id;
        $row->subscription_type_id = getSubscriptionType('Weekly');
        $row->is_new_user = 1;
        $row->is_subscribed = 1;
        $row->is_active = 1;
        $row->valid_date = $valid_date;
        $row->save();
        return $row;
    }
    
    public function updatesubscribe($user_id, $valid_date)
    {
        // $current_date = date("Y-m-d h:i:s");
        // if ($valid_date < $current_date) {
        //     $v_date = Carbon::now()->AddDays(7);
        // } else {
        //     $v_date = $this->customDateAdd($valid_date, "+ 7 day");
        // }

        // $row = SubscriberModel::where('user_id', $user_id)->first();

        // if ($row->is_subscribed == 0) {
        //     $v_date = Carbon::now()->AddDays(7);
        // }

        $v_date = Carbon::now()->AddDays(1);

        SubscriberModel::where('user_id', $user_id)
                ->update([
                    'is_new_user' => 0,
                    'is_subscribed' => 1,
                    'is_active' => 1,
                    'valid_date' => $v_date
        ]);
    }

    private function customDateAdd($existdate, $add_day)
    {
        $new_date = date('Y-m-d', strtotime($existdate . $add_day));
        $new_time = date('H:i:s');
        return $new_date . " " . $new_time;
    }

}







