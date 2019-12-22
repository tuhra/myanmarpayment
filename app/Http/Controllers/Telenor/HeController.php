<?php

namespace App\Http\Controllers\Telenor;

use App\AffiliateServices\AffiseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\OperatorHelper;
use App\Helper\SmsHelper;
use App\Helper\CallbackHelper;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\TelenorHeLogModel;
use App\Models\TelenorPaymentLogModel;
use App\Models\TelenorSMSLogModel;
use App\Models\TelenorPinCreateLog;
use App\Models\TelenorOtpCheckLog;
use App\Models\DndModel;
use App\Http\Controllers\Telenor\TelenorController;
use Session;
use Kimia;
use App\Models\SocialCampaignClick;
use App\Models\HasOffer;
use App\AffiliateServices\HasOfferService;
use Carbon\Carbon;


class HeController extends Controller
{
    private $hasOfferService;
    private $affiseService;

    function __construct(HasOfferService $hasOfferService, AffiseService $affiseService)
    {
        $this->hasOfferService = $hasOfferService;
        $this->affiseService = $affiseService;
    }

    public function callback(Request $request) {

        $json = $request->getContent();
        $array = json_decode($json, true);

        $log = new TelenorHeLogModel;
        $log->refId = $array['referenceId'];
        $log->resBody = $json;
        $log->save();
    }

    public function hesubscribe(Request $request) {

        $refId = $request->refId;

        $result = TelenorHeLogModel::where('refId', $refId)->first();
        $response = [];
        if (empty($result)) {
            // Return to OTP
            $response['status'] = false;
            $response['callback'] = null;
            return json_encode($response);
        }

        $json = $result->resBody;   
        $array = json_decode($json, true);
        if (!$array['success'] && empty($array['acr'])) {
            // Return to OTP
            $response['status'] = false;
            $response['callback'] = null;
            return $response;
        } else {
            $msisdn = mb_substr($array['acr'], 0, 15);
            $diff = check_dnd_status($msisdn);
            if ($diff) {
                $response['status'] = true;
                $response['callback'] = '/telenor/dnd';
                return $response;
                // return json_encode($response);
            }
            $endUserId = $array['acr'];
            Session::put('msisdn', $msisdn);
            Session::put('endUserId', $endUserId);
            $url = telenorConsentPageRequest($endUserId);
            $response['status'] = true;
            $response['callback'] = $url;
            return $response;
        }
    }

    public function telenor_exist() {
        return view('messages.telenor_exist');
    }

    public function subscriber_log_creation($userid) {

        $channel = (getSocialID()) ? 'Social' : 'WEB';
        if (Session::has('ott')) {
            $channel = 'Apps';
        }
        if(getKpValue()) {
            $channel = 'Armor';
        }
        $log = new SubscriberLogModel;
        $log->user_id = $userid;
        $log->attempt_type = 1;
        $log->channel_id = channel_id($channel);
        $log->attempt_type_status = 1;
        $log->event = 'SUBSCRIBED';
        $log->save();
    }

    public function sms_log_creation($userid, $req, $res, $status_code) {
        $smslog = new TelenorSMSLogModel;
        $smslog->user_id = $userid;
        $smslog->request_msg = $req;
        $smslog->response_msg = $res;
        $smslog->response_status_code = $status_code;
        $smslog->save();
    }

}







