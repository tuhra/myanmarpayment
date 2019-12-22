<?php

namespace App\Http\Controllers\MPT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Helper\MptHelper;

class BulkSmsController extends Controller
{
    public function index(Request $request) {
    	$mpt = new MptHelper;
    	$users = UserModel::join('tbl_subscribers', 'tbl_subscribers.user_id', 'tbl_users.id')
    				->where('tbl_users.operator_id', '<>', 1)
    				->where('tbl_subscribers.is_subscribed', 1)
    				->where('tbl_subscribers.is_active', 1)
    				->get();
    	$msg = "သတင္းေကာင္းေလးေျပာပါရေစ။ ဂိမ္းေတြကိုေဆာ့ကစားရတာ ႀကိဳက္ႏွစ္သက္တဲ့ Go Games ၀န္ေဆာင္မႈ အသံုးျပဳသူေတြအတြက္ ၀န္ေဆာင္ခ တစ္ရက္လွ်င္၂၀၀ က်ပ္ မွ ၉၉ က်ပ္ သို႕ ေအာက္တိုဘာလ ၃၀ ရက္ေန႕မွ စတင္၍ ေစ်းႏႈန္းေလွ်ာ့ခ်လိုက္ပါၿပီ။ အေသးစိတ္သိရွိလိုပါက ၁၀၆ (သို႕) http://gg.lotayamm.com/ မွတစ္ဆင့္ ၀င္ေရာက္ၾကည့္ရူလိုက္ပါ။";
    	$i = 0;
    	$count = count($users);
    	foreach ($users as $key => $user) {
            // echo $i .'-'. $msg;
            // echo '<br/>';
    		// $msisdn = ltrim($user->plain_msisdn, '+');
    		// $result = $mpt->sendsms($msg, $msisdn);
    		// $mpt->mpt_sms_log_creation($user->id, $result);
    		$i++;
    	}

    	if ($count == $i) {
    		return $i . "Success";
    	}
    	
    }
}
