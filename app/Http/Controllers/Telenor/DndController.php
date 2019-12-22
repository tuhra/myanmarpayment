<?php

namespace App\Http\Controllers\Telenor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class DndController extends Controller
{
	public function __construct(){
		if (!Session::has('is_logged_in')) {
			return redirect('/login');  
        }
	}

    public function index() {
    	Session::set('dnd', true);
    	return view('dnd');
    }
}
