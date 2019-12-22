<?php

namespace App\Helper;

use App;
use App\Models\UserSource;
use ClassPreloader\Config;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\CallbackModelAppland;
use Session;
use App\Models\Callback;
use Kimia;
use App\Models\SubscriberModel;
use App\Models\SocialCampaignClick;
use App\Models\HasOffer;
use App\AffiliateServices\HasOfferService;

class CallbackHelper {

    private $time;

    public function callbacktoken() {


        $this->time = time();
        if(Session::get('he_msisdn')) {
            $msisdn = Session::get('he_msisdn');
        }

        if(getMsisdn()) {
            $msisdn = Session::get('msisdn');
        }
        $user = UserModel::where('plain_msisdn', $msisdn)->first();
       /* $user = UserModel::where('plain_msisdn', 'azrUBb959L3QPjC')->first();*/

        if(empty($user)) {
            $user = UserModel::find(Session::get('user_id'));
            if (empty($user)) {
                $response = [];
                $response['message'] = "something want wrong!";
                return $response;
            } 
        }

        //has offer flow
//        if ($this->getHasOfferData()) {
//           $this->successfulConversion(Session::get('hasOfferData'), $user->id);
//        } elseif (getSocialID()) {
//            $subscribe_info = SubscriberModel::UserId($user->id)->first();
//            if (isset($subscribe_info->is_new_user) && $subscribe_info->is_new_user == 1) {
//                SocialCampaignClick::where('id', getSocialID())
//                    ->update(['status' => 1, 'user_id' => $user->id]);
//                unsetSocialID();
//            }
//        }

        // if (getKpValue()) {
        //     Kimia::cpa(getKpValue(), $user->id);
        //     unsetKpValue();
        // } elseif (getSocialID()) {
        //     $subscribe_info = SubscriberModel::UserId($user->id)->first();
        //     if (isset($subscribe_info->is_new_user) && $subscribe_info->is_new_user == 1) {
        //         SocialCampaignClick::where('id', getSocialID())
        //                 ->update(['status' => 1, 'user_id' => $user->id]);
        //         unsetSocialID();
        //     }
        // }

        $data = ["user" => $user->encrypted_msisdn, "timestamp" => $this->time, "key" => config('customauth.key'), "signature" => $this->getSignature($user->encrypted_msisdn)];
        $token = base64_encode(json_encode($data));

        // if (Session::has("payment")) {
        //     $redirect = $this->getCallback();
        // } else {
        //     $redirect = $this->getCallback();
        // }

        // $redirect = $this->getCallback();

        // if (Session::has('ott')) {
        //     $url = $redirect . '&token=' . $token;
        // } else {
        //     $url = $redirect . '?token=' . $token;
        // }

        // return redirect($url);

        return redirect($this->getRedirectUrl($user));
    }

    private function getCallback() {
        // if (config('customauth.APP_ENV') == 'staging') {
        //     $redirect = "http://miakimock.applandstore.com/api/session/event/verify-subscription-widget/bn";
        // } else {
        //     $redirect = Session::get('callback') ? Session::get('callback') : "http://mm.gogamesapp.com/api/session/event/verify-subscription-widget";
        // }
        $redirect = Session::get('callback') ? Session::get('callback') : "http://mm.gogamesapp.com/api/session/event/verify-subscription-widget";
        return $redirect;
    }

    public function hecallback() {
        $this->time = time();

        $endUserId = Session::get('endUserId');
        $user = UserModel::where('endUserId', $endUserId)->first();

        if(empty($user)) {
            $response = [];
            $response['message'] = "something want wrong!";
            return $response;
        }

//        if ($this->getHasOfferData()) {
//            $this->successfulConversion(Session::get('hasOfferData'), $user->id);
//        }

        if (getKpValue()) {
            Kimia::cpa(getKpValue(), $user->id);
            unsetKpValue();
        }

        $data = ["user" => $user->encrypted_msisdn, "timestamp" => $this->time, "key" => config('customauth.key'), "signature" => $this->getSignature($user->encrypted_msisdn)];
        $token = base64_encode(json_encode($data));

        $redirect = $this->getCallback();

        if (Session::has('ott')) {
            $url = $redirect . '&token=' . $token;
        } else {
            $url = $redirect . '?token=' . $token;
        }
        $this->callback_token_log(json_encode($data), $url, $user->id);
        // return redirect($this->getRedirectUrl($user));
    }

