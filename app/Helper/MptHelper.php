<?php

namespace App\Helper;

use App;
use Session;
use App\Helper\SmsHelper;
use App\Models\MptSmsLogModel;
use App\Models\MptPaymentLogModel;
use App\Models\MoSmsLogModel;

class MptHelper 
{
	public function sendsms($message, $msisdn) {

		$soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
										xmlns:v3="http://www.csapi.org/schema/parlayx/common/v3_1" 
										xmlns:loc="http://www.csapi.org/schema/parlayx/sms/send/v3_1/local">
										<soapenv:Header> 
											<v3:RequestSOAPHeader>
												<spId>'.config('mpt.spId').'</spId>
												<spPassword>'.config('mpt.spPassword').'</spPassword> 
												<timeStamp>'.getTimestamp().'</timeStamp> 
												<serviceId>'.config('mpt.sms_service_id').'</serviceId>
												<FA>tel: +86123</FA>
											</v3:RequestSOAPHeader> 
										</soapenv:Header> 
										<soapenv:Body>
											<loc:sendSms> 
												<loc:addresses>tel:'.$msisdn.'</loc:addresses>
												<loc:message>'.$message.'</loc:message> 
											</loc:sendSms>
										</soapenv:Body> 
									</soapenv:Envelope>';

		$url = "http://45.112.178.185:62/SendSmsService";


		$header = array(
		    "Content-type: text/xml;charset=\"utf-8\"",
		    "Accept: text/xml",
		    "Cache-Control: no-cache",
		    "Pragma: no-cache",
		    "SOAPAction: \"run\"",
		    "Content-length: ".strlen($soap_request),
		  );

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,            $url );   
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_TIMEOUT,        10); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($ch, CURLOPT_POST,           true ); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap_request); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return ['status_code' => $httpcode, 'res' => $result, 'req' => $soap_request];


	}

	public function charge_amount($msisdn, $amount) {
		$endUserId = "tel:" . $msisdn;
		// $endUserId = "tel:959253141636";
		$url = 'http://45.112.178.185:61/1/payment/'.$endUserId.'/transactions/amount';
		$telenorhelper = new SmsHelper;
		$refCode = $telenorhelper->randomstring(8, 8) . "-" . $telenorhelper->randomstring(4, 4) . "-" . $telenorhelper->randomstring(4, 4);
		$refCode = $refCode . "-" . $telenorhelper->randomstring(4, 4) . "-" . $telenorhelper->randomstring(12, 12). time();
		
		$code = $telenorhelper->randomstring(4, 4);
		$clientCorrelator = $telenorhelper->randomstring(8, 8).time();

		$header = array(
		    'Accept: application/json',
		    'Content-Type: application/x-www-form-urlencoded',
		    'Authorization: AUTH spId="'.config('mpt.spId').'",spPassword="'.config('mpt.spPassword').'",timeS tamp="'.getTimestamp().'",serviceId="'.config('mpt.pay_service_id').'"',
		);

		$data = '{
			"endUserId":"'.$endUserId.'", 
			"transactionOperationStatus":"Charged", 
			"chargingInformation":{
				"description":"Go|Games", 
				"code": "'.$code.'",
				"amount": "'.$amount.'",
				"currency": "'.config('mpt.currency').'"
			}, 
			"referenceCode":"'.$refCode.'", 
			"clientCorrelator": "'.$clientCorrelator.'"
		}';


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return ['status_code' => $httpcode, 'res' => $result, 'req' => $data];

	}

	public function charged_sms($message, $msisdn) {
		$soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
										xmlns:v3="http://www.csapi.org/schema/parlayx/common/v3_1" 
										xmlns:loc="http://www.csapi.org/schema/parlayx/sms/send/v3_1/local">
										<soapenv:Header> 
											<v3:RequestSOAPHeader>
												<spId>'.config('mpt.spId').'</spId>
												<spPassword>'.config('mpt.spPassword').'</spPassword> 
												<timeStamp>'.getTimestamp().'</timeStamp> 
												<serviceId>'.config('mpt.pay_service_id').'</serviceId>
												<FA>tel: +86123</FA>
											</v3:RequestSOAPHeader> 
										</soapenv:Header> 
										<soapenv:Body>
											<loc:sendSms> 
												<loc:addresses>tel:'.$msisdn.'</loc:addresses>
												<loc:message>'.$message.'</loc:message> 
											</loc:sendSms>
										</soapenv:Body> 
									</soapenv:Envelope>';

		$url = "http://45.112.178.185:62/SendSmsService";


		$header = array(
		    "Content-type: text/xml;charset=\"utf-8\"",
		    "Accept: text/xml",
		    "Cache-Control: no-cache",
		    "Pragma: no-cache",
		    "SOAPAction: \"run\"",
		    "Content-length: ".strlen($soap_request),
		  );

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,            $url );   
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_TIMEOUT,        10); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($ch, CURLOPT_POST,           true ); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,    $soap_request); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return ['status_code' => $httpcode, 'res' => $result, 'req' => $soap_request];
	}


	public function mpt_sms_log_creation($user_id, $result) {
		$sms = new MptSmsLogModel;
		$sms->user_id = $user_id;
		$sms->reqBody = $result['req'];
		$sms->resBody = $result['res'];
		$sms->response_status_code = $result['status_code'];
		$sms->save();
	}

	public function mpt_payment_log_creation($user_id, $result) {
		$arr = json_decode($result['req'],true);

		$log = new MptPaymentLogModel;
		$log->user_id = $user_id;
		$log->referenceCode = $arr['referenceCode'];
		$log->clientCorrelator = $arr['clientCorrelator'];
		$log->reqBody = $result['req'];
		$log->resBody = $result['res'];
		$log->response_status_code = $result['status_code'];
		$log->save();
	}

	public function mo_log_createion($user_id, $req, $res) {
		$mo = new MoSmsLogModel;
		$mo->user_id = $user_id;
		$mo->mptReqBody = $req;
		$mo->ggResBody = $res;
		$mo->save();
	}


	// MPT MA Helper
	public function maoptsms($msisdn) {
		$url = 'http://macnt.mpt.com.mm/API/CGRequest';
		// $params = 'MSISDN='.$msisdn.'&productID='.config('ma.productID').'&pName=GoGames&pPrice='.config('ma.pPrice').'&pVal=1&CpId='.config('ma.CpId').'&CpPwd='.config('ma.CpPwd').'&CpName='.config('ma.CpName').'&reqMode=PIN&reqType='.config('ma.reqType').'&ismID=17&transID='.getUUID().'&sRenewalPrice='.config('ma.sRenewalPrice').'&sRenewalValidity='.config('ma.sRenewalValidity').'&serviceType='.config('ma.serviceType').'&planId='.config('ma.planId').'&request_locale=my';
		$params = 'MSISDN='.$msisdn.'&productID=9320&pName=GoGames&pPrice=99&pVal=1&CpId=MIAKI&CpPwd=miaki@123&CpName=MIAKI&reqMode=PIN&reqType=SUBSCRIPTION&ismID=17&transID='.getUUID().'&sRenewalPrice=99&sRenewalValidity=1&serviceType=T_MIA_GOG_SUB_D&planId=T_MIA_GOG_SUB_D_99&request_locale=my';

		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $result = curl_exec($ch);
	    \Log::info($result);
	    curl_close($ch);
	    return ['req' => $url .'?'.$params , 'res' => $result];
	}

	public function otpRegeneration($msisdn) {

		$url = 'http://macnt.mpt.com.mm/API/OTPRegenerateActionApp';
		$params = 'msisdn='.$msisdn.'&request_locale=my&reqMode=PIN&transId='. Session::get('mpt_tranid') .'&productName=GoGames&pPrice=99&pVal=1&sRenewalPrice=99&sRenewalValid=1&opId=101&CpId=MIAKI&productID=9320';

		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $result = curl_exec($ch);
	    curl_close($ch);
	  	return ['req' => $url .'?'.$params , 'res' => $result];
	}

	public function sendotp($otp, $msisdn) {

		$url = 'http://macnt.mpt.com.mm/API/OTPValidateActionApp';
		$params = 'msisdn='.$msisdn.'&request_locale=my&reqMode=PIN&transId='. Session::get('mpt_tranid') .'&productName=GoGames&pPrice=99&pVal=1&Otp='.$otp.'&opId=101&CpId=MIAKI&productID=9320';
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $result = curl_exec($ch);
	    curl_close($ch);
	  	return ['req' => $url .'?'.$params , 'res' => $result];
	}

	public function maUnsubscribe($msisdn, $tranid) {
		$url = 'http://macnt.mpt.com.mm/API/CGUnsubscribe';
		$params = 'MSISDN='.$msisdn.'&productID=9320&pName=GoGames&pPrice=99&pVal=1&CpId=MIAKI&CpPwd=miaki@123&CpName=MIAKI&reqMode=PIN&reqType=SUBSCRIPTION&ismID=17&transID='.$tranid.'&sRenewalPrice=99&sRenewalValidity=1&serviceType=T_MIA_GOG_SUB_D&planId=T_MIA_GOG_SUB_D_99&request_locale=my';
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    $result = curl_exec($ch);
	    curl_close($ch);
	  	return ['req' => $url .'?'.$params , 'res' => $result];	
	}


}
















