<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WaveController extends Controller
{
    public function callback(Request $request) {
    	return "wave money callback";
    }
}
