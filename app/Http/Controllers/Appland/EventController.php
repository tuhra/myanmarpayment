<?php

namespace App\Http\Controllers\Appland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class EventController extends Controller
{    
    public function index(Request $request,$subscription,$starttime,$offset,$limit)
    {
        /**
        * We assume that the we will not hit over 250 events per second. 
        * So appland will move to another request based on the latest timestamp
        * (Notes) we might need to change the logic if we overcome 250 events per second
        */
        $data['events'] = array();
        /* if($offset > 0) {
            return response()->json($data, 200);
        } */
        $time=date('Y-m-d H:i:s',$starttime);
        $sub_logs=DB::table('tbl_subscribers_logs')
            ->join('tbl_users', 'tbl_users.id', '=', 'tbl_subscribers_logs.user_id')
            ->select('tbl_subscribers_logs.*','tbl_users.encrypted_msisdn')
            ->where('tbl_subscribers_logs.created_at', '>=', $time)
            ->offset($offset)
            ->limit($limit)
            ->get();
       
        $sub_logs = $sub_logs->toArray();
     
        $charge_logs=DB::table('tbl_charging_logs')
            ->join('tbl_users', 'tbl_users.id', '=', 'tbl_charging_logs.user_id')
            ->select('tbl_charging_logs.*','tbl_users.encrypted_msisdn')
            ->where('tbl_charging_logs.created_at', '>=', $time)
            ->offset($offset)
            ->limit($limit)
            ->get();

        $charge_logs = $charge_logs->toArray();
    
        $events_merge=array_merge($sub_logs,$charge_logs);
       
        usort($events_merge, [$this,'sortingByDate']);
        $events_merge = array_slice($events_merge, 0, $limit);

        foreach ($events_merge as $key => $event) 
        {
            $event_type = isset($event->attempt_type)?$this->sub_event($event->attempt_type):$this->charging_event($event->status_code_id);
            $tmp = array(
                    "isEligible" =>$this->eligibility($event), 
                    "event"      => $event_type,
                    "user"       => $event->encrypted_msisdn, //user​: User ID of the user in question not msisdn
                    "timestamp"  =>strtotime($event->created_at)
            );

        // add currency and amount
        if($this->isChargingEvent($event_type)) {
                $tmp["currency"] = "MMK";
                $tmp["amount"] = floatval($event->amount);
                $tmp["nextRenewal"] = $this->nextRenewal($event_type, $event->created_at);
            }
            // check the bill next renewal
          
            $data['events'][] = $tmp; 
        }

        return response()->json($data,200);
    }
    /**
     * Next Renewal
     */
    private function nextRenewal($type, $created_at) {
        $nextRenewal = '';
        if($type == 'BILLED_SUCCESS') {
            // Next Day Renewal
            $nextRenewal = date('Y-m-d 00:00:20', strtotime("+1 day", strtotime($created_at)));
        } else if($type == 'BILLED_FAILURE') {
            // Check the another date
            $event_date = date('Y-m-d', strtotime($created_at));
            $retryTime = array("08:00:00", "14:00:00", "18:00:00");
            for($i = 0; $i < count($retryTime); $i++) {
                if(strtotime($created_at) < strtotime($event_date." ".$retryTime[$i])) {
                    $nextRenewal = $event_date." ".$retryTime[$i];
                    break;
                }
            }
            $nextRenewal = $nextRenewal == '' ? date('Y-m-d 00:00:20', strtotime("+1 day", strtotime($created_at))) : $nextRenewal;
        }
        return $nextRenewal != '' ? strtotime($nextRenewal) : '';
    }
    
    /**
    * Sorting by time
    */
    private function sortingByDate($a,$b){
        $ad = strtotime($a->created_at);
        $bd = strtotime($b->created_at);
        return ($ad-$bd);
    }

    private function isChargingEvent($type) {
        if(in_array($type, array('BILLED_SUCCESS', 'BILLED_FAILURE'))) return true;
        else return false;
    }
    /**
    * 
    */
    private function charging_event($status) {
        return $status==2?'BILLED_SUCCESS':'BILLED_FAILURE';
    }

    private function sub_event($type) {
        return (($type == 1) ? "SUBSCRIBE" :(($type == 2) ? "SUBSCRIPTION_END" :"CANCEL"));
    }

    private function eligibility($event)
    {
        if (isset($event->status_code_id)) {
            $response=$event->status_code_id==2?true:false;
        }else{
            $response=$event->attempt_type==1 && $event->attempt_type_status==1?true:false;
        }
        return $response;
    }
}
