<?php

namespace App\Http\Controllers\Appland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\ChannelModel;
use App\Models\DndModel;
use App\Models\MoUnsubLog;
use App\Helper\SmsHelper;
use App\Helper\MptHelper;

class UnsubscriptionController extends Controller
{
    public function index($key,$user,Request $request)
    {
        $user=$request->get('user');
        $unsub=SubscriberModel::where('user_id', $user->id)->first();
        $is_new_user = 1;
        if ($unsub->is_new_user==1) {
            $is_new_user = 0;
        }
        SubscriberModel::where('user_id', $user->id)
                        ->update([
                            'is_new_user' => $is_new_user,
                            'is_subscribed' => 0,
                            'is_active' => 0
                        ]);

        $channel_id = channel_id('Apps');
        SubscriberLogModel::create(['user_id' => $user->id, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'UNSUBSCRIBE']);
        if ($user->operator_id == 1) {
            $unsub_message = "GoGames တစ္ႏွစ္စာ၀န္ေဆာင္မွဳကို ေအာင္ၿမင္စြာ ရပ္ဆိုင္းျပီးပါျပီ၊၊ ၿပန္လည္ရယူရန္  ဤေနရာကိုႏွိပ္ပါ https://mm.gogames.co.";
            $endUserId = $user->endUserId;
            $smshelper = new SmsHelper;
            $result = $smshelper->telenor_mt_sms($endUserId, $unsub_message);
            dnd($user->plain_msisdn);
            if ($result['status_code'] == 201) {
                return response()->json([
                    'message' => 'User has been successfully unsubscribed.',
                ],200);
            }
        } else {

            $tranid = getUUID();
            $ma = new MptHelper;
            $res = $ma->maUnsubscribe($user->plain_msisdn, $tranid);
            $xml = $res['res'];
            $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $result = json_decode($json,TRUE);
            \Log::info($result);
            if ($result['error_code'] == '0') {
                return response()->json([
                    'message' => 'User has been successfully unsubscribed.',
                ],200);
            }

            // $log = new MoUnsubLog;
            // $log->user_id = $user->id;
            // $log->tranid = $tranid;
            // $log->reqBody = $res['req'];
            // $log->reqBody = $res['res'];
            // $log->reqBody = $result['error_code'];
            // $log->save();

            // $unsub_message = "GoGames ဝန္ေဆာင္မႈအား သင္၏ ဖုန္းမွ အသုံးျပဳမႈရပ္ဆိုင္းျခင္းေအာင္ျမင္ပါသည္။ ဝန္ေဆာင္မႈအားျပန္လည္ ရယူလိုပါက http://mm.gogamesapp.com/en/app အားႏွိပ္ၿပီး ျပန္လည္အသံုးျပဳႏိုင္ပါသည္။  ဝန္ေဆာင္ခမွာ ၉၉ က်ပ္ တိတိျဖစ္ပါသည္။အေသးစိတ္သိရွိလိုပါက ၁၀၆ သို႔ဆက္သြယ္ေမးျမန္းႏိုင္ပါသည္။";
            // $mpt = new MptHelper;
            // $sms_result = $mpt->sendsms($unsub_message, $user->plain_msisdn);
            // \Log::info($sms_result);
            // $mpt->mpt_sms_log_creation($user->id, $sms_result);
            // return response()->json([
            //     'message' => 'User has been successfully unsubscribed.',
            // ],200);
        }
    }
}


