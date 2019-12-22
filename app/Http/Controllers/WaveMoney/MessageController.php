<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTypeModel;
use Session;

class MessageController extends Controller
{
    public function success(Request $request)
    {
        $id = Session::get('sub_type_id');
    	$subscriber = SubscriptionTypeModel::find($id);
        return view('messages.success', compact('subscriber'));
    }

    public function failed()
    {
        return view('messages.failed');
    }

    public function subscription_failed() 
    {
        $message = Session::get('message');
        return view('messages.subscription_failed', compact('message'));
    }

    public function operator_notprovided()
    {
        return view('messages.operator_notprovided');
    }
}
