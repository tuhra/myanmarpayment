<?php

case 'GG ON':
	$user = getMoUser($msisdn);
	$is_exist=SubscriberModel::where('user_id', $user->id)->first();
	if (empty($is_exist)) {
		$renewal = getRenewalDate();
		$renewal_date = $renewal[0];
		$valid = $renewal[1];
		$msg = "GoGames ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ ဝန္ေဆာင္မႈအား ယေန႔မွစ၍ ၇ ရက္တိတိအခမဲ့ အသုံးျပဳႏိုင္ၿပီး " .$renewal_date . ". ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၂၀၀ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ GG OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
		$amount = config('mpt.trialprice');
		$chargeRes = $mpt->charge_amount($msisdn, $amount);
		$mpt->mpt_payment_log_creation($user->id, $chargeRes);

		if ($chargeRes['status_code'] == 201) {
			$subscriber = $helper->subscriber_creation($user->id, $valid);
			$helper->subscriber_log_creation($user->id, $subscriber->user_id, "SMS");
			$sms_result = $mpt->charged_sms($msg, $msisdn);
			$response = getMoResponse(200, 'successfully subscribed!');
			$this->log_creation($user->id, $sms_result, $xmlfile, $response);
			return $response;
		}

	} else {

		$start = new \Carbon\Carbon;
		$date = $start->now();
		if($is_exist->is_subscribed == 1) {
			$msg = "GoGames ၀န္ေဆာင္မႈအား ရယူထားျပီးျဖစ္ပါသည္။ေက်းဇူးျပဳ၍ http://mm.gogamesapp.com/en/app အားႏွိပ္ျပီး အသံုးျပဳလိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႕ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
			$sms_result = $mpt->sendsms($msg, $msisdn);
			$response = getMoResponse(200, 'already subscribed!');
			$this->log_creation($user->id, $sms_result, $xmlfile, $response);
			return $response;
		} 

		$renewal = getRenewalDate();
		$renewal_date = $renewal[0];
		$valid = $renewal[1];
        $msg = "GoGames ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ " .$renewal_date . ". ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၂၀၀ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ GG OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";

        $amount = config('mpt.dailyprice');
        $chargeRes = $mpt->charge_amount($msisdn, $amount);
        $mpt->mpt_payment_log_creation($user->id, $chargeRes);

        if ($chargeRes['status_code'] == 201) {
            $subscriber = $helper->subscriber_updating($is_exist->id, $user->id, $valid);
            $helper->subscriber_log_creation($user->id, $subscriber->user_id);
            $sms_result = $mpt->charged_sms($msg, $msisdn);
            $response = getMoResponse(200, 'successfully subscribed!');
            $this->log_creation($user->id, $sms_result, $xmlfile, $response);
            return $response;
        } else {
            $arr = json_decode($chargeRes['res'], true);
           	if ($arr['errorCode'] == 'SVC0270') {
           		$sms = "လက္က်န္ေငြမလံုေလာက္ပါသျဖင့္ GoGames ၀န္ေဆာင္မႈအားရယူ၍မရႏိုင္ပါ။ ၀န္ေဆာင္ခမွာ ၂၀၀ က်ပ္ တိတိျဖစ္ပါသည္။ေက်းဇူးျပဳ၍ ေငြျဖည့္သြင္းျခင္း (သို႔) *800# ကိုရိုက္နွိပ္၍ ယူထား၀န္ေဆာင္မႈအားရယူလိုက္ပါ။အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႕ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
           	}
            // $response = $arr['message'];
            $sms_result = $mpt->charged_sms($sms, $msisdn);
            $this->log_creation($user->id, $sms_result, $xmlfile, $response);
            return $response;
        }
	}

	break;
?>