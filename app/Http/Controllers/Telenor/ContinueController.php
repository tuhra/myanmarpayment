<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\CallbackHelper;

class ContinueController extends Controller
{
    public function continue() {
    	$cbthelper = new CallbackHelper;
		return $cbthelper->callbacktoken();
    }

    public function hecontinue() {
    	$cbthelper = new CallbackHelper;
		return $cbthelper->hecallback();
    }

    public function hesuccess() {
    	return view('messages.he_success');
    }
}
