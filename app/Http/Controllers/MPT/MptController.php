<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\FbkitHelper;
use App\Helper\SubHelper;
use Session;
use App\Models\UserModel;
use App\Models\OtpModel;
use App\Models\SubscriberModel;
use App\Helper\MptHelper;
use App\Helper\SmsHelper;

class MptController extends Controller
{
    public function index(Request $request) {
    	return view('mpt.create');
    }

    public function sendotp(Request $request) {
        $msisdn = $request->msisdn;
        if ($msisdn[0] == 0) {
            $msisdn = ltrim($msisdn, $msisdn[0]);
            $msisdn = "+95" . $msisdn;
            $operator = getopr($msisdn);
        } elseif ($msisdn[0] == 9) {
            $msisdn = "+95" . $msisdn;
            $operator = getopr($msisdn);
        } else {
            return "Wrong Msisdn";
        }

        $country_id = country($msisdn);
        $operator_id = operator($msisdn, $country_id);
        setOptId($operator_id);

        switch ($operator) {
            case 'Telenor':
                # code...
                break;
            case 'Ooredoo':
                # code...
                break;
            default:
                // To generate OTP
                $otpArr = generateotp();
                return $this->mptotp($otpArr, $msisdn);
                break;
        }

    }

    public function otp() {
        return view('mpt.otp');
    }

    public function resent() {
        $msisdn = getMsisdn();
        $otpArr = generateotp();
        $this->mptotp($otpArr, $msisdn);
        return ['status_code' => 200];
    }

    public function postOTP(Request $request) {
        $otp = $request->pin;
        $enc = getotpencrypted();
        $row = OtpModel::where('otp', $otp)
                        ->where('encrypted', $enc)->first();
        if (empty($row)) {
            $message = trans('app.wrong_opt_message');
            return redirect('mpt/otp')->withErrors([$message]);
        }

        return $this->charge($request);
    }

    private function mptotp($otpArr, $msisdn) {
        $mpt = new MptHelper;
        $otp = $otpArr[0];
        $enc = $otpArr[1];
        setotpencrypted($enc);
        setotp($otp);
        $msg = $otp .' အား GoGames ဝန္ေဆာင္မႈအတြက္ မိနစ္ (၃၀) အတြင္း ႐ိုက္ထည့္ႏိုင္ပါသည္။';
        // $msg = 'Please enter' .$otp. ' into GoGames  within 30 mins.';
        $msisdn = ltrim($msisdn, '+');
        setMsisdn($msisdn);
        $chargeRes = $mpt->charge_amount($msisdn, 0);
        if ($chargeRes['status_code'] == 201) {
            $result = $mpt->charged_sms($msg, $msisdn);
            $mpt->mpt_sms_log_creation(1, $result);
            $this->opt_creation($otp, $enc);
            return redirect('mpt/otp');
        }
    }

    public function charge(Request $request) {
    	$msisdn = getMsisdn();
    	// $user = getUser($msisdn);
        $user = UserModel::where('plain_msisdn', $msisdn)->first();
        if (empty($user)) {
            $user = new UserModel;
            $user->operator_id = 2;
            $user->plain_msisdn = $msisdn;
            $user->encrypted_msisdn = getUUID();
            $user->save();
        }
    	
    	$is_exist=SubscriberModel::where('user_id', $user->id)->first();
    	$helper = new SubHelper;
        $mpt = new MptHelper;
    	if (empty($is_exist)) {
    		$start = new \Carbon\Carbon;
    		$date = $start->now()->addDays(7);
    		$valid = $date->toDateTimeString();
            $renewal_date = $date->toDateString();
            $amount = config('mpt.trialprice');
            $chargeRes = $mpt->charge_amount($msisdn, $amount);
            $mpt->mpt_payment_log_creation($user->id, $chargeRes);
            if ($chargeRes['status_code'] == 201) {
                $subscriber = $helper->subscriber_creation($user->id, $valid);
                $helper->subscriber_log_creation($user->id, $subscriber->user_id, "Apps");
                // $renewal_date = $subscriber->valid_date;
                $msg = "GoGames ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ ဝန္ေဆာင္မႈအား ယေန႔မွစ၍ ၇ ရက္တိတိအခမဲ့ အသုံးျပဳႏိုင္ၿပီး " .$renewal_date . " ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၉၉ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ GG OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
                $result = $mpt->charged_sms($msg, $msisdn);
                $mpt->mpt_sms_log_creation($user->id, $result);
                return redirect('/mpt/success');
            }

    	} else {

            if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
                return redirect('/mpt/mpt_exist');
            }

            $start = new \Carbon\Carbon;
            $date = $start->now()->addDays(1);
            $valid = $date->toDateTimeString();
            $renewal_date = $date->toDateString();

            $amount = config('mpt.trialprice');
            $chargeRes = $mpt->charge_amount($msisdn, $amount);
            $mpt->mpt_payment_log_creation($user->id, $chargeRes);
            if ($chargeRes['status_code'] == 201) {
                $msg = "GoGames ဝန္ေဆာင္မႈအားရယူသည့္အတြက္ ေက်းဇူးတင္ပါသည္။ " .$renewal_date . " ေန႔မွစတင္၍ တစ္ရက္လွ်င္ ၉၉ က်ပ္ တိတိ ေကာက္ခံသြားမည္ျဖစ္ပါသည္။ ဝန္ေဆာင္မႈအား http://mm.gogamesapp.com/en/app မွ တစ္ဆင့္ download ရယူလိုက္ပါ။  ဝန္ေဆာင္မႈအားရပ္တန႔္လိုပါက 8433 သို႔ GG OFF ဟု ေပးပို႔လိုက္ပါ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
                $subscriber = $helper->subscriber_updating($is_exist->id, $user->id, $valid);
                $sms_result = $mpt->charged_sms($msg, $msisdn);
                $mpt->mpt_sms_log_creation($user->id, $sms_result);
                return redirect('/mpt/success');
            }
    	}
    }	


    public function check() {
        $response = [];
        $response['ott'] = Session::get('ott');
        return $response;
    }

    public function success() {
    	return view('mpt.success');
    }

    public function mosuccess() {
        return view('mpt.mosuccess');
    }

    public function failed() {
    	return view('mpt.failed');	
    }

    public function mpt_exist() {
        return view('messages.mpt_exist');
    }

    private function getURL($cancelURL) {
        $telenorhelper = new SmsHelper;
        $shrinkresult = $telenorhelper->shirnkUrl($cancelURL);
        if ($shrinkresult['status_code'] == 200) {
            $result = json_decode($shrinkresult['result'], true);
            $cancelURL = $result['shorturl'];
            return $cancelURL;
        }
    }

    private function opt_creation($otp, $encrypted) {
        $row = new OtpModel;
        $row->otp = $otp;
        $row->encrypted = $encrypted;
        $row->save();
    }

}















