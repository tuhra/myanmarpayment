<?php

namespace App\Http\Controllers\Telenor;

use App\AffiliateServices\AffiseService;
use App\AffiliateServices\HasOfferService;
use Illuminate\Http\Request;
use App\Http\Requests\MsisdnRequest;
use App\Http\Controllers\Controller;
use App\Helper\OperatorHelper;
use App\Helper\SmsHelper;
use App\Helper\CallbackHelper;
use App\Models\OperatorModel;
use Session;
use App\Http\Requests\OtpRequest;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\UserModel;
use App\Models\TelenorPaymentLogModel;
use App\Models\TelenorSMSLogModel;
use App\Models\TelenorPinCreateLog;
use App\Models\TelenorOtpCheckLog;
use App\Models\TelenorChargingLogModel;
use App\Models\DndModel;
use Kimia;
use App\Models\SocialCampaignClick;
use App\Models\HasOffer;
use Carbon\Carbon;

class TelenorController extends Controller {

    private $hasOfferService;
    private $affiseService;

    function __construct(HasOfferService $hasOfferService, AffiseService $affiseService)
    {
        $this->hasOfferService = $hasOfferService;
        $this->affiseService = $affiseService;
    }

    public function telenorconsent(Request $request) {
        $data = $request->all();
        $endUserId = $data['endUserId'];
        $url = telenorConsentPageRequest($endUserId);
        return redirect($url);
    }


    public function valueposition(Request $request) {
        setConsentId($request->all());
        return view('telenor.price_point');
    }

    public function tandc() {
        return view('telenor.T&C');
    }


    public function sms_log_creation($userid, $req, $res, $status_code) {
        $smslog = new TelenorSMSLogModel;
        $smslog->user_id = $userid;
        $smslog->request_msg = $req;
        $smslog->response_msg = $res;
        $smslog->response_status_code = $status_code;
        $smslog->save();
    }

    public function pin_creation_log($req, $res, $status_code) {
        $pinlog = new TelenorPinCreateLog;
        $pinlog->request_msg = $req;
        $pinlog->response_msg = $res;
        $pinlog->response_status_code = $status_code;
        $pinlog->save();
    }

    public function otp_check_log($req, $res, $status_code) {
        $pinlog = new TelenorOtpCheckLog;
        $pinlog->request_msg = $req;
        $pinlog->response_msg = $res;
        $pinlog->response_status_code = $status_code;
        $pinlog->save();
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

    public function index(Request $request) {
        return view("telenor.create");
    }

    public function pricepoint() {
        Session::put('telenor_he', 'telenor');
        return view('telenor.index');
    }

    public function postTelenor(MsisdnRequest $request) {
        $mdn = $request->mobile_number;
        $msisdn = $request->country_code . $request->mobile_number;
        $prefix = "95";
        if ($mdn[0] == 0) {
            $msisdn = ltrim($mdn, '0');
            $msisdn = $prefix . $msisdn;
        }

        $user = UserModel::where('plain_msisdn', $msisdn)->first();
        if ($user) {
            $is_exist = SubscriberModel::where('user_id',$user->id)->first();
            if ($is_exist) {
                if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
                    return view('messages.already_exist');
                }   
            }
        }

        $oprhelper = new OperatorHelper;
        $country_id = $oprhelper->country("+".$msisdn); //country creation.
        $operator_id = $oprhelper->operator("+".$msisdn, $country_id); //operator creation.
        $operators = OperatorModel::where('id', $operator_id)->first();

        if ($operators->name == "Telenor") {
            $smsHelper = new SmsHelper;
            $result = $smsHelper->otpsms($msisdn);           
            $this->pin_creation_log($result['req'], $result['res'], $result['status_code']);
            if ($result['status_code'] == 200) {
                Session::put('msisdn', ltrim($msisdn, "+"));
                return redirect('/telenor-otp');
            } else {
                $arr = json_decode($result['res'], true);
                Session::flash('exception', trans('sms_problem'));
                return redirect('/telenor');
            }
        }
        return redirect("/telenor")->with('exception', trans('app.number_placeholder'));
    }

    public function otpTelenor() {
        $msisdn = Session::get('msisdn');
        if (!$msisdn) {
            return redirect("/telenor")->with('exception', trans('app.number_placeholder'));
        }
        return view('telenor.otp');
    }

    public function otpResend() {
        $msisdn = Session::get('msisdn');
        $smsHelper = new SmsHelper;
        $result = $smsHelper->otpsms($msisdn);
        Session::flash('message', trans('app.pin_re_success'));
        return $result;
    }

    public function postOtpTelenor(OtpRequest $request) {
        $plain_msisdn = Session::get('msisdn');
        $pin = $request->pin;
        $smsHelper = new SmsHelper;
        $result = $smsHelper->confirm_otp($plain_msisdn, $pin);

        $this->otp_check_log($result['req'], $result['res'], $result['status_code']);

        if ($result['status_code'] == 201) {
            $resArr = json_decode($result['res'], true);
            $endUserId = $resArr['acr'];
            $msisdn = mb_substr($resArr['acr'], 0, 15);
            Session::put('msisdn', $msisdn);
            Session::put('endUserId', $endUserId);
            $url = telenorConsentPageRequest($endUserId);
            return redirect($url);
        } else {
            return redirect("/telenor")->with('exception', "Unable to make subscription please try again. Please try agin!");
        }
    }

