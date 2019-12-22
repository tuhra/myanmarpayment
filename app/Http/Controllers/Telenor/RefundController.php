<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use app\Models\TelenorPaymentLogModel;
use App\Helper\SmsHelper;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;

class RefundController extends Controller
{
    public function index() {
        
        return view('telenor.refund');

        $endUserId = '88v37Pi2z5dnsPVGxJ55NjNX9fTHkivn';
        $serverRefCode = '3;1;Subscription08449774';
        $refund = new SmsHelper;
        $result = $refund->refund($serverRefCode, $endUserId);
        return $result;
        // $row = DB::select('select * from tmp_double_20190205');
        // foreach($row as $r) {
        //     $array = json_decode($r->response_body, true);
        //     $endUserId = $array['amountTransaction']['endUserId'];
        //     $serverRefCode = $array['amountTransaction']['serverReferenceCode'];

        // }
    }

    public function refund(Request $request) {
        $row = DB::select('select * from tmp_double_20190205');
        return $row;
        // $msisdn = $request->msisdn;
        // $user = UserModel::where('plain_msisdn', $msisdn)->first();
        // $acrhelper = new SmsHelper;
        // $json = $acrhelper->msisdn2acr($msisdn);
        // $arr = json_decode($json, true);
        // if ($arr) {
        //     $msisdn = $arr['acrPrefix'];
        // }

    }

}
