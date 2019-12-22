<?php

namespace App\Http\Controllers\MA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\MptHelper;
use Session;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\MaOtpSmsLog;
use App\Models\MaOtpValidLog;
use App\Models\MaCallBackLog;
use App\Helper\CallbackHelper;
use DB;
use App\Helper\Rc4Helper;
use App\Helper\EncDecHelper;
use Maatwebsite\Excel\Facades\Excel;
use Route;
use App\Models\MaRenewalLog;
use App\Models\MaRetryLog;

class MptController extends Controller
{
    public function index() {
        // \Log::info('MptController,line:23, index Method,MPT selection'); //TODO need to remove
        return view('ma.valueposition');
    }

    public function webvalueposition() {
        \Log::info('MptController,line:27, webvalueposition Method,MPT selection'); //TODO need to remove
        return view('ma.webvalueposition');
    }

    public function sendopt(Request $request) {
    	if(Session::get('he_msisdn')) {
            $msisdn = Session::get('he_msisdn');
    	}

        $msisdn = $request->msisdn;
        $prefix = substr($msisdn, 0, 2);
        if ($prefix == '09') {
            $msisdn = ltrim($msisdn, '0');
            $msisdn = '95' . $msisdn;

            $operator = getopr("+" . $msisdn);

            if ($operator == 'Ooredoo' || $operator == 'Telenor') {
                $message['message'] = trans('app.msisdn_valid_text');
                return view('ma.valueposition', compact('message'));
            }

            setMsisdn($msisdn);

        } elseif ($prefix !== '09') {
            $msisdn = '95' . $msisdn;
            $operator = getopr("+" . $msisdn);

            if ($operator == 'Ooredoo' || $operator == 'Telenor') {
                $message['message'] = trans('app.msisdn_valid_text');
                return view('ma.valueposition', compact('message'));
            }

            setMsisdn($msisdn);
        }else {
            $message = [];
            $message['message'] = trans('app.ma_msisdn_invalid');
            return view('ma.valueposition', compact('message'));
        }

        // if (!Session::get('he_msisdn')) {
            
        // }

    	$user = UserModel::where('plain_msisdn', $msisdn)->first();
    	// if ($user) {
    	// 	$subscriber = SubscriberModel::where('user_id', $user->id)->first();
    	// 	if($subscriber) {
    	// 		if ($subscriber->is_subscribed == 1 && $subscriber->is_active == 1) {
    	// 			return redirect(route('subscribed'));
    	// 		}
    	// 	}
    	// }

    	if (empty($user)) {
    		$user = new UserModel;
    		$user->operator_id = 4;
    		$user->plain_msisdn = $msisdn;
    		$user->encrypted_msisdn = getUUID();
    		$user->save();
    	}

        Session::put('user_id', $user->id);

        createTmpChannel();


    	$sms = new MptHelper();
    	$res = $sms->maoptsms($msisdn);
        \Log::info($res['res']);
        $xml = $res['res'];
    	$xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
    	$json = json_encode($xml);
        \Log::info($json);
    	$result = json_decode($json,TRUE);
    	Session::put('mpt_tranid', $result['transId']);
        $this->maotpsmslog($user->id, $res['req'], $res['res'], $result['error_code']);
    	return redirect(route('otp'));

    	if ($result['error_code'] === '0' || $result['error_code'] === '506') {
    		return redirect(route('otp'));
    	}
    }

    private function CheckOperatorMessage($msisdn) {
        
    }

    public function otp() {
    	return view('ma.otp');
    }

    public function subscribed() {
    	return view('ma.subscribed');
    }

