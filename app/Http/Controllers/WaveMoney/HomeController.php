<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\WavePaymentHelper;
use Wave;
use Session;
use App\Http\Requests\OperatorRequest;
use App\Models\Callback;

class HomeController extends Controller
{
    public function index(Request $request)
    {
    	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        \Log::info($actual_link);
    	// return $actual_link;
        \Log::info('Home Controller,line:21, Operator choose Page, Request:'. json_encode($request->all())); //TODO need to remove
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

        return view('home');

        // return view('HE');
        // return $this->showoperator();

    }

    public function he() {
        return view('HE');
        
        // return view('home');
        /*
            For Wave Only
            return redirect('/wavesubscribe');
        */
    }


    public function operator(OperatorRequest $request)
    {
        if($request->operatorCheck == 'telenor'){
            return redirect('/telenor');
        }else{
            return redirect('/wavesubscribe');
        }
       
    }

    public function operatorGet()
    {
    	return redirect('/');
    }

    private function rc4($key, $str) {
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }


}



