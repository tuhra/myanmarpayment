<?php

namespace Thura\Wave;

/**
* 
*/
class Wave
{
	public function wave_payment($msisdn, $amount, $currency, $paymentRequestId)
	{
		// $public_key = config('wave.WAVE_PUBLIC_KEY');
		// $private_key = config('wave.WAVE_PRIVATE_KEY');

		// $result = shell_exec('curl https://vault.paysbuy.com/ -X POST -u '.$public_key.': -d "wallet_id='.$msisdn.'" -d "wallet_brand=WaveMoney"');

		// $resultarr = json_decode($result, true);
		// $token = $resultarr['object']['token']['id'];

		// $url = 'https://payapi.paysbuy.com/payment/';
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL,$url);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS,
		//             "token=$token&amount=$amount&currency=$currency&invoice=go games&description=test charge");
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// curl_setopt($ch, CURLOPT_USERPWD, "$private_key:");
		// $result = curl_exec($ch);
		// curl_close($ch); 
		// return $result;

	}	
}



