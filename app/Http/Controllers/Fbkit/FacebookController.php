<?php

namespace App\Http\Controllers\Fbkit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\FbkitHelper;

class FacebookController extends Controller
{
    public function verify(Request $request) {

    	$fbkithelper = new FbkitHelper;
    	$code=$request->get('code');
    	$info = $fbkithelper->verify($code);

    	$number = $info['phone']['number'];
    	$msisdn = $info['phone']['country_prefix'] . $info['phone']['national_number'];

    	setMsisdn($msisdn);
    	$country_id = country($number);
    	$operator_id = operator($number,$country_id);
    	$operator_name = getOperator($operator_id);
    	setOptId(2);

    	switch ($operator_name) {
    		case 'Telenor':
    			return redirect('/telenor');
    			break;
    		
    		default:
    			return redirect('/mpt/charge');
    			break;
    	}


    }
}
