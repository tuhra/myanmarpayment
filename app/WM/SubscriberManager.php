<?php
namespace App\WM;
use App\Models\SubscriberModel;
use App\Models\SubscriptionTypeModel;
use App\Models\SubscriberLogModel;
use \Carbon\Carbon;
use Session;

/**
* 
*/
class SubscriberManager
{
	public function subscribe($user_id,$sub_id=null, $sub_type_id)
	{
		Session::put('sub_type_id', $sub_type_id);
		if ($sub_type_id == 1) {
			$valid_date = Carbon::now()->AddDays(7);
		} else if ($sub_type_id == 2) {
			$valid_date = Carbon::now()->AddDays(28);
		} else {
			$valid_date = Carbon::now()->AddDays(70);
		}
		$this->insertsubscriber($user_id, $sub_id, $sub_type_id, $valid_date);
	}

	public function updatesubscriber($user_id, $sub_id, $sub_type_id)
	{
		$row=SubscriberModel::UserId($user_id)->first();
		if ($row->is_subscribed == 0 && $row->is_active == 0) {
			// return "inactive";
			if ($sub_type_id == 1) {
				$valid_date = Carbon::now()->AddDays(7);
			} else if ($sub_type_id == 2) {
				$valid_date = Carbon::now()->AddDays(28);
			} else {
				$valid_date = Carbon::now()->AddDays(70);
			}
		} else {
			$current_date = date("Y-m-d h:i:s");
			if ($row->valid_date < $current_date) {
				// return "over valid date";
				if ($sub_type_id == 1) {
					$valid_date = Carbon::now()->AddDays(7);
				} else if ($sub_type_id == 2) {
					$valid_date = Carbon::now()->AddDays(28);
				} else {
					$valid_date = Carbon::now()->AddDays(70);
				}
			} else {
				// return "update user";
				if ($sub_type_id == 1) {
					$valid_date = $this->customDateAdd($row->valid_date, "+ 7 day");
				} else if ($sub_type_id == 2) {
					$valid_date = $this->customDateAdd($row->valid_date, "+ 28 day");
				} else {
					$valid_date = $this->customDateAdd($row->valid_date, "+ 70 day");
				}	
			}
		}
		$this->update($user_id, $sub_id, $sub_type_id, $valid_date);

	}

	private function getSubscriptionType($type)
	{
		return SubscriptionTypeModel::Name($type)->first()->id;
	}

	private function customDateAdd($existdate, $add_day)
	{
		$new_date = date('Y-m-d', strtotime($existdate . $add_day));
		$new_time = date('H:i:s');
		return $new_date . " " . $new_time;
	}

	public function insertsubscriber($user_id, $sub_id, $sub_type_id, $valid_date)
	{
		$row=SubscriberModel::UserId($user_id)->first();
		$row=new SubscriberModel;
		$row->user_id=$user_id;
		$row->is_new_user=1;
		$row->subscription_id=$sub_id;
		$row->subscription_type_id=$sub_type_id;
		$row->is_subscribed=1;
		$row->is_active=1;
		$row->valid_date=$valid_date;
		$row->save();
	}

	public function update($user_id, $sub_id, $sub_type_id, $valid_date)
	{
		SubscriberModel::where('user_id', $user_id)->update([
			'user_id' => $user_id,
			'is_new_user' => 0,
			'subscription_id' => $sub_id,
			'subscription_type_id' => $sub_type_id,
			'is_subscribed' => 1,
			'is_active' => 1,
			'valid_date' => $valid_date,
		]);
	}

}