    public function postOtp(Request $request) {

        if(Session::get('he_msisdn')) {
            $msisdn = Session::get('he_msisdn');
        }

        if(getMsisdn()) {
            $msisdn = Session::get('msisdn');
        }

        $otp = $request->pin;
        $sms = new MptHelper();
        $res = $sms->sendotp($otp, $msisdn);
        \Log::info($res['res']);
        $xml = $res['res'];
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        \Log::info($json);
        $result = json_decode($json,TRUE);
        $this->maotpvalidlog(Session::get('user_id'), $res['req'], $res['res'], $result['error_code']);
        if($result['error_code'] === '504') {
            $message = [];
            $message['message'] = trans('app.ma_opt_invalid');
            return view('ma.otp', compact('message'));
        }
        if($result['error_code'] == '503') {
            $message = [];
            $message['message'] = trans('app.otp_expire_text');
            return view('ma.otp', compact('message'));
        }
        
        if ($result['error_code'] === '0') {
            return redirect(route('maloading'));
        }
        return redirect(route('maloading'));

        // Session::put('otp', $request->pin);
        // return redirect('mpt/ma/T&C');
    }

    public function verify(Request $request) {
        if(Session::get('he_msisdn')) {
            $msisdn = Session::get('he_msisdn');
        }

        if(getMsisdn()) {
            $msisdn = Session::get('msisdn');
        }

        // $otp = $request->pin;
        $otp = Session::get('otp');
        $sms = new MptHelper();
        $res = $sms->sendotp($otp, $msisdn);
        $xml = $res['res'];
        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $result = json_decode($json,TRUE);
        $this->maotpvalidlog(Session::get('user_id'), $res['req'], $res['res'], $result['error_code']);
        if($result['error_code'] === '504') {
            $message = [];
            $message['message'] = trans('app.ma_opt_invalid');
            return view('ma.otp', compact('message'));
        }
        if($result['error_code'] == '503') {
            $message = [];
            $message['message'] = trans('app.otp_expire_text');
            return view('ma.otp', compact('message'));
        }
        
        if ($result['error_code'] === '0') {
            return redirect(route('maloading'));
        }
        return redirect(route('maloading'));
    }

    public function otpRegeneration(Request $request) {
    	// $msisdn = getMsisdn();
        if(Session::get('he_msisdn')) {
            $msisdn = Session::get('he_msisdn');
        }

        if(getMsisdn()) {
            $msisdn = Session::get('msisdn');
        }
    	$sms = new MptHelper();
    	$res = $sms->otpRegeneration($msisdn);
        \Log::info($res['res']);
        $xml = $res['res'];
    	$xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
    	$json = json_encode($xml);
    	$result = json_decode($json,TRUE);
        \Log::info($result);
    	Session::put('mpt_tranid', $result['transId']);
    	$response = [];
    	if ($result['error_code'] === '0' || $result['error_code'] === '506') {
    		$response['status'] = true;
    	} else {
    		$response['status'] = false;
    	}
    	return $response;
    }

    public function loading() {
        // dd(Session::all());
    	return view('ma.loading');
    }

    public function checkStatus() {
        $data = MaCallBackLog::where('tranid', Session::get('mpt_tranid'))->first();
        $response = [];
        if(empty($data)) {
            $response['link'] = '/mpt/ma/insufficient';
            return $response;
        }
        $response['link'] = $data->link;
        return $response;

    }

    public function success() {
        $data = MaCallBackLog::where('tranid', Session::get('mpt_tranid'))->first();
        return view('ma.success', compact('data'));
    }

    public function error() {
        $data = MaCallBackLog::where('tranid', Session::get('mpt_tranid'))->first();
        return view('ma.error', compact('data'));
    }

    public function insufficient() {
        return view('ma.insufficient');
    }

    public function tandc(Request $request) {
        return view('ma.tandc');
    }

    public function inapptandc() {
        return view('ma.inapptandc');   
    }

    public function fulltandc(Request $request) {
        return view('ma.fulltandc');
    }

    private function maotpsmslog($userid, $reqBody, $resBody, $statusCode) {
        $log = new MaOtpSmsLog;
        $log->user_id = $userid;
        $log->tranId = Session::get('mpt_tranid');
        $log->reqBody = $reqBody;
        $log->resBody = $resBody;
        $log->response_status_code = $statusCode;
        $log->save();
    }

