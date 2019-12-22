<?php

namespace App\Helper;

use App;
use Session;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;

class SubHelper 
{
	public function subscriber_creation($user_id, $valid) {
	    $row = new SubscriberModel;
	    $row->user_id = $user_id;
	    $row->subscription_type_id = getSubscriptionType('Weekly');
	    $row->is_new_user = 1;
	    $row->is_subscribed = 1;
	    $row->is_active = 1;
	    $row->valid_date = $valid;
	    $row->save();
	    return $row;
	}

	public function subscriber_updating($id, $user_id, $valid) {
		$row = SubscriberModel::find($id);
		$row->subscription_type_id = getSubscriptionType('Weekly');
		$row->is_new_user = 0;
		$row->is_subscribed = 1;
		$row->is_active = 1;
		$row->valid_date = $valid;
		$row->return_date = \Carbon\Carbon::now();
		$row->save();
		return $row;
	}

	public function subscriber_log_creation($user_id, $subscriber_id, $channel) {
	    $channel = (getSocialID()) ? 'Social' : 'WEB';
        if (Session::has('ott')) {
            $channel = 'Apps';
        }
        if(getKpValue()) {
            $channel = 'Armor';
        }
		$log=new SubscriberLogModel;
		$log->user_id=$user_id;
		$log->attempt_type=1;
		$log->channel_id=2;
		$log->attempt_type_status=1;
	    $log->subscription_id=$subscriber_id;
		$log->event='SUBSCRIBED';
		$log->save();
	}
}
















