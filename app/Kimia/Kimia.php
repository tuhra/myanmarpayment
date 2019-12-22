<?php

namespace App\Kimia;
use App\Models\KimiaCpaModel;
use App\Models\KimiaCpcModel;

class Kimia
{
	private $kp_value,
	        $raw_request;

	public function cpa($kp_value, $user_id) {
		// check the user id is already exist or not
		$response = false;
		$isUserExist = KimiaCpaModel::where('user_id', $user_id)->first();
		if($isUserExist === null) {
		  	$this->kp_value = $kp_value;
		  	$this->raw_request=$this->url();
		  	$this->request($this->raw_request);  
		  	$row = new KimiaCpaModel;
		  	$row->user_id = $user_id;
		  	$row->kp_value = $this->kp_value;
		  	$row->raw_request = $this->raw_request;
		  	$row->save();
		  	$response = true;
		}
		// return $response;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->raw_request); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$content = trim(curl_exec($ch));
		curl_close($ch);

	}

	private function url()
	{
		return "http://secure-conversion.com/conversion_get/pixel.jpg?kp=". $this->kp_value;
	 	// return "http://adserver.kimia.es/conversion/".$this->kp_value."/pixel.jpg";
	}

	private function request($url)
	{
	  	return file_get_contents($url);
	}

    public function handleKpRequest($request){
        if(!$request->has('kp')) return;

        $kp_value = $request->get('kp');
        $row=new KimiaCpcModel;
        $row->kp_value = $kp_value;
        $row->save();

        setKpValue($kp_value);
    }

}