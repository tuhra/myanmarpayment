<?php

namespace App\Http\Middleware\Appland;

use Closure;
use Request;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use Config;

class SubStatusVerifierMiddleware
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
        $row=UserModel::with('subscriber','operator')->EncMSISDN($request->route('user'))->first();
        if(!isset($row->subscriber)){
           return response()->json([
                'message' => 'User doesn not exist!',
           ],404);  
        }
        $request->attributes->add(['user' => $row]);
        return $next($request);
    }
}

