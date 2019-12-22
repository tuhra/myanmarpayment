<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\WM\UserManager;
use App\WM\SubscriberManager;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\UserModel;
use Flash;
use Session;
use App\Helper\WavePaymentHelper;
use Wave;
use GuzzleHttp\Client;
use Alert;
use App\Helper\CallbackHelper;

class MsisdnController extends Controller
{
    public function index()
    {
    	return view('pages.msisdn');
    }

    public function verify(Request $request)
    {
    	$code=$request->get('code');
        $sub_type_id = Session::get('sub_type_id');
        $amount = Session::get('amount');

        // Session::forget('sub_type_id');
        // Session::forget('amount');

    	if ($code) {

            $url='https://graph.accountkit.com/v1.0/access_token?grant_type=authorization_code&code='.  $code .'&access_token=AA|'.config('customauth.FB_ACCOUNT_KIT_APP_ID').'|'.config('customauth.FB_ACCOUNT_KIT_APP_SECRET');
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $auth = curl_exec($curl);
            curl_close($curl);

    		$access = json_decode( $auth, true );

    		if( empty( $access ) || !isset( $access['access_token'] ) ){
    		   return array( "status" => 1, "message" => "Unable to verify the phone number." );
    		}

            $appsecret_proof= hash_hmac('sha256', $access['access_token'], config('customauth.FB_ACCOUNT_KIT_APP_SECRET'));
            return $redirect=$this->send_request($appsecret_proof,$access['access_token'], $sub_type_id, $amount);

    	} else {
    		return redirect('/failed');
    	}
    }

    public function send_request($appsecret_proof,$access_token, $sub_type_id, $amount)
    {
    	$ch = curl_init();
		// Set query data here with the URL
		curl_setopt($ch, CURLOPT_URL, 'https://graph.accountkit.com/v1.0/me/?access_token='. $access_token.'&appsecret_proof='. $appsecret_proof); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_TIMEOUT, '4');
		$resp = trim(curl_exec($ch));
        curl_close($ch);
        $info = json_decode( $resp, true );

		if( empty( $info ) || !isset( $info['phone'] ) || isset( $info['error'] ) ){
		    return array( "status" => 2, "message" => "Unable to verify the phone number." );
		}

        $operator = getopr($info['phone']['number']);
        if ($operator == 'MNTC') {
            return redirect('/message');
        }

		$msisdn=ltrim($info['phone']['number'],'+');
		setMsisdn($msisdn);
  //       $usermanager = new UserManager;
		// $user=$usermanager->user($msisdn);
        $row = UserModel::where('plain_msisdn', $msisdn)
                        ->first();
        if (!$row) {
            $row=new UserModel;
            $row->operator_id=3;
            $row->plain_msisdn=$msisdn;
            $row->encrypted_msisdn=$this->getUUID();
            $row->save();
        }

        Session::put('user_id', $row->id);
        $valid_msisdn = $info['phone']['national_number'];

        $paymentRequestId = getUUID();
        $payment_msisdn = $info['phone']['national_number'];

        $url = 'https://api.wavemoney.io:8100/wmt-mfs-merchant-exp/pay-with-wave';
        $data = '{"purchaserMsisdn": "'.$payment_msisdn.'", "purchaserAmount": "'.$amount.'", "timeOut": "40"}';
        $header = array(
            'Cache-Control:no-cache',
            'callbackUrl: https://telenor.gogamesapp.com/wave/callback',
            'paymentRequestId:' . $paymentRequestId,
            'client_secret: f460fcBd98414D66ae89310E59166bED',
            'client_id:c32a4bcb60fd4de699e285d45ab22cc0',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info($result);
        $arr = json_decode($result, true);
        if ($arr['statusCode'] == "102") {
            Session::put('paymentRequestId', $paymentRequestId);
            return redirect('/loading');
        } else {
            return redirect('/failed');
        }

        // $payment = Wave::wave_payment($payment_msisdn, $amount, "MMK", $paymentRequestId);
        // $new_msisdn = '0' . $info['phone']['national_number'];
        // New API integration
        // $payment = json_decode(Wave::wave_payment($new_msisdn, $amount, "MMK"), true);
        // if ($payment['success']) {
        //     $callbackhelper = new CallbackHelper;
        //     $is_exist=SubscriberModel::userId($user->id)->first();
        //     if (!$is_exist) {
        //         Session::put('sub_type_id', $sub_type_id);
        //         $sub_id=subcriptionID();
        //         $subscribermanager = new SubscriberManager;
        //         $subscribermanager->subscribe($user->id,$sub_id, $sub_type_id);
        //         $this->subscriber_log_creation($user->id, $sub_id);
        //         return redirect('/success');
        //     } else {
        //         Session::put('sub_type_id', $sub_type_id);
        //         $subscribermanager = new SubscriberManager;
        //         $sub_id = $is_exist->subscription_id;
        //         $subscribermanager->updatesubscriber($user->id,$sub_id, $sub_type_id);
        //         $this->subscriber_log_creation($user->id, $sub_id);
        //         return redirect('/success');
        //     }
        // } else {
        //     Session::put('message',$payment['object']['error']['message']);
        //     return redirect('/subscription_failed');
        // }

    }
}









