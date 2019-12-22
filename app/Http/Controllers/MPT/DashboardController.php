<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use DB;
use App\Helper\SmsHelper;
use App\Models\DndModel;
use App\Models\WavePromotion;

class DashboardController extends Controller
{
	public function __construct(){
		if (!Session::has('is_logged_in')) {
			return redirect('/login');  
        }
	}

    public function index() {
        if (3 == Session::get('operator')) {
            return redirect('/wave/dashboard');
        }
        Session::forget('dnd');
    	return view('dashboard');
    }

    public function logout() {
        Session::forget('is_logged_in');
        Session::forget('operator');
    	Session::forget('dnd');
    	return redirect('/login');
    }

    public function searchMSISDN(Request $request) {
    	$message = array('status' => false, 'message' => 'Enter Your MSISDN');
    	$msisdn = $request->get('msisdn');
    	if($msisdn == null) {
    	    $message['message'] = 'Please Enter Your MSISDN';
    	    $message['status'] = false;
    	    return view('dashboard',compact('user','message'));
    	}
        if (Session::has('dnd') && Session::get('operator') == 1) {
            $acrhelper = new SmsHelper;
            $json = $acrhelper->msisdn2acr($msisdn);
            $arr = json_decode($json, true);
            $msisdn = $arr['acrPrefix'];
            if (empty($msisdn)) {
                $message['message'] = 'Msisdn not found';
                $message['status'] = false;
                return view('dashboard',compact('message'));
            }
        }

    	// $user = UserModel::with('subscriber')->where('plain_msisdn', $msisdn)->first();
        if (Session::get('operator') == 1) {
            Session::put('msisdn', $msisdn);
            $user = UserModel::with('subscriber')->where('tbl_users.plain_msisdn', $msisdn)
                                ->where('operator_id', 1)->first();
        } else {
            $user = UserModel::with('subscriber')->where('tbl_users.plain_msisdn', $request->get('msisdn'))
                                ->where('operator_id', '<>', 1)->first();
        }

    	if ($user == null) {
    	    $message['message'] = 'Your MSISDN Is Invalid';
    	}  else {
    	    $message = null;
    	    $sublogs = DB::table('tbl_subscribers_logs')
    	        ->join('tbl_channel_name', 'tbl_channel_name.id', '=', 'tbl_subscribers_logs.channel_id')
    	        ->where('tbl_subscribers_logs.user_id', '=', $user->id)
    	        ->select('tbl_channel_name.name','tbl_subscribers_logs.*')
    	        ->get();
    	}
        
    	return view('dashboard',compact('user', 'message', 'sublogs'));
    }

    public function deActivate(Request $request) {
    	$message = array('message' => '' , 'status' => false);
    	$input = $request->all();
    	$user_id = $input['user_id'];
        $response = [];
        
        if (Session::has('dnd')) {
            $msisdn = Session::get('msisdn');
            SubscriberModel::where('user_id', $user_id)->update(['is_active' => 0, 'is_subscribed' => 0, 'is_new_user' => 0]);
            $channel_id = channel_id('DND');
            SubscriberLogModel::create(['user_id' => $user_id, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'UNSUBSCRIBE']);
            $row = DndModel::where('msisdn', $msisdn)->first();

            if(!$row) {
                DndModel::create(['msisdn' => $msisdn, 'dnd_status' => 'telenor']);
            }
            $response['status'] = "success";
            return $response;
        }
    	
        $subscribe = SubscriberModel::where('user_id', $user_id)->first();
    	if($subscribe->is_subscribed == 1) {
    	    SubscriberModel::where('user_id', $user_id)->update(['is_active' => 0, 'is_subscribed' => 0, 'is_new_user' => 0]);
            $channel_id = channel_id('DND');
    	    SubscriberLogModel::create(['user_id' => $user_id, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'UNSUBSCRIBE']);
    	    $response['status'] = "success";
    	    return $response;
    	} else {
    	    $response['status'] = "error";
    	    return $response;
    	}	
    }

    public function deActivated()
    {
        $message['message'] = 'Successfully deactivated the user';
        $message['status'] = true;
        $sublogs = [];
        return view('dashboard',compact('message', 'sublogs'));
    }
}
