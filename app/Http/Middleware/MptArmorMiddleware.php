<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\KimiaCpcModel;

class MptArmorMiddleware
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
        if ($request->kp) {
           //  return response()->json([
           //  'message' => 'Kp Value Parameter is missing.',
           // ],400);

            $kp_value = $request->get('kp');
            $row=new KimiaCpcModel;
            $row->kp_value = $kp_value;
            $row->save();
            // Save the kp value to the sessions
            setKpValue($kp_value); 
        }
        
        return $next($request);
    }
}
