<?php

namespace App\Http\Controllers\MA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\MaCallBackLog;
use App\Models\MaRenewalLog;
use App\Models\MaRetryLog;
use Session;

class MaCallbackController extends Controller
{
    private $vDay;
    public function callback(Request $request) {
    	$callback = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	\Log::info($callback);
        $data = $request->all();
    	$msisdn = $data['callingParty'];
        if(array_key_exists('resultCode', $data)) {
            $resultCode = $data['resultCode'];
        }
        if(array_key_exists('result', $data)) {
            $message = $data['result'];
        }
        if(array_key_exists('operationId', $data)) {
            $operationId = $data['operationId'];
        }
        
        if(array_key_exists('sequenceNo', $data)) {
            $tranid = $data['sequenceNo'];    
        }

        if(array_key_exists('chargeAmount', $data)) {
            $chargeAmount = $data['chargeAmount'];
        }
        if(array_key_exists('validityDays', $data)) {
            $validityDays = $data['validityDays'];
            $this->vDay = $validityDays;
        }

        // User Creation for MO user
        $user = UserModel::where('plain_msisdn', $msisdn)->first();
        if(empty($user)) {
            $user = new UserModel;
            $user->operator_id = 4;
            $user->moStatus = 1;
            $user->plain_msisdn = $msisdn;
            $user->encrypted_msisdn = getUUID();
            $user->save();
        }
        
        // $channelrow = getTmpChannel($msisdn);
        // $channel = $channelrow->channel;
        $channel = 'test';

        if(array_key_exists('resultCode', $data)) {
            switch ($resultCode) {
                case '0':
                    switch ($operationId) {
                        // New Subscribe Case
                        case "SN":
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            if(!$row){
                                \Log::info(getMaRenewalDate(1));
                                $this->subscribercreate($user->id, $tranid);
                                $this->subscriberlog($user->id, $channel);
                            } else {
                                SubscriberModel::find($row->id)->delete();
                                $this->subscribercreate($user->id, $tranid);
                                $this->subscriberlog($user->id, $channel);
                                // $this->subscriberupdate($row->id, $user->id, $tranid);
                            }
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/success', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        // Unsubscribe Case
                        case 'ACI':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'PCI':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        // Renewal Success Case
                        case 'YR':
                        case 'YF':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->subscriberupdate($row->id, $user->id, $tranid);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, 'renewal', $tranid);
                            $this->maRenewalLog($user->id, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message );
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'RR':
                        case 'RF':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->subscriberupdate($row->id, $user->id, $tranid);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, 'renewal', $tranid);
                            $this->maRetryLog($user->id, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message );
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'SCI':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'YS':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'PD':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                        case 'PN':
                            $row = SubscriberModel::where('user_id', $user->id)->first();
                            if(!$row){
                                $this->subscribercreate($user->id, $tranid);
                                $this->subscriberlog($user->id, $channel);
                            } else {
                                $this->subscriberupdate($row->id, $user->id, $tranid);
                            }
                            $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/success', $tranid);
                            $response['status'] = 200;
                            return $response;
                            break;
                    }
                // You have Already Subscribed Requested Services
                case '2084':
                    $row = SubscriberModel::where('user_id', $user->id)->first();
                    if (0 == $row->is_active && 0 == $row->is_active) {
                        $data = [
                                'is_new_user' => 0,
                                'is_subscribed' => 1,
                                'is_active' => 1
                            ];
                        SubscriberModel::find($row->id)->update($data);
                        

                        $log = new SubscriberLogModel;
                        $log->user_id = $row->user_id;
                        $log->attempt_type = 1;
                        $log->channel_id = getChannelId($channel);
                        $log->attempt_type_status = 1;
                        $log->subscription_id = 1;
                        $log->event = 'SUBSCRIBED';
                        $log->save();
                    }
                    $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/success', $tranid);
                    $response['status'] = 200;
                    return $response;
                    break;
                // Subscriber has insufficient balance
                case '2032':
                    $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/error', $tranid);
                    $response['status'] = 200;
                    return $response;
                    break;
                    
                // MSISDN is Blacklisted
                case '4105':
                    $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/mpt/ma/error', $tranid);
                    $response['status'] = 200;
                    return $response;
                    break;
                default:
                    # code...
                    break;
            }

