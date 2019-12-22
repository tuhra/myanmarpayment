<?php

namespace App\Helper;

use App;

class WavePaymentHelper 
{
	public function wavepayment($msisdn, $amount, $currency)
	{
		$public_key = config('wave.WAVE_PUBLIC_KEY');
		$private_key = config('wave.WAVE_PRIVATE_KEY');

		$result = shell_exec('curl https://vault.paysbuy.com/ -X POST -u '.$public_key.': -d "wallet_id='.$msisdn.'" -d "wallet_brand=WaveMoney"');
		$tokenarr = json_decode($result, true);
		$token = $tokenarr['object']['token']['id'];
		// $token = $tokenarr['object']['token']['id'];

		$url = 'https://payapi.paysbuy.com/payment/';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            "token=$token&amount=$amount&currency=$currency&invoice=go games&description=test charge");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$private_key:");
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result;
	}


	public function wavenotifyRequest($url, $wavemsisdn, $amount, $paymentRequestId, $client_secret, $client_id, $callback) {
		
		/*
		// Production API Request
		$url = 'https://api.wavemoney.io:8100/wmt-mfs-merchant-exp/pay-with-wave';
		$data = '{"purchaserMsisdn": "'.$wavemsisdn.'", "purchaserAmount": "'.$amount.'", "timeOut": "40"}';
		$header = array(
		    'Cache-Control:no-cache',
		    'callbackUrl: https://telenor.gogamesapp.com/wave/callback',
		    'paymentRequestId:' . $paymentRequestId,
		    'client_secret: f460fcBd98414D66ae89310E59166bED',
		    'client_id:c32a4bcb60fd4de699e285d45ab22cc0',
		    'Content-Type: application/json'
		);
		*/

		$data = '{"purchaserMsisdn": "'.$wavemsisdn.'", "purchaserAmount": "'.$amount.'", "timeOut": "40"}';
		$header = array(
		    'Cache-Control:no-cache',
		    'callbackUrl: ' . $callback,
		    'paymentRequestId:' . $paymentRequestId,
		    'client_secret:' . $client_secret,
		    'client_id:' . $client_id,
		    'Content-Type: application/json'
		);

		\Log::info($header);
		\Log::info($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$result = curl_exec($ch);
		\Log::info($result);
		if (curl_error($ch)) {
		    $error_msg = curl_error($ch);
		    \Log::info('curl-error:' . $error_msg);
		}
		curl_close($ch);
		return $result;
	}
}




