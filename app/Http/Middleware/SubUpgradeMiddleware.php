<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\UserModel;

class SubUpgradeMiddleware
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
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($actual_link);
        $token = $request->get('token');
        if($token) {
            $token_decode = json_decode(base64_decode($token),true);
            \Log::info($token_decode['user']);
            $row = UserModel::where('encrypted_msisdn', $token_decode['user'])->first();
            if(!$row){
                Session::put('error_message', "User does not exist!");
                return redirect('error'); 
            }
            setMsisdn($row->plain_msisdn);
            setEncMsisdn($row->encrypted_msisdn);
        } else {
            Session::put('error_message', "User does not exist!");
            return redirect('error'); 
        }

        return $next($request);
    }
}