    private function callback_token_log($data, $url, $user_id) {
        $log = new CallbackModelAppland;
        $log->user_id = $user_id;
        $log->user_info = $data;
        $log->raw_request = $url;
        $log->raw_response = 'ok';
        $log->save();
    }

    private function getSignature($user) {
        $payload = $user . "\r\n" . $this->time;
        return $this->base64url_encode((hash_hmac('sha256', $payload, config('customauth.secret'), true)));
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

//    private function getHasOfferData(){
//        if(Session::get('hasOfferData')){
//            return true;
//        }
//        else{
//            return false;
//        }
//    }

//    private function successfulConversion($hasOfferData, $userId){
//        $hasOfferIns = HasOffer::where('id', $hasOfferData['id'])->first();
//        if(!$hasOfferIns) return;
//
//        $hasOfferIns->user_id = $userId;
//        $hasOfferIns->raw_request = $this->generateHasOfferCallbackUrl($hasOfferData['offerId'], $hasOfferData['transaction_id']);
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $hasOfferIns->raw_request);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
//        $content = curl_exec($ch);
//        curl_close($ch);
//
//        if($content){
//            $responseArr = explode(';',$content);
//            $isSuccess = false;
//
//            foreach ($responseArr as $temp){
//                $tempArr = explode('=',$temp);
//                if($tempArr[0] == "success") {
//                    $isSuccess = $tempArr[1];
//                    break;
//                }
//            }
//            if($isSuccess == 'true'){
//                $hasOfferIns->conversion_happened = 1;
//            }
//        }
//
//        $hasOfferIns->raw_response = $content;
//        $hasOfferIns->save();
//
//        Session::forget('hasOfferData');
//    }
//
//    private function generateHasOfferCallbackUrl($offerId, $transId){
//        return 'http://gogames.go2cloud.org/aff_lsr?offer_id='.$offerId.'&transaction_id='.$transId;
//    }

    private function getRedirectUrl($user) {
        //if comming from appland redirect to appland, else redirect to click2play from success page continue button event
        if($this->isApplandRequestReferer() || Session::get('callback')){
             $redirect = Session::get('callback') ? Session::get('callback') : config('c2p.apk_download_link');
            //$redirect = Session::get('callback') ? Session::get('callback') : "http://miakimock.applandstore.com/api/session/event/verify-subscription-widget/bn";

            $userInfo = ["user" => $user->encrypted_msisdn, "timestamp" => $this->time, "key" => config('customauth.key'), "signature" => $this->getSignature($user->encrypted_msisdn)];
            $token = base64_encode(json_encode($userInfo));

            if (Session::has('ott')) {
                $url = $redirect . '&token=' . $token;
            } else {
                $url = $redirect . '?token=' . $token;
            }
        }else{
            $userInfo = ["user" => $user->encrypted_msisdn, "timestamp" => $this->time, "from" => config('c2p.club_name')];
            $url = config('c2p.c2p_domain') .'?user=' .$user->encrypted_msisdn;
        }

        $this->createUserSourceLog($user->id);
        $this->callback_token_log(json_encode($userInfo), $url, $user->id);
        return $url;
    }

    private function isApplandRequestReferer(){
        $reqReferer = Session::get('reqReferer');
        $applandIdentifier = config('c2p.appland_identifier_in_req_referer');

        if (strpos($reqReferer, $applandIdentifier) === false) {
            return false;
        }
        else {
            return true;
        }
    }

    private function createUserSourceLog($userId){
        $prvUsrSrc = UserSource::where('user_id', $userId)->first();
        if($prvUsrSrc) return;

        $userSrc = new UserSource();
        $userSrc->user_id = $userId;
        $userSrc->src = Session::get('reqReferer')? Session::get('reqReferer'): null;
        $userSrc->save();
    }



}
