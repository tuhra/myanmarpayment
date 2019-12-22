<?php

use Illuminate\Http\Request;

Route::group(['namespace'=>'Appland','middleware'=>'ApplandAuth'],function(){
	Route::group(['middleware' => 'subStatusVerifier'],function(){
		Route::get('/subscription/{subscriptionID}/{user}','SubStatusController@index');
	});
	
	Route::group(['middleware' => 'unsubApiVerifier'],function(){
		Route::delete('/subscription/{subscriptionID}/{user}','UnsubscriptionController@index');
	});
        
	Route::get('events/{subscription}/{starttime}/{offset}/{limit}', 'EventController@index');
	
});

Route::get('events/{subscription}/{starttime}/{offset}/{limit}','Appland\EventController@index')->middleware('EventAuth');

Route::get('/revenue', function (Request $request) {
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	return $actual_link;
	// return $request->input();
});


