<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use DB;

class UnSubListController extends Controller
{
    public function unsublist() {
    	$lists = DB::table('tbl_users')
    		->join('tbl_subscribers', 'tbl_subscribers.user_id', 'tbl_users.id')
    		->join('tbl_subscribers_logs', 'tbl_subscribers_logs.user_id', 'tbl_users.id')
    		->where('tbl_users.operator_id', 1)
    		->where('tbl_subscribers_logs.channel_id', 6)
    		->select('tbl_users.plain_msisdn', 'tbl_subscribers_logs.created_at')
    		->paginate(25);
    	return view('unsublist', compact('lists'));
    }
}
