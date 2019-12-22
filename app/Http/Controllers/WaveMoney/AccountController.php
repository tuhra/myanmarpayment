<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Session;

class AccountController extends Controller
{
    public function index(Request $request) {

        // $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // return $actual_link;
        
        $user_type = null;
        $msisdn = getEncMissdn();
    	$user = UserModel::with('subscriber')->EncMSISDN($msisdn)->first();
        \Log::info($user);
        $date=date_create($user->subscriber->valid_date);
        $renewal_date = date_format($date,"d-m-Y");
        $data = ['renewal_date' => $renewal_date];
        switch ($user->operator_id) {
            case '1':
                return view('profile.telenorprofile', compact('data', 'user'));                
                break;
            
            case '2':
                return view('profile.mptprofile', compact('data', 'user'));
                break;

            case '3':
                $sub_type_id = $user->subscriber->subscription_type_id;
                $subtype = getSubType($sub_type_id);
                $renewal = date('Y-m-d',strtotime($user->subscriber->valid_date.'+1days'));
                $data = ['renewal_date' => $renewal, 'subtype' => $subtype];
                return view('profile.waveprofile', compact('data', 'user'));
                break;
                
            case '4':
                $data = ['renewal_date' => $user->subscriber->valid_date];
                return view('profile.mptprofile', compact('data', 'user'));
                break;
        }
    }

    public function upgrade() {
    	$user = UserModel::with('subscriber')->MSISDN(getMsisdn())->first();
        return $user;
    }

    public function error() {
        return view('profile.error');
    }

}
