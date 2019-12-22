<?php

namespace App\Helper;

use App;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\UserModel;
use App\Helper\MptHelper;

class MptUnsubHelper
{
	public function moUnsub($msisdn) {
		$user = UserModel::with('subscriber')->where('plain_msisdn', $msisdn)->first();
		if (empty($user)) {
			return getMoResponse(404, 'User not found');
		}
		if ($user->subscriber->is_active == 0) {
			return getMoResponse(200, 'Already unsubscribed!');	
		}
		if ($user->subscriber->is_active == 1) {
			$row = SubscriberModel::find($user->subscriber->id);
			$row->is_subscribed = 0;
			$row->is_active = 0;
			$row->save();

			$this->subscriber_log($user->id, $user->subscriber->user_id);
			return getMoResponse(200, 'Successfully subscribed!');	
		}
	}

	private function subscriber_log($userId, $subId) {
		$row = new SubscriberLogModel;
		$row->user_id = $userId;
		$row->attempt_type = 0;
		$row->channel_id = channel_id('SMS');
		$row->attempt_type_status = 1;
		$row->subscription_id = $subId;
		$row->event = 'UNSUBSCRIBE';
		$row->save();
	}

}
















