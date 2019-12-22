<?php

namespace App\Http\Middleware\Appland;

use Closure;

class AuthMiddleware
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
        $sign=$request->input('signature');
        $this->method=$request->method();
        $create_sign=$this->verify_signature($request->route('user'),$request->route('subscriptionID'),$request->input('time'));
        
        // if($sign!==$create_sign){
        //     return response()->json([
        //         'message' => 'Invalid signature.',
        //     ],401);
        // } 
        
        // $addTime= $request->input('time')+(5*60);
        // if(!(time()- $request->input('time')<=300)){
        //     return response()->json([
        //         'message' => 'Time Does not match.',
        //     ],401);
        // }
        
        return $next($request);
    }

    private function verify_signature($user,$subscriptionID,$time){
        return $this->getSignature($user,$subscriptionID,$time);
    }
    private function getSignature($user,$subscriptionID,$time)
    {
        $payload = $this->method."\r\n".$subscriptionID."\r\n".$user."\r\n".$time;
        return $this->base64url_encode((hash_hmac('sha256', $payload,config('customauth.secret'), true)));
    }
    private  function base64url_encode($data) 
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