    private function maotpvalidlog($userid, $reqBody, $resBody, $statusCode) {
        $log = new MaOtpValidLog;
        $log->user_id = $userid;
        $log->tranId = Session::get('mpt_tranid');
        $log->reqBody = $reqBody;
        $log->resBody = $resBody;
        $log->response_status_code = $statusCode;
        $log->save();
    }

    public function continue_process() {
        $cbthelper = new CallbackHelper;
        return $cbthelper->callbacktoken();
    }

    public function websubscribe() {
        return redirect('http://macnt.mpt.com.mm/API/CGRequest?productID=9320&pName=GoGames&pPrice=99&pVal=1&CpId=MIAKI&CpPwd=miaki@123&CpName=MIAKI&reqMode=WEB&reqType=SUBSCRIPTION&ismID=17&transID='. getUUID() .'&sRenewalPrice=99&sRenewalValidity=1&serviceType=T_MIA_GOG_SUB_D&planId=T_MIA_GOG_SUB_D_99&Wap_mdata=http://wave.gogamesapp.com/logo/gogames-logo.png&request_locale=my');
    }

    public function redirect(Request $request) {
        // $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // \Log::info($actual_link);
        $data = $request->all();
        $msisdn = '95' . $data['MSISDN'];
        setMsisdn($msisdn);
        $tranid = $data['transID'];
        Session::put('mpt_tranid', $tranid);
        $result = $data['Result'];
        if ($result == 'SUCCESS') {
            $user = UserModel::where('plain_msisdn', $msisdn)->first();
            if(empty($user)) {
                $user = new UserModel;
                $user->operator_id = 4;
                $user->moStatus = 1;
                $user->plain_msisdn = $msisdn;
                $user->encrypted_msisdn = getUUID();
                $user->save();
            }
            createTmpChannel();
            Session::put('user_id', $user->id);
            $row = SubscriberModel::where('user_id', $user->id)->first();
            if(!$row){
                return redirect(route('maloading'));
            } else {
                if ($row->is_subscribed == 1 && $row->is_active == 1) {
                    return redirect(route('subscribed'));
                }
                return redirect(route('maloading'));
            }
        } else {
            return redirect(route('maloading'));
        }        
    }


    public function msisdn(Request $request) {
        var_dump($request->header());
        if ($request->header('x-msisdn')) {
            $rc4 = new Rc4Helper;
            $key = 'bNf&CZ=MvqBssZ9y';
            $msisdn = "";
            $encrypted_msisdn = $request->header('x-msisdn');
            $hash = md5($key,true);
            $msisdn = $rc4->rc4($hash, base64_decode($encrypted_msisdn));
            var_dump("decrypted msisdn => " . $msisdn);
        }

    }

    public function json(Request $request) {
        $data = array();
        $rows = Excel::load(public_path() . '/5thCallback.xlsx')->get();
        $array = [];
        foreach ($rows as $row=>$value) {
            foreach ($value as $key => $v) {
                $array[] = ['date' => $v['date'], 'call_back' => $v['call_back']];
            }
        }

        $json = json_encode($array, JSON_PRETTY_PRINT);
        file_put_contents(public_path('5thJson.json'), stripslashes($json));
        $channel = 'APP';
        $res = $response['status'] = 200;
    }

