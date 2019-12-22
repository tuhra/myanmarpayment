<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CallbackMiddleware
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
        $callback = $request->get('callback');
        $request->session()->put(['callback' => $request->get('callback')]);
        return $next($request);
    }
}
