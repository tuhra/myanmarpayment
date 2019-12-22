<?php

namespace App\Http\Middleware\Appland;

use Closure;

class EventAuthMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $method,
            $subscription,
            $starttime,
            $offset,
            $limit,
            $timestamp;

    public function handle($request, Closure $next) {
        
        $sign = $request->input('signature');
        $this->method = $request->method();
        $this->subscription = $request->route('subscription');
        $this->starttime = $request->route('starttime');
        $this->offset = $request->route('offset');
        $this->limit = $request->route('limit');
        $this->timestamp = $request->get('time');
        $create_sign = $this->create_sign();
         if($sign!==$create_sign){
          return response()->json([
          'message' => 'Invalid signature.',
          ],401);
          }
          $addTime= $this->timestamp+(5*60);
          if(!(time()- $this->timestamp<=300)){
          return response()->json([
          'message' => 'Time Does not match.',
          ],401);
          }

        return $next($request);
    }

    private function create_sign() {
        $payload = $this->method . "\r\n" . $this->subscription . "\r\n" . $this->starttime . "\r\n" . $this->offset . "\r\n" . $this->limit . "\r\n" . $this->timestamp;
        return $this->base64url_encode((hash_hmac('sha256', $payload, config("customauth.secret"), TRUE)));
    }

    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

}