    public function revenue(Request $request) {

        $file = $request->get('file');
        $rows = json_decode(file_get_contents(public_path($file)), true);;
        $count = 0;
        $channel = 'APP';

        foreach ($rows as $row=>$value) {
            $created_at = $value['date'];
            $created_at = trim($created_at, " ");
            $callback = $value['call_back'];
            $arr = explode("?", $value['call_back']);
            $request = Request::create('/api/revenue?' . $arr[1], 'GET');
            $data = $request->input();
            $count ++;
            if(array_key_exists('callingParty', $data)) {
                $msisdn = $data['callingParty'];
            }
            if(array_key_exists('resultCode', $data)) {
                $resultCode = $data['resultCode'];
            } else {
                $resultCode = 1001;
            }
            if(array_key_exists('result', $data)) {
                $message = $data['result'];
            }else {
                $message = 'wrong keywork';
            }
            if(array_key_exists('operationId', $data)) {
                $operationId = $data['operationId'];
            }
            
            if(array_key_exists('sequenceNo', $data)) {
                $tranid = $data['sequenceNo'];    
            }

            if(!array_key_exists('sequenceNo', $data)) {
                $tranid = 1001;
            }

            if(array_key_exists('chargeAmount', $data)) {
                $chargeAmount = $data['chargeAmount'];
            }
            if(array_key_exists('validityDays', $data)) {
                $validityDays = $data['validityDays'];
            }

            $user = UserModel::where('plain_msisdn', $msisdn)->first();
            if(empty($user)) {
                $user = new UserModel;
                $user->operator_id = 4;
                $user->moStatus = 1;
                $user->plain_msisdn = $msisdn;
                $user->encrypted_msisdn = getUUID();
                $user->save();
            }

            $row = SubscriberModel::where('user_id', $user->id)->first();
            if(!$row){
                $this->subscribercreate($user->id, $tranid);
                $this->subscriberlog($user->id, $channel);
            } else {
                $this->subscriberupdate($row->id, $user->id, $tranid);
            }

            if(array_key_exists('resultCode', $data)) {
                switch ($resultCode) {
                    case '0':
                        switch ($operationId) {
                            // New Subscribe Case
                            case 'SN':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                if(!$row){
                                    $this->subscribercreate($user->id, $tranid);
                                    $this->subscriberlog($user->id, $channel);
                                } else {
                                    $this->subscriberupdate($row->id, $user->id, $tranid);
                                }
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/success', $tranid, $created_at);
                                break;
                            // Unsubscribe Case
                            case 'ACI':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                                break;
                            case 'PCI':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                                break;
                            // Renewal Success Case
                            case 'YR':
                            case 'YF':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->subscriberupdate($row->id, $user->id, $tranid);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, 'renewal', $tranid, $created_at);
                                $this->maRenewalLog($user->id, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message, $created_at );
                                break;
                            case 'RR':
                            case 'RF':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->subscriberupdate($row->id, $user->id, $tranid);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, 'renewal', $tranid, $created_at);
                                $this->maRetryLog($user->id, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message, $created_at);
                                break;
                            case 'SCI':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                                break;
                            case 'YS':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                                break;
                            case 'PD':
                                $row = SubscriberModel::where('user_id', $user->id)->first();
                                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                                break;
                        }
                        break;
                    // You have Already Subscribed Requested Services
                    case '2084':
                        $row = SubscriberModel::where('user_id', $user->id)->first();
                        if (0 == $row->is_active && 0 == $row->is_active) {
                            $update = [
                                    'is_new_user' => 0,
                                    'is_subscribed' => 1,
                                    'is_active' => 1
                                ];
                            SubscriberModel::find($row->id)->update($update);

                            $log = new SubscriberLogModel;
                            $log->user_id = $row->user_id;
                            $log->attempt_type = 1;
                            $log->channel_id = getChannelId($channel);
                            $log->attempt_type_status = 1;
                            $log->subscription_id = 1;
                            $log->event = 'SUBSCRIBED';
                            $log->save();
                        }
                        $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/success', $tranid, $created_at);
                    // Subscriber has insufficient balance
                    case '2032':
                        $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/error', $tranid, $created_at);
                        break;
                    // MSISDN is Blacklisted
                    case '4105':
                        $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/error', $tranid, $created_at);
                        break;
                }
            }

