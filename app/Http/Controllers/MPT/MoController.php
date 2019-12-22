<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Helper\MptHelper;
use App\Helper\SubHelper;
use App\Helper\MptUnsubHelper;

class MoController extends Controller
{
    public function index(Request $request) {
    	$xmlfile = $request->getContent();
		$xml = loadxml($xmlfile);
		$arrayData = xmlToArray($xml);
		$spId = $arrayData['Envelope']['SOAP-ENV:Header']['comm3:NotifySOAPHeader']['spId'];
		$sender_address = $arrayData['Envelope']['SOAP-ENV:Header']['comm3:NotifySOAPHeader']['SAN'];

		$messageArray = $arrayData['Envelope']['SOAP-ENV:Body']['sms7:notifySmsReception']['sms7:message'];
		$msisdn = $messageArray['senderAddress'];
		$message = strtoupper($messageArray['message']);
		$helper = new SubHelper;
	    $mpt = new MptHelper;

		switch ($message) {
			case 'ON':
				$user = getMoUser($msisdn);
				$is_exist=SubscriberModel::where('user_id', $user->id)->first();
				if (empty($is_exist)) {
					$start = new \Carbon\Carbon;
					$date = $start->now()->addDays(7);
					$valid = $date->toDateTimeString();
			        $renewal_date = $date->toDateString();
		        	$msg = "ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ ဝန္ေဆာင္မႈအား ယေန႔မွစ၍ ၇ ရက္တိတိအခမဲ့ အသုံးျပဳႏိုင္ၿပီး " .$renewal_date . ". ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၉၉ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
		        	$subscriber = $helper->subscriber_creation($user->id, $valid);
		        	$helper->subscriber_log_creation($user->id, $subscriber->user_id);
		        	$sms_result = $mpt->sendsms($msg, $msisdn);
		        	$response = getMoResponse(200, 'successfully subscribed!');
		        	$this->log_creation($user->id, $sms_result, $xmlfile, $response);
		        	return $response;

				} else {
					$start = new \Carbon\Carbon;
					$date = $start->now()->addDays(1);
					$valid = $date->toDateTimeString();
			        $renewal_date = $date->toDateString();
			        $amount = config('mpt.trialprice');
			        $msg = "ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ " .$renewal_date . ". ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၉၉ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
			        $subscriber = $helper->subscriber_updating($is_exist->id, $user->id, $valid);
			        $sms_result = $mpt->sendsms($msg, $msisdn);
			        $response = getMoResponse(200, 'successfully subscribed!');
			        $this->log_creation($user->id, $sms_result, $xmlfile, $response);
			        return $response;
				}
				break;

			case 'GG OFF':
				$mptunsub = new MptUnsubHelper;
				$user = getMoUser($msisdn);
				$is_exist=SubscriberModel::where('user_id', $user->id)->first();

				if ($is_exist) {
					if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
						$msg = "GoGames ဝန္ေဆာင္မႈအား သင္၏ ဖုန္းမွ အသုံးျပဳမႈရပ္ဆိုင္းျခင္းေအာင္ျမင္ပါသည္။ ဝန္ေဆာင္မႈအားျပန္လည္ ရယူလိုပါက http://mm.gogamesapp.com/en/app အားႏွိပ္ၿပီး ျပန္လည္အသံုးျပဳႏိုင္ပါသည္။  ဝန္ေဆာင္ခမွာ ၉၉  က်ပ္ တိတိျဖစ္ပါသည္။အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
						$response = $mptunsub->moUnsub($msisdn);
						$sms_result = $mpt->sendsms($msg, $msisdn);
						$this->log_creation($user->id, $sms_result, $xmlfile, $response);
						return $response;
					}
				} 

				break;
			case 'OFF':
				$mptunsub = new MptUnsubHelper;
				$user = getMoUser($msisdn);
				$is_exist=SubscriberModel::where('user_id', $user->id)->first();

				if ($is_exist) {
					if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
						$msg = "GoGames ဝန္ေဆာင္မႈအား သင္၏ ဖုန္းမွ အသုံးျပဳမႈရပ္ဆိုင္းျခင္းေအာင္ျမင္ပါသည္။ ဝန္ေဆာင္မႈအားျပန္လည္ ရယူလိုပါက http://mm.gogamesapp.com/en/app အားႏွိပ္ၿပီး ျပန္လည္အသံုးျပဳႏိုင္ပါသည္။  ဝန္ေဆာင္ခမွာ ၉၉  က်ပ္ တိတိျဖစ္ပါသည္။အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
						$response = $mptunsub->moUnsub($msisdn);
						$sms_result = $mpt->sendsms($msg, $msisdn);
						$this->log_creation($user->id, $sms_result, $xmlfile, $response);
						return $response;
					}
				} 

				break;

			case 'GG HELP':
				$msg = "GoGames ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။ ၇ ရက္တိတိအခမဲ့ အသုံးျပဳႏိုင္ၿပီး ေနာက္ တရက္လ်ွင္ ၀န္ေဆာင္ခ ေန့စဥ္ ၉၉ က်ပ္ တိတိ ေကာက္ခံ သြားမည္ျဖစ္ပါသည္။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
				$sms_result = $mpt->sendsms($msg, $msisdn);
				$response = getMoResponse(200, 'successfully help to user');
				// $this->log_creation($user->id, $sms_result, $xmlfile, $response);
				return $response;
				break;
			default:
				$msg = "႐ိုက္သြင္းထားေသာ သေကၤတမွာ မွား ယြင္းေနပါသည္။ GoGames ဝန္ေဆာင္မႈ အားရယူ လိုပါက ေက်းဇူးျပဳ ၍ http://mm.gogamesapp.com/en/app အားနွိပ္ ၍ အသံုးျပဳလိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို့ဆက္သြယ္ေမးျမန္းနိုင္ပါသည္။";
				$sms_result = $mpt->sendsms($msg, $msisdn);
				$response = getMoResponse(200, 'Wrong key word');
				// $this->log_creation($user->id, $sms_result, $xmlfile, $response);
				return $response;
				break;
		}


    }

    private function log_creation($user_id, $sms_result, $xmlfile, $response) {
    	$mpt = new MptHelper;
    	$mpt->mpt_sms_log_creation($user_id, $sms_result);
    	$mpt->mo_log_createion($user_id, $xmlfile, $response);
    }


}






