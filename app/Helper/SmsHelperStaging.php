<?php

namespace App\Helper;
use App\Models\TelenorHeLogModel;
use App;

class SmsHelperStaging {

    public function remindersms($user_id, $msisdn, $sub_id) {
        $new_msisdn = substr($msisdn, 3);
        $new_msisdn_arr = str_split($new_msisdn);
        $prefix = $new_msisdn[0];
        if ($prefix == 7) {
            $send_msisdn = "959" . $new_msisdn;
            \Log::info($send_msisdn);
        }
        // \Log::info($user_id ."-". $msisdn ."-". $new_msisdn ."-". $sub_id);
    }

    public function refund($serverRefCode, $endUserId) {

        $refCode = getUUID();
        $authKey = config('telenor.TelenorStagingToken');
        $url = "​https://stagingapi.comoyo.com/partner/payment/v1/". $endUserId ."/transactions/amount";
        $authorize = "Basic" . " " . $authKey;
        $data = '{
            "amountTransaction":{
                "endUserId":"'.$endUserId.'",
                "transactionOperationStatus":"Refunded",
                "referenceCode":"'.$refCode.'",
                "originalServerReferenceCode":"'. $serverRefCode .'",
                        "paymentAmount":{
                            "chargingInformation":{
                            "amount":499,
                            "description":"GoGames - Refund",
                            "currency":"MMK"
                        },
                        "chargingMetaData":{
                            "purchaseCategoryCode":"Game"
                            }
                        }
                    }
                }';
        return $this->postapirequest($url, $data);
    }

    public function getACR($refid) {
    	$authKey = config('telenor.TelenorStagingToken');
        $url = "http://stagingacr.telenordigital.com/partner/acr/echo?partnerId=miaki&operatorId=TLN-MM&redirect=true&referenceId=" .$refid;
  
    	$authorize = "Basic" . " " . $authKey;

    	$ch = curl_init($url);
    	curl_setopt(
    	    $ch, 
    	    CURLOPT_HTTPHEADER,
    	    array(
    	        'Authorization: ' . $authorize,
    	        'Content-Type: application/json'
    	    )
    	);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    	$result = curl_exec($ch);
    	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	
        curl_close($ch);
        
        $log = new TelenorHeLogModel;
        $log->reqBody = $url;
        $log->resBody = $result;
        $log->resCode = $httpcode;
        $log->save();

    	return $result;


    }


    public function otpsms($msisdn) {
        $url = 'https://stagingapi.comoyo.com/partner/pin';

        $data = '{"PinCreationRequest": {
		                "msisdn": "' . $msisdn . '"
		        }}';


        return $this->postapirequest($url, $data);
    }

    private function postapirequest($url, $data) {

        $header = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . config('telenor.TelenorStagingToken')
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['status_code' => $httpcode, 'res' => $result, 'req' => $data];
    }


    public function confirm_otp($msisdn, $pin) {
        $url = 'https://stagingapi.comoyo.com/partner/acrs';

        $data = '{  
		   "AcrCreationRequest":{  
		      "msisdn": "' . $msisdn . '",
		      "pin": "' . $pin . '"
		   }
		}';

        return $this->postapirequest($url, $data);
    }

    public function telenor_mt_sms($endUserId, $msg) {
        $url = 'https://stagingapi.comoyo.com/partner/smsmessaging/v2/outbound/tel:+959769867204/requests';
        $data = '
			{  
			   "outboundSMSMessageRequest":{  
			      "address":[  
			         "acr:' . $endUserId . '"
			      ],
			      "senderAddress":"tel:+959769867204",
			      "outboundSMSTextMessage":{  
			         "message":"' . $msg . '"
			      },
			      "clientCorrelator":"65675757895469875678",
			      "senderName":"GoGames"
			   }
			}
		';

        return $this->postapirequest($url, $data);
    }

    public function payment_with_acr ($endUserId, $amount, $refCode) {

		$url = 'https://stagingapi.comoyo.com/partner/payment/v1/'. $endUserId .'/transactions/amount';

		$data = '{
					"amountTransaction":{
					"clientcorrelator":"",
					"endUserId": "'.$endUserId.'",
					"transactionOperationStatus":"Charged",
					"referenceCode":"'.$refCode.'",
					"paymentAmount":{
					 	"chargingInformation":{
					    	"amount": '.$amount.',
					    	"description":"GoGames - 7 day subscription",
					    	"currency":"MMK"
					},
						   	"chargingMetaData":{
						    "onBehalfOf":"GoGames",
						    "purchaseCategoryCode":"Game",
						    "channel":"WAP",
							"mandateId":{
							    "renew":"true"
					    		}
					   		}
					  	}
					}
				}';

		return $this->postapirequest($url, $data);

	}

	public function randomstring($x, $y) {
		$length = rand($x,$y);
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		    $randomString = '';

		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }

		return $randomString;
	}

    public function shirnkUrl($req_url) {

        $url = "https://is.gd/create.php?format=json&url=" . $req_url;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($ch); 
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['status_code' => $httpcode, 'result' => $result];

    }


    public function msisdn2acr($msisdn) {
        $username='miaki-acr-by-msisdn';
        $password='JoUXX7xAKpoYcacnbC7cebTMhrfAzJ3t';
        $URL='https://stagingapi.comoyo.com/partner/acrs?msisdn=' . $msisdn;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close ($ch);
        return $result;
    }


}







