<?php

namespace App\Helper;

use App;
use App\Models\OperatorModel;
use App\Models\CountryModel;
use Propaganistas\LaravelPhone\PhoneNumber;

class OperatorHelper 
{

	public function getUUID()
	{
		return rand(100,999).time().rand(100,999);
	}

	public function country($msisdn)
	{
		$geoCoder = \libphonenumber\geocoding\PhoneNumberOfflineGeocoder::getInstance();
		$gbNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
		$country=$geoCoder->getDescriptionForNumber($gbNumber, 'en_GB', 'US');
		$row=CountryModel::where('name',$country)->first();
		if(!$row){
		   $row=new CountryModel;
		   $row->name=$country;
		   $row->save();
		}
		return $row->id;
	}

	public function operator($msisdn,$country_id)
	{
		$carrierMapper = \libphonenumber\PhoneNumberToCarrierMapper::getInstance();
		$chNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
		$operator_name=$carrierMapper->getNameForNumber($chNumber, 'en');
		$row=OperatorModel::where('name',$operator_name)->first();
		if(!$row){
		   $row=new OperatorModel;
		   $row->country_id=$country_id;
		   $row->name=$operator_name;
		   $row->save();
		}
		return $row->id;
	}


}