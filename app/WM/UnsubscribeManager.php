<?php
namespace App\WM;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
/**
* Unsubscription manager 
*/
class UnsubscribeManager
{
   public function unsubscribe($user,$channel)
   {
        /*-------Unsubscribe From the System----------*/
        $row=SubscriberModel::UserId($user->id)->Subscribe(1)->first();
   
        if ($row->is_new_user!=0) {
            $row->is_new_user=0;
        }
      
        $row->is_subscribed=0;
        $row->is_active=0;
        $row->save();
       
        /*-----------subscriber logs save-------------*/
        $log=new SubscriberLogModel;
        $log->user_id=$user->id;
        $log->subscription_id=$row->subscription_id;
        $log->attempt_type=0;
        $log->channel_id=getChannelId($channel);//getChannelId helper function
        $log->attempt_type_status=1;
        $log->event='UNSUBSCRIBED';
        $log->save();
        return $row;
   }
 
}


?>