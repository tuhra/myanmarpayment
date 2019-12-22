<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;

class UnsubscribeAPIController extends Controller
{
    public function unsubscribe(Request $request)
    {
        try {
            $enc_msisdn = $request->all()['encrypted_msisdn'];
            $user = UserModel::where('encrypted_msisdn', $enc_msisdn)->first();
            if(!$user) {
                return response(array(
                        'success' => false,
                        'status' => 400,
                        'message' => "Subscriber not found!",
                    ),400);
            } else {
                $id = $user->id;
                $subscriber = UserModel::find($id)->subscriber()->first();
                $user_id = $subscriber->user_id;
                if(!$subscriber) {
                    return response(array(
                            'success' => false,
                            'status' => 200,
                            'message' => "Subscriber not found!",
                        ),400);
                }

                if ($subscriber['is_subscribed'] == 1 && $subscriber['is_active'] == 1) {
                    $row = SubscriberModel::find($subscriber['id']);
                    $row->is_subscribed = 0;
                    $row->is_active = 0;
                    $row->save();
                    $channel_id = getChannelId('UnSub');
                    $updatewhere = SubscriberLogModel::where('user_id', $subscriber->user_id)->pluck('user_id');
                    $update = ['attempt_type' => 3, 'channel_id' => $channel_id, 'attempt_type_status' =>0, 'event' => 'UNSUBSCRIBED'];
                    SubscriberLogModel::wherein('user_id', $updatewhere)->update($update);

                    return response(array(
                            'success' => true,
                            'status' => 200,
                            'message' => "You have been successfully unsubscribed from GoGames service. To re-subscribe at anytime, please visit http://mm.gogamesapp.com",
                        ),200);

                } else {
                    return response(array(
                            'success' => true,
                            'status' => 200,
                            'message' => $enc_msisdn . " is not subscribe",
                        ),200);
                }
            }
        }
        catch (\Exception $e) {
            // return $e->getMessage();
            return response(array(
                    'success' => false,
                    'status' => 403,
                    'message' => "Wrong Input",
                ),200);
        }

    }
}
