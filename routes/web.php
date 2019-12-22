<?php

// Main Route
Route::group(['middleware' => ['MptHe','IpRange','SocialCampaign']], function () {
	Route::get('/', 'WaveMoney\HomeController@index');
});

// Armor ADS Route
Route::get('/mmadv', 'Advertise\AdvertiseController@handleAdsRequest');

// Wave Money Route Start
Route::get('/showoperator', 'WaveMoney\HomeController@showoperator');
Route::get('/operator', 'WaveMoney\HomeController@operatorGet');
Route::post('/operator', 'WaveMoney\HomeController@operator');
Route::get('/wavesubscribe', 'WaveMoney\WaveSubscribeController@index');
Route::get('/subscribe/{typeid}', 'WaveMoney\WaveSubscribeController@create');
Route::post('wave', 'WaveMoney\WaveSubscribeController@store');
Route::get('/expirewave', 'WaveMoney\WaveSubscribeController@expire');
Route::post('/wave/callback', 'WaveMoney\WaveSubscribeController@callback');
Route::get('/T&C', 'WaveMoney\WaveSubscribeController@tandc');
Route::post('/language',['as' => 'wm.web.language', 'uses' =>'WaveMoney\LanguageSwitchController@languageSwitcher']);
Route::get('/continue',[ 'as' => 'wm.web.continue', 'uses' => 'WaveMoney\ContinueController@continue_process']);
Route::get('/other_success/{sub_type_id}', 'WaveMoney\getChannelId@other_success');
Route::get('/failed', 'WaveMoney\MessageController@failed');
Route::get('/message', 'WaveMoney\MessageController@operator_notprovided');
Route::get('/success', 'WaveMoney\MessageController@success');
Route::get('/subscription_failed', 'WaveMoney\MessageController@subscription_failed');
Route::get('/wave_exist', function () {
	return view('messages.wave_exist');
});
Route::group(['middleware' => 'SubUpgrade'],function(){
	Route::get('myaccount',['uses' => 'WaveMoney\AccountController@index']);
});
Route::post('upgrade','WaveMoney\AccountController@upgrade');
Route::get('error','WaveMoney\AccountController@error');

// Wave Money Route End

// Telenor Digital Route Start
Route::get('/tandc', 'Telenor\TelenorController@tandc');
Route::get('/telenor','Telenor\TelenorController@index');
Route::post('/telenor','Telenor\TelenorController@postTelenor');
Route::get('/telenor-otp','Telenor\TelenorController@otpTelenor');
Route::post('/telenor-otp','Telenor\TelenorController@postOtpTelenor');
Route::get('/telenor-otp-resent','Telenor\TelenorController@otpResend');
Route::get('/telenor-unsubscribe', 'Telenor\UnsubscribeController@index')->middleware('TelenorUnsubscribeMiddleware');
Route::post('/telenor/callback', 'Telenor\HeController@callback');
Route::post('/telenor/hesubscribe', 'Telenor\HeController@hesubscribe');
Route::get('/telenor/continue', 'Telenor\ContinueController@continue');
Route::get('/telenor/hesuccess', 'Telenor\ContinueController@hesuccess');
Route::get('/telenor/telenor_exist', 'Telenor\HeController@telenor_exist');
Route::get('/telenor/subscription', 'Telenor\TelenorController@subscription');
Route::get('/valueposition', 'Telenor\TelenorController@valueposition');
Route::get('/pricepoint', 'Telenor\TelenorController@pricepoint');
Route::get('/hecharge', 'WaveMoney\HomeController@he');
Route::get('/insufficient', 'Telenor\TelenorController@insufficient');

Route::get('test', function () {
	return view('messages.insufficient');
	$response = '{
		"requestError": {
			"policyException": {
			"text": "A policy error occurred. Error code is %1",
			"messageId": "POL1000",
			"variables": ["Invalid PIN"]
			}
		}
	}';
	$result_array = json_decode($response, TRUE);
	$messageId = $result_array['requestError']['policyException']['messageId'];
	if ('POL1000' == $messageId) {
		return $result_array['requestError']['policyException']['text'];
	}
});

Route::get('/telenor/success', function () {
	return view('messages.telenor_success');
});
Route::get('/telenor/dnd', function () {
	return view('messages.telenor_dnd');
});
Route::get('/telenor/hecontinue', ['as' => 'he.web.continue', 'uses' => 'Telenor\ContinueController@hecontinue']);
Route::get('/telenor_unsub_success', function () {
	return view('messages.telenor_unsub_success');
});
Route::get('/telenor/refund', 'Telenor\RefundController@refund');
Route::post('/telenor/mo/unsubscribe', 'Telenor\TelenorController@stopSMS');
Route::get('/telenorconsent', 'Telenor\TelenorController@telenorconsent');
// Telenor Digital Route End

