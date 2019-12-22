<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserModel;
use Session;

class TelenorUnsubscribeMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $row = UserModel::EndUserId($request->get('endUserId'))->first();  
        if (empty($row)) {
            Session::flash('exception', 'Unsubscribe Failed!');
            // return redirect('/telenor');
            return redirect('http://wave.gogamesapp.com/telenor');
        }

        return $next($request);
    }

}