            if(array_key_exists('hexMsg', $data)) {
                $keywordArr = ['ON', 'OFF', 'HELP'];
                $keyword = strtoupper($data['hexMsg']);
                if($keyword != NULL) {
                    if ($keyword == 'ON') {
                        $row = SubscriberModel::where('user_id', $user->id)->first();
                        if(!$row){
                            $this->updateMoStatus($user->id);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                            $this->subscribercreate($user->id, $tranid);
                            $this->subscriberlog($user->id, $channel);
                            $response['status'] = 200;
                            return $response;
                        } else {
                            $this->updateMoStatus($user->id);
                            $this->subscriberupdate($row->id, $user->id, $tranid);
                        }
                    } elseif($keyword == 'OFF') {
                        $row = SubscriberModel::where('user_id', $user->id)->first();
                        if($row) {
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                        }
                    } else {
                        // if (!empty($data['hexMsg']) || NULL == $data['hexMsg']) {
                        //     $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid, $created_at);
                        //     \Log::info('Wrong Keyword');
                        // }
                    }
                }
            }

        }

    }


    private function macallbaclog($userId, $req, $res, $status_code = 10001, $msg = null, $link = null, $tranid, $date) {
        $log = new MaCallBackLog;
        $log->user_id = $userId;
        $log->reqBody = $req;
        $log->resBody = $res;
        $log->status_code = $status_code;
        $log->tranid = $tranid;
        $log->message = $msg;
        $log->link = $link;
        $log->created_at = $date;
        $log->updated_at =$date;
        $log->save();
    }

    private function subscribercreate($userId, $tranId) {
        $subscriber = new SubscriberModel;
        $subscriber->user_id = $userId;
        $subscriber->subscription_type_id = getSubscriptionType("Daily");
        $subscriber->is_new_user = 1;
        $subscriber->is_subscribed = 1;
        $subscriber->is_active = 1;
        $subscriber->tran_id = $tranId;
        $subscriber->valid_date = getMaRenewalDate(1);
        $subscriber->save();
    }

    private function subscriberupdate($id, $userId, $tranId){
        $subscriber = SubscriberModel::find($id);
        $subscriber->user_id = $userId;
        $subscriber->subscription_type_id = getSubscriptionType("Daily");
        $subscriber->is_new_user = 0;
        $subscriber->is_subscribed = 1;
        $subscriber->is_active = 1;
        $subscriber->tran_id = $tranId;
        $subscriber->valid_date = getMaRenewalDate(1);
        $subscriber->save();
    }

    private function unsubscribe($id, $userId, $tranId, $channel) {
        $data = [
                'is_new_user' => 0,
                'is_subscribed' => 0,
                'is_active' => 0,
                'tran_id' => $tranId
            ];
        $row = SubscriberModel::find($id)->update($data);

        $log = new SubscriberLogModel;
        $log->user_id = $userId;
        $log->attempt_type = 1;
        $log->channel_id = getChannelId($channel);
        $log->attempt_type_status = 1;
        $log->subscription_id = 1;
        $log->event = 'UNSUBSCRIBED';
        $log->save();
    }

    private function subscriberlog($userId, $channel) {
        $log = new SubscriberLogModel;
        $log->user_id = $userId;
        $log->attempt_type = 1;
        $log->channel_id = getChannelId($channel);
        $log->attempt_type_status = 1;
        $log->subscription_id = 1;
        $log->event = 'SUBSCRIBED';
        $log->save();
    }

    private function maRenewalLog($userid, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message, $date ){
            $log = new MaRenewalLog;
            $log->user_id = $userid;
            $log->msisdn = $msisdn;
            $log->tranid = $tranid;
            $log->chargeAmount = $chargeAmount;
            $log->validityDays = $validityDays;
            $log->operationId = $operationId;
            $log->status_code = $resultCode;
            $log->result = $message;
            $log->created_at = $date;
            $log->updated_at =$date;
            $log->save();
    }

    private function maRetryLog($userid, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message, $date ){
            $log = new MaRetryLog;
            $log->user_id = $userid;
            $log->msisdn = $msisdn;
            $log->tranid = $tranid;
            $log->chargeAmount = $chargeAmount;
            $log->validityDays = $validityDays;
            $log->operationId = $operationId;
            $log->status_code = $resultCode;
            $log->result = $message;
            $log->created_at = $date;
            $log->updated_at =$date;
            $log->save();
    }


}