    public function subscription(Request $request) {
        $smsHelper = new SmsHelper;
        $data = $request->all();
        $consentId = $data['consentId'];
        $msisdn = Session::get('msisdn');
        $endUserId = Session::get('endUserId');

        $row = UserModel::where('plain_msisdn', $msisdn)->first();
        if (!$row) {
            $oprhelper = new OperatorHelper;
            $row = new UserModel;
            $row->operator_id = 1;
            $row->plain_msisdn = $msisdn;
            $row->encrypted_msisdn = $oprhelper->getUUID();
            $row->endUserId = $endUserId;
            $row->consent_id = $consentId;
            $row->save();
        }

        $diff = check_dnd_status($msisdn);
        if ($diff) {
            return redirect('/telenor/dnd');
        }

        $userid = $row->id;
        $is_exist = SubscriberModel::where('user_id',$userid)->first();
        if (!$is_exist) {
            $subscribermodel = new SubscriberModel;

            $msg = 'GoGames ကို တစ်နှစ်စာဝန်ဆောင်မှုစနစ်ဖြင့် ရယူပြီးပါပြီ။ အရစ်ကျ အပါတ်စဉ်ကြေး 499 ကျပ် ကျသင့်ပါသည်။ ဝန်ဆောင်မှု ကိုအသုံးပြုရန် ဤနေရာကိုနှိပ်ပါ https://mm.gogames.co. ရပ်ဆိုင်းရန် STOP GGMM ဟု 75457 သို့ SMSပေးပို့ပါ။';

            $refCode = getUUID();
            $payment_res = $smsHelper->payment_with_acr($endUserId, '499', $refCode, $consentId);
            
            // insert into tbl_telenor_payment_log
            $this->payment_log_creation($userid, $refCode, $payment_res['req'], $payment_res['res'], $payment_res['status_code']);

            // insert into tbl_charging_logs
            $this->telenor_charging_log_creation($userid, NULL, $payment_res['req'], $payment_res['res'], $payment_res['status_code'], '499.00');
            
            if ($payment_res['status_code']== 201) {
                $subscribermodel->subscribe($userid);
                $this->subscriber_log_creation($userid);

                //affiliates code( successful conversion)
                $this->affiseService->successfulConversion($userid);
                //$this->hasOfferService->successfulConversion($userid);

                if (getKpValue()) {
                    Kimia::cpa(getKpValue(), $userid);
                    unsetKpValue();
                } elseif (getSocialID()) {
                    $subscribe_info = SubscriberModel::UserId($userid)->first();
                    if (isset($subscribe_info->is_new_user) && $subscribe_info->is_new_user == 1) {
                        SocialCampaignClick::where('id', getSocialID())
                                ->update(['status' => 1, 'user_id' => $userid]);
                        unsetSocialID();
                    }
                }

                $result = $smsHelper->telenor_mt_sms($endUserId, $msg);
                $this->sms_log_creation($userid, $result['req'], $result['res'], $result['status_code']);
                if ($result['status_code'] == 201) {
                    return view('messages.telenor_success');
                }
            }else {
                $response = json_decode($payment_res['res'], TRUE);
                $messageId = $response['requestError']['policyException']['messageId'];
                if ('POL1000' == $messageId) {
                    $msg = 'GoGames တစ်နှစ်စာဝန်ဆောင်မှုမှ အရစ်ကျ အပါတ်စဉ်ကြေး ဖြတ်တောက်ရန်  လက်ကျန်ငွေမလုံလောက်ပါ၊၊ ဝန်ဆောင်မှုကိုဆက်လက်ရယူရန် လက်ကျန်ငွေထပ်မံဖြည့်ပါ၊၊ ဝန်ဆောင်မှု ကို ရပ်ဆိုင်းရန် STOP GGMM ဟု 75457 သို့ SMS ပေးပို့ပါ။';
                    $result = $smsHelper->telenor_mt_sms($endUserId, $msg);
                    $this->sms_log_creation($userid, $result['req'], $result['res'], $result['status_code']);

                    $message = $response['requestError']['policyException']['text'];
                    return view('messages.insufficient');
                } else {
                    return view('messages.failed');
                }
            }
        } else {
            if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
                return view('messages.already_exist');
            }

            $refCode = getUUID();
            $payment_res = $smsHelper->payment_with_acr($endUserId, '499', $refCode, $consentId);


            // insert into tbl_telenor_payment_log
            $this->payment_log_creation($userid, $refCode, $payment_res['req'], $payment_res['res'], $payment_res['status_code']);

            // insert into tbl_charging_logs
            $this->telenor_charging_log_creation($userid, NULL, $payment_res['req'], $payment_res['res'], $payment_res['status_code'], '499.00');


            if ($payment_res['status_code']== 201) {
                UserModel::where('id', '=', $userid)->update([
                    'endUserId' => $endUserId,
                    'consent_id' => $consentId
                ]);
                $subscribermodel = new SubscriberModel;

                SubscriberModel::where('user_id', $userid)
                        ->update([
                            'is_new_user' => 0,
                            'is_subscribed' => 1,
                            'is_active' => 1,
                            'valid_date' => Carbon::now()->AddDays(7)
                ]);


                $this->subscriber_log_creation($userid);
                $this->affiseService->markReturningUser($userid);

                $msg = 'GoGames ကို တစ်နှစ်စာဝန်ဆောင်မှုစနစ်ဖြင့် ရယူပြီးပါပြီ။ အရစ်ကျ အပါတ်စဉ်ကြေး 499 ကျပ် ကျသင့်ပါသည်။ ဝန်ဆောင်မှု ကိုအသုံးပြုရန် ဤနေရာကိုနှိပ်ပါ https://mm.gogames.co. ရပ်ဆိုင်းရန် STOP GGMM ဟု 75457 သို့ SMSပေးပို့ပါ။';
                
                $result = $smsHelper->telenor_mt_sms($endUserId, $msg);
                $this->sms_log_creation($userid, $result['req'], $result['res'], $result['status_code']);
                if ($result['status_code'] == 201) {
                    return view('messages.telenor_success');
                } else {
                    $errmsg = 'Unable to make subscription please try again';
                    $result = $smsHelper->telenor_mt_sms($endUserId, $errmsg);
                    $this->sms_log_creation($userid, $result['req'], $result['res'], $result['status_code']);
                    return redirect('/failed');
                }
            } else {
                $response = json_decode($payment_res['res'], TRUE);
                $messageId = $response['requestError']['policyException']['messageId'];
                if ('POL1000' == $messageId) {
                    $msg = 'GoGames တစ်နှစ်စာဝန်ဆောင်မှုမှ အရစ်ကျ အပါတ်စဉ်ကြေး ဖြတ်တောက်ရန်  လက်ကျန်ငွေမလုံလောက်ပါ၊၊ ဝန်ဆောင်မှုကိုဆက်လက်ရယူရန် လက်ကျန်ငွေထပ်မံဖြည့်ပါ၊၊ ဝန်ဆောင်မှု ကို ရပ်ဆိုင်းရန် STOP GGMM ဟု 75457 သို့ SMS ပေးပို့ပါ။';
                    $result = $smsHelper->telenor_mt_sms($endUserId, $msg);
                    $this->sms_log_creation($userid, $result['req'], $result['res'], $result['status_code']);

                    $message = $response['requestError']['policyException']['text'];
                    return view('messages.insufficient');
                } else {
                    return view('messages.failed');
                }
            }
        }
    }

    public function payment_log_creation($userid, $refCode, $req, $res, $status_code) {
        $log = new TelenorPaymentLogModel;
        $log->user_id = $userid;
        $log->refCode = $refCode;
        $log->request_msg = $req;
        $log->response_msg = $res;
        $log->response_status_code = $status_code;
        $log->save();
    }

    public function telenor_charging_log_creation($userid, $sub_id, $req, $res, $status_code, $amount) {
        $log = new TelenorChargingLogModel;
        $log->user_id = $userid;
        $log->subscription_id = $sub_id;
        $log->request_body = $req;
        $log->response_body = $res;
        $log->is_renewal_request = 0;
        $log->status_code_id = $status_code;
        $log->amount = $amount;
        $log->save();
    }

    public function unsubscribe(Request $request) {
        $data = $request->all();
        \Log::info($data);
        switch ($data['message']) {
            case 'STOP GGMM':
            $acr = $data['userId'];
            $acr_array = explode(":", $acr);
            $msisdn = mb_substr($acr_array[1], 0, 15);
            $row = UserModel::where('plain_msisdn', $msisdn)->first();
            if ($row) {
                if($row->subscriber->is_subscribed == 1 || $row->subscriber->is_active == 1) {
                    $response['success'] = TRUE;
                    $response['message'] = 'You are already stop the service';
                    return $response;
                }
                SubscriberModel::where('user_id', $row->id)
                    ->update([
                        'is_subscribed' => 0,
                        'is_active' => 0
                    ]);
            }
            $channel_id = channel_id('SMS');
            SubscriberLogModel::create(['user_id' => $row->id, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'UNSUBSCRIBE']);
            dnd($msisdn);
            $message = "GoGames တစ်နှစ်စာဝန်ဆောင်မှုကို အောင်မြင်စွာ ရပ်ဆိုင်းပြီးပါပြီ၊၊ ပြန်လည်ရယူရန်  ဤနေရာကိုနှိပ်ပါ https://mm.gogames.co.";
            $helper = new SmsHelper;
            $result = $helper->telenor_mt_sms($acr_array[1], $message);
            $this->sms_log_creation($row->id, $result['req'], $result['res'], $result['status_code']);
            $response = [];
            $response['success'] = TRUE;
            $response['message'] = 'Successfully unsubscribe the service';
            return $response;
            break;
        }
    }

}















