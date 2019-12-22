<?php

namespace App\Http\Middleware\Language;

use Closure;
use Session;
use App;
use Config;

class LanguageMiddleware
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
        if (Session::has('locale')) {
            $locale=Session::get('locale');
        }else{
            $locale='mm';
            Session::put('locale',$locale);   
        }
        App::setLocale($locale);
        return $next($request);
    }
}
