<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\DndModel;
use Session;
use App\Helper\SmsHelper;

class UnsubscribeController extends Controller {

    public function index(Request $request) {
        $endUserId = $request->get('endUserId');
        $row = UserModel::EndUserId($request->get('endUserId'))->first();
        $userid = $row->id;
        if ($row) {
            $row = SubscriberModel::UserId($row->id)->first();
            if ($row->is_subscribed == 0) {
                return view('messages.unsub_already');
            }else {
                SubscriberModel::where('user_id', $userid)
                                ->update([
                                    'is_subscribed' => 0,
                                    'is_active' => 0
                                ]);

                $channel_id = channel_id('SMS');
                SubscriberLogModel::create(['user_id' => $userid, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'UNSUBSCRIBE']);
            }
        }

        //Add dnd list for unsubscriber
        
        $msisdn = mb_substr($endUserId, 0, 15);
        dnd($msisdn);

        $unsub_message = "Your subscription to GoGames has been terminated. Thank you for your patronage. To subscribe again, visit http://mm.gogamesapp.com";

        $smshelper = new SmsHelper;
        $result = $smshelper->telenor_mt_sms($endUserId, $unsub_message);
        if ($result['status_code'] == 201) {
            return redirect('/telenor_unsub_success');
        }
    }

}