// MPT SDP Route Start
Route::get('/mpt', 'MPT\MptController@index');
Route::get('/operator/fbkit/verify', 'Fbkit\FacebookController@verify');
Route::get('/mpt/charge', 'MPT\MptController@charge');
Route::get('/mpt/valueposition', function () {
	return view('mpt.valueposition');
});
Route::post('mpt/sendotp', 'MPT\MptController@sendotp');
Route::get('mpt/otp', 'MPT\MptController@otp');
Route::post('mpt/otp', 'MPT\MptController@postOTP');
Route::get('mpt/resent', 'MPT\MptController@resent');
Route::get('/mpt/success', 'MPT\MptController@success');
Route::get('/mpt/mosuccess', 'MPT\MptController@mosuccess');
Route::get('/mpt/mpt_exist', 'MPT\MptController@mpt_exist');
Route::get('/mpt/failed', 'MPT\MptController@failed');
Route::post('/mpt/mo/callback', 'MPT\MoController@index');
Route::post('/mpt/mt/notify', 'MPT\MtController@index');
Route::get('/login', 'MPT\LoginController@index');
Route::post('/login', 'MPT\LoginController@login');
Route::get('/dashboard',[ 'as' => 'dashboard', 'uses' => 'MPT\DashboardController@index']);
Route::get('/logout',[ 'as' => 'logout', 'uses' => 'MPT\DashboardController@logout']);
Route::get('/search',[ 'as' => 'search', 'uses' => 'MPT\DashboardController@searchMSISDN']);
Route::get('/deActivate',[ 'as' => 'deActivated', 'uses' => 'MPT\DashboardController@deActivate']);
Route::get('/dnd', [ 'as' => 'dnd', 'uses' => 'Telenor\DndController@index']);
Route::get('/refund', [ 'as' => 'refund', 'uses' => 'Telenor\RefundController@index']);
Route::post('/refund', [ 'as' => 'refund', 'uses' => 'Telenor\RefundController@refund']);
Route::get('/unsublist', [ 'as' => 'unsublist', 'uses' => 'Telenor\UnSubListController@unsublist']);
Route::get('/block', ['as' => 'block', 'uses' => 'Telenor\BlockController@index']);
Route::post('/block', ['as' => 'block', 'uses' => 'Telenor\BlockController@block']);
Route::get('/check', 'MPT\MptController@check');
Route::get('/mpt-unsubscribe', 'MPT\UnsubscribeController@index');
Route::get('/loading', function() {
	return view('wave.loading');
});
Route::get('/wave/checkStatus', 'WaveMoney\WaveSubscribeController@checkStatus');
Route::get('/wave/dashboard', 'WaveMoney\WaveSubscribeController@dashboard');
Route::post('/wave/promotion', 'WaveMoney\WaveSubscribeController@promotion');
// MPT SDP Route End

// MPT MA route Start
Route::get('/mpt/ma/web', 'MA\MptController@websubscribe');
Route::get('/mpt/ma/valueposition', 'MA\MptController@index');
Route::group(['middleware' => 'MptArmor'],function(){
	Route::get('/mpt/ma/webvalueposition', 'MA\MptController@webvalueposition');
});
Route::post('/mpt/ma/sendotp', 'MA\MptController@sendopt');
Route::get('/mpt/ma/otp/', 'MA\MptController@otp')->name('otp');
Route::post('/mpt/ma/postotp', 'MA\MptController@postOtp');
Route::post('/mpt/ma/otpregeneration', 'MA\MptController@otpRegeneration');
Route::get('/mpt/ma/subscribed/', 'MA\MptController@subscribed')->name('subscribed');
Route::get('/mpt/ma/loading/', 'MA\MptController@loading')->name('maloading');

// MPT Notify URL
Route::get('/redirect', 'MA\MptController@redirect');
// MPT Callback URL
Route::get('/mpt/notify', 'MA\MaCallbackController@callback');
Route::get('/mpt/ma/checkStatus', 'MA\MptController@checkStatus');
Route::get('/mpt/ma/success', 'MA\MptController@success');
Route::get('/mpt/ma/error', 'MA\MptController@error');
Route::get('/mpt/ma/insufficient', 'MA\MptController@insufficient');
Route::get('/mpt/ma/continue', 'MA\MptController@continue_process');
Route::get('/msisdn', 'MA\MptController@msisdn');
Route::get('mpt/ma/T&C', 'MA\MptController@tandc');
Route::get('mpt/ma/inapptandc', 'MA\MptController@inapptandc');
Route::get('mpt/ma/verify', 'MA\MptController@verify');
Route::get('mpt/ma/fulltandc', 'MA\MptController@fulltandc');
Route::get('/mpt/ma/revenue', 'MA\MptController@revenue');
Route::get('/mpt/ma/json', 'MA\MptController@json');
// MPT SDP Route End

//for has offer testing [the route which one actually hit by has offer after successful conversion]
Route::post('/test_kp', 'Advertise\AdvertiseController@testKpPost');
Route::get('/test_kp', 'Advertise\AdvertiseController@testKpGet');

//for verify c2p player.
Route::post('/playerVerify', 'Api\PlayerVerificationController@playerVerify');


















