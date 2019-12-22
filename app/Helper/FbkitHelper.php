<?php

namespace App\Helper;

use App;
use Session;

class FbkitHelper 
{
	public function verify($code) {
		if ($code) {

	        $url='https://graph.accountkit.com/v1.0/access_token?grant_type=authorization_code&code='.  $code .'&access_token=AA|1692195921084530|69589811cf85062efd339a68247188b5';
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
	        return $redirect=$this->send_request($appsecret_proof,$access['access_token']);

		} else {
			return redirect('/failed');
		}
	}

	public function send_request($appsecret_proof,$access_token) {
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

		return $info;

	}
}
