            if ($operationId == "YS") {
                $row = SubscriberModel::where('user_id', $user->id)->first();
                $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                $response['status'] = 200;
                return $response;
            }
        }

        if(array_key_exists('keyword', $data)) {
            $keywordArr = ['ON', 'OFF', 'HELP'];
            $keyword = strtoupper($data['keyword']);
            if($keyword != NULL) {
                if ($keyword == 'ON') {
                    $row = SubscriberModel::where('user_id', $user->id)->first();
                    if(!$row){
                        $this->updateMoStatus($user->id);
                        $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                        $this->subscribercreate($user->id, $tranid);
                        $this->subscriberlog($user->id, $channel);
                        $response['status'] = 200;
                        return $response;
                    } else {
                        if($row->is_subscribed == 1 && $row->is_active == 1) {
                            $response['status'] = 200;
                            return $response;
                        }
                        $this->updateMoStatus($user->id);
                        $this->subscriberupdate($row->id, $user->id, $tranid);
                        $response['status'] = 200;
                        return $response;
                    }
                } elseif($keyword == 'OFF') {
                    $row = SubscriberModel::where('user_id', $user->id)->first();
                    if($row) {
                        $this->unsubscribe($row->id, $user->id, $tranid, $channel);
                        $this->macallbaclog($user->id, $callback, '200', $resultCode, $message, '/', $tranid);
                        $response['status'] = 200;
                        return $response;
                    }
                    $response['status'] = 200;
                    return $response;
                } elseif($keyword == 'HELP') {
                    $response['status'] = 200;
                    return $response;
                } elseif(!in_array($keyword, $keywordArr)) {
                    $response['status'] = 200;
                    return $response;
                }
            }
        }

    }


    // Create
    private function subscribercreate($userId, $tranId) {
        SubscriberModel::create([
            'user_id' => $userId,
            'subscription_type_id' => getSubscriptionType("Daily"),
            'is_new_user' => 1,
            'is_subscribed' => 1,
            'is_active' => 1,
            'tranid' => $tranId,
            'valid_date' => \Carbon\Carbon::now()->AddDays(1)
        ]);
        // $subscriber = new SubscriberModel;
        // $subscriber->user_id = $userId;
        // $subscriber->subscription_type_id = getSubscriptionType("Daily");
        // $subscriber->is_new_user = 1;
        // $subscriber->is_subscribed = 1;
        // $subscriber->is_active = 1;
        // $subscriber->tran_id = $tranId;
        // $subscriber->valid_date = getMaRenewalDate(1);
        // $subscriber->save();
    }

    // Updated
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

    private function updateMoStatus($userId) {
        UserModel::find($userId)->update(['moStatus' => 1]);
    }

    private function macallbaclog($userId, $req, $res, $status_code, $msg, $link, $tranid) {
    	$log = new MaCallBackLog;
    	$log->user_id = $userId;
    	$log->reqBody = $req;
    	$log->resBody = $res;
        $log->status_code = $status_code;
        $log->tranid = $tranid;
        $log->message = $msg;
    	$log->link = $link;
    	$log->save();
    }

    private function maRenewalLog($userid, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message ){
            $log = new MaRenewalLog;
            $log->user_id = $userid;
            $log->msisdn = $msisdn;
            $log->tranid = $tranid;
            $log->chargeAmount = $chargeAmount;
            $log->validityDays = $validityDays;
            $log->operationId = $operationId;
            $log->status_code = $resultCode;
            $log->result = $message;
            $log->save();
    }

    private function maRetryLog($userid, $msisdn, $tranid, $chargeAmount, $validityDays, $operationId, $resultCode, $message ){
            $log = new MaRetryLog;
            $log->user_id = $userid;
            $log->msisdn = $msisdn;
            $log->tranid = $tranid;
            $log->chargeAmount = $chargeAmount;
            $log->validityDays = $validityDays;
            $log->operationId = $operationId;
            $log->status_code = $resultCode;
            $log->result = $message;
            $log->save();
    }
}







