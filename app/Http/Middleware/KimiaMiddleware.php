<?php

namespace App\Http\Middleware;

use Closure;

class KimiaMiddleware
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

        if($request->has('offerid') && $request->has('trans') && $request->has('affiliate_id') && $request->has('aff_click_id') && $request->has('aff_sub3'))
        {
            return $next($request);
        }

        if ($request->has('kp')) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Parameter is missing.',
        ],400);
    }
}
