<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\Rc4Helper;
use App\Helper\EncDecHelper;
use Session;

class MptMiddleware
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

        //keep reqReferer(Where the request coming from) in session to determine where we should redirect after subscription(Continue button event). The value needs to be string.
       $this->handleReqReferer();

        if ($request->header('x-msisdn')) {
            $rc4 = new Rc4Helper;
            $key = 'bNf&CZ=MvqBssZ9y';
            $msisdn = "";
            $encrypted_msisdn = $request->header('x-msisdn');
            $hash = md5($key,true);
            $msisdn = $rc4->rc4($hash, base64_decode($encrypted_msisdn));
            \Log::info("decrypted msisdn => " . $msisdn);
            Session::put('he_msisdn', $msisdn);
            
            if ($request->exists('callback')) {
                Session::put('callback', $request->get('callback'));
                $callback = Session::get('callback');
                $findme = 'ott';
                $pos = strpos($callback, $findme);
                if ($pos !== false) {
                    $callbackArr = explode("ott=", $callback);
                    Session::put('ott', end($callbackArr));
                    return redirect('/mpt/ma/valueposition');
                }
                return redirect('/mpt/ma/webvalueposition');
            }
        }

        return $next($request);
    }

    /**
     *
     * Identify req Referer and set it to session, Filter by accountkit request.
     * @return bool
     */
    private function handleReqReferer(){
        $reqReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        // if (!in_array($reqReferer, config('c2p.unimportant_domains'))) {
        //     Session::put('reqReferer', $reqReferer);
        // }
    }
}




