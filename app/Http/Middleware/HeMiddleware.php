<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\Rc4Helper;

class HeMiddleware
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
        $header = $request->headers->all();
        // \Log::info($header);

        if (array_key_exists("X-MSISDN", $header)) {
            $encrypted = $header['X-MSISDN'];

            $rc4 = new Rc4Helper;

            $rc4->setKey(config('mpt.rc4password'));
            $msisdn = $rc4->decrypt($encrypted);

            setMsisdn($msisdn);
            $country_id = country($number);
            $operator_id = operator($number,$country_id);
            $operator_name = getOperator($operator_id);
            setOptId($operator_id);

            switch ($operator_name) {
                case 'telenor':
                    return redirect('/telenor');
                    break;
                
                default:
                    return redirect('/mpt/charge');
                    break;
            }

            dd('header enrichment');
        }

        return $next($request);
    }
}
