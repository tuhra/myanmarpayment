<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\SmsHelper;
use Request;
use Wikimedia\IPSet;
use Session;

class TelenorHEMiddleware
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
        
        if ($request->exists('callback')) {
            Session::put('callback', $request->get('callback'));
            $callback = Session::get('callback');
            $findme = 'ott';
            $pos = strpos($callback, $findme);
            if ($pos !== false) {
                $callbackArr = explode("ott=", $callback);
                Session::put('ott', end($callbackArr));
            }
        }

        $range = config('ip.range');
        
        $ipset = new IPSet($range);

        $ip = Request::ip();

        if ($ipset->match($ip)) {
            Session::put('HE', 'TURE');
            return redirect('/pricepoint');

        }

        return $next($request);

    }
}
