<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LoginUserModel;
use Session;
use Validator;
use Redirect;

class LoginController extends Controller
{
	public function __construct()
	{
		if (Session::has('is_logged_in')) {
            return redirect()->route('dashboard')->send();  
        }
	}

    public function index() {
    	return view('login');
    }

    public function login(Request $request) {

    	$validator=$this->validation($request->all());
    	if ($validator->fails()) {
    		return Redirect::back()->withInput()->withErrors($validator->messages());
    	}

    	$row = LoginUserModel::where('name', $request->user_name)
    					->where('password', $request->password)->first();

    	if (!$row) {
    		return redirect()->back()->with('login_failed','The credentials you provided cannot be determined to be authentic.');
    	}

    	Session::set('is_logged_in',1);
        Session::set('operator', $row->operator);
    	return redirect()->route('dashboard');
    }

    protected function validation(array $data)
    {
    	return Validator::make($data, [
            'user_name' => 'required',
            'password' => 'required|min:6',
        ]);
    }

}
