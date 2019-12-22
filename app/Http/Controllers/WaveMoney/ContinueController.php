<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\CallbackModelAppland;
use Session;
use App\Helper\CallbackHelper;

class ContinueController extends Controller
{
    public function continue_process()
    {
        $cbthelper = new CallbackHelper;
        return $cbthelper->callbacktoken();
    }
}











