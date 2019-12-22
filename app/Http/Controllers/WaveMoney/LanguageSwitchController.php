<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Session;

class LanguageSwitchController extends Controller
{
    public function languageSwitcher(Request $request)
    {
        if ($request->ajax()) {
           	Session::put('locale',$request->locale);
        }
        return response()->json(['success' => true]);
    }
}
