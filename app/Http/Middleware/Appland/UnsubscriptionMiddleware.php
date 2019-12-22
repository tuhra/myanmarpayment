<?php

namespace App\Http\Middleware\Appland;

use Closure;
use Request;
use App\Models\UserModel;
use App\Models\SubscriberModel;
class UnSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $row=UserModel::with('subscriber','operator')->EncMSISDN($request->route('user'))->first();
        $row=UserModel::with('subscriber')->where('encrypted_msisdn', $request->route('user'))->first();
        if (!isset($row->subscriber)) {
           return response()->json([
            'message' => 'User does not exist!',
           ],404);    
        }else{
            if ($row->subscriber->is_subscribed==0 && $row->subscriber->is_active ==0) {
                return response()->json([
                'message' => 'User is already unsubscribed',
               ],202);
            }
        }
        $request->attributes->add(['user' => $row]);
        return $next($request);
        
    }
}
