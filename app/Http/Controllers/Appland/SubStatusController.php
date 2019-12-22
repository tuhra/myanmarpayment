<?php

namespace App\Http\Controllers\Appland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubStatusController extends Controller
{
    public function index(Request $request)
    {
      	$user=$request->get('user');
        if (empty($user->endUserId)) {
            $current_date = date("Y-m-d h:i:s");
                return response()->json([
                    'user' => $user->encrypted_msisdn,
                    'nextRenewal' => strtotime($user->subscriber->valid_date),
                    'isEligible'  =>($user->subscriber->valid_date >= $current_date && $user->subscriber->is_subscribed == 1 && $user->subscriber->is_active == 1)?true:false,
                ],200);                
        }
        
        $mid_night=strtotime(date('Y-m-d 00.00.01',strtotime('+1days')));
        $twelvepm=strtotime(date('Y-m-d 12.00.00'));
        $fivepm=strtotime(date('Y-m-d 17.00.00'));
        $ninepm=strtotime(date('Y-m-d 21.00.00'));
        $is_free=$this->is_free($user->subscriber->is_new_user,$user->subscriber->created_at);
        if($is_free){
            $nextRe=$is_free;
        }elseif(time()<$twelvepm){
            $nextRe=$twelvepm;
        }else if(time()<$fivepm){
            $nextRe=$fivepm;
        }elseif (time()<$ninepm) {
            $nextRe=$ninepm;
        }else{
            $nextRe=$mid_night;
        }
        return response()->json([
                'user' => $user->encrypted_msisdn,
                'nextRenewal' =>($user->subscriber->is_subscribed==1)?$nextRe:null,
                'isEligible'  =>($user->subscriber->is_active==1)?true:false,
            ],200); 

    }

    private function is_free($is_free,$created_at)
    {
        if($is_free==1){
            $freeDate=strtotime(date('Y-m-d',strtotime($created_at.'+7days')));
            $currentDate=strtotime(date('Y-m-d'));
            return $currentDate<$freeDate?$freeDate:false;
        }
        return false;
    }
}







