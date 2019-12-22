<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MptNotifyLogModel;

class MtController extends Controller
{
    public function index(Request $request) {
    	$xmlfile = $request->getContent();
    	$xml = loadxml($xmlfile);
    	$arrayData = xmlToArray($xml);
    	$address = $arrayData['Envelope']['SOAP-ENV:Body']['sms7:notifySmsDeliveryReceipt']['sms7:deliveryStatus']['address'];
    	$deliveryStatus = $arrayData['Envelope']['SOAP-ENV:Body']['sms7:notifySmsDeliveryReceipt']['sms7:deliveryStatus']['deliveryStatus'];
    	$response = getMoResponse(200, 'successfull save the deliver record');
    	$log = new MptNotifyLogModel;
    	$log->address = $address;
    	$log->deliveryStatus = $deliveryStatus;
    	$log->mptReqBody = $xmlfile;
    	$log->ggResBody = $response;
    	$log->save();
    	return $response;
    }
}
