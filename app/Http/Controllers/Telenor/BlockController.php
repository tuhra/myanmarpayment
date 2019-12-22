<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\SmsHelper;
use App\Models\DndModel;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use Alert;
use Session;

class BlockController extends Controller
{
	public function __construct(){
		if (!Session::has('is_logged_in')) {
			return redirect('/login');  
        }
	}
		
    public function index() {
    	return view('telenor.block', compact('message'));
    }

    public function block(Request $request) {
    	$msisdn = $request->all()['msisdn'];
    	$acrhelper = new SmsHelper;
    	$json = $acrhelper->msisdn2acr($msisdn);
    	$arr = json_decode($json, true);
    	$msisdn = $arr['acrPrefix'];
    	if (empty($msisdn)) {
            Session::put('message', "Msisdn not found");
    	    return redirect('/block');
    	}

    	$row = DndModel::where('msisdn', $msisdn)->first();
    	if (!$row) {
            $user = UserModel::where('plain_msisdn', $msisdn)->first();
            $channel_id = channel_id('DND');
            SubscriberLogModel::create(['user_id' => $user->id, 'attempt_type' => 1, 'channel_id' => $channel_id,'attempt_type_status' => 1, 'event' => 'BLOCK']);
    		$dnd = new DndModel;
    		$dnd->msisdn = $msisdn;
    		$dnd->dnd_status = 'Telenor';
    		$dnd->save();
            Session::put('message', "Successfully blocked");
            return redirect('/block');
    	}

        Session::put('message', "Already Blocked");
        return redirect('/block');

    }
}
