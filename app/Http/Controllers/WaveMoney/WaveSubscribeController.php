<?php

namespace App\Http\Controllers\WaveMoney;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTypeModel;
use App\WM\UserManager;
use App\WM\SubscriberManager;
use App\Models\UserModel;
use App\Models\SubscriberModel;
use App\Models\SubscriberLogModel;
use App\Models\WaveCallbackLogModel;
use App\Models\WavePromotion;
use App\Models\WavePromotionPackage;
use Flash;
use Session;
use DB;
use App\Helper\WavePaymentHelper;

class WaveSubscribeController extends Controller
{
    private $time;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (!empty($data)) {
            Session::put('payment', $data['payment']);
            $msisdn = '95' .$data['msisdn'];
            Session::put('wavepay_msisdn', $data['msisdn']);
            $user = UserModel::where('plain_msisdn', $msisdn)
                            ->first();
            if ($user) {
                $row = SubscriberModel::where('user_id', $user->id)->first();
                $now = strtotime(date("Y-m-d h:i:s"));
                $valid = strtotime($row->valid_date);
                if ($valid < $now) {
                    return redirect('expirewave');
                } else {
                    if (1 == $row->is_subscribed && 1 == $row->is_active) {
                        return view('messages.wave_exist');
                    }
                }
            }
        }

        $promotion = WavePromotion::find(1);
        if (empty($promotion)) {
            $types = SubscriptionTypeModel::limit(3)->get();
            return view('wave.index', compact('types'));
        }

        $start = new \Carbon\Carbon;
        $now = strtotime($start->now()->toDateTimeString());
        $fromDate = strtotime($promotion->fromDate);
        $toDate = strtotime($promotion->toDate);

        if (TRUE == $promotion->is_promotion && $now > $fromDate && $now < $toDate) {
            $types = WavePromotionPackage::limit(3)->get();
            return view('wave.promotion', compact('types')); 

            // if ($now > $fromDate && $now < $toDate) {
            //     $types = WavePromotionPackage::limit(3)->get();
            //     return view('wave.promotion', compact('types'));    
            // }
        }

        $types = SubscriptionTypeModel::limit(3)->get();
        return view('wave.index', compact('types'));

    }

    public function expire() {
        return view('wave.expire');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $promotion = WavePromotion::find(1);
        if($promotion) {
            $start = new \Carbon\Carbon;
            $now = strtotime($start->now()->toDateTimeString());
            $fromDate = strtotime($promotion->fromDate);
            $toDate = strtotime($promotion->toDate);
            $type = null;

            if (TRUE == $promotion->is_promotion && $now > $fromDate && $now < $toDate) {
                $type = WavePromotionPackage::find($id);
                // if ($now > $fromDate && $now < $toDate) {
                //     $type = WavePromotionPackage::find($id);
                // }
            } else {
                $type = SubscriptionTypeModel::find($id);
            }    
        } else {
            $type = SubscriptionTypeModel::find($id);
        }
        

        if (empty($type)) {
            Flash::error('Subscription type not found');
            return redirect('/wavesubscribe');
        }
        Session::put('sub_type_id', $type->id);
        Session::put('amount', $type->amount);
        // $typearr = ["type" => $type];
        return view("wave.create", compact('typearr'));
    }

    public function checkStatus() {
        $paymentRequestId = Session::get('paymentRequestId');
        // \Log::info($paymentRequestId);
        $row = WaveCallbackLogModel::where('refId', $paymentRequestId)->first();
        $sub_type_id = Session::get('sub_type_id');
        $user_id = Session::get('user_id');

        if (empty($row)) {
            $response = ['success' => true, 'redirect' => '/failed'];
            return $response;
        }

        if ($row->statusCode == "107") {
            $is_exist=SubscriberModel::userId($user_id)->first();
            if (!$is_exist) {
                Session::put('sub_type_id', $sub_type_id);
                $sub_id=subcriptionID();
                $subscribermanager = new SubscriberManager;
                $subscribermanager->subscribe($user_id,$sub_id, $sub_type_id);
                $this->subscriber_log_creation($user_id, $sub_id);
                $response = ['success' => true, 'redirect' => '/success'];
                return $response;
            } else {
                if ($is_exist->is_subscribed == 1 && $is_exist->is_active == 1) {
                    $response = ['success' => true, 'redirect' => '/wave_exist'];
                    return $response;
                }
                Session::put('sub_type_id', $sub_type_id);
                $subscribermanager = new SubscriberManager;
                $sub_id = $is_exist->subscription_id;
                $subscribermanager->updatesubscriber($user_id,$sub_id, $sub_type_id);
                // $this->subscriber_log_creation($user_id, $sub_id);
                $response = ['success' => true, 'redirect' => '/success'];
                return $response;
            }
        } else {
            $response = ['success' => true, 'redirect' => '/failed'];
            return $response;
        }
    }

    public function store(Request $request) {
        $data = $request->all();

        if (Session::has('payment') || Session::has('wavepay_msisdn')) {
            $msisdn = '+'.Session::get('wavepay_msisdn');
            $wavemsisdn = substr($msisdn, 3);
            $client_secret = '3E5958Fb0325477399d7D4BC1189C06F';
            $client_id = '69cb3c9979164659ad194c6025842794';
            $url = 'https://api.wavemoney.io:8100/wmt-mfs-merchant-exp/pay-with-wave';
            $callback = "https://telenor.gogamesapp.com/wave/callback";
        } else {
            $wavemsisdn = substr($data['number'], 1);
            $msisdn = $data['country_code'].$wavemsisdn;
            $client_secret = 'f460fcBd98414D66ae89310E59166bED';
            $client_id = 'c32a4bcb60fd4de699e285d45ab22cc0';
            $url = 'https://api.wavemoney.io:8100/wmt-mfs-merchant-exp/pay-with-wave';
            $callback = "https://telenor.gogamesapp.com/wave/callback";
        }

        $sub_type_id = Session::get('sub_type_id');
        $amount = Session::get('amount');

        $operator = getopr($msisdn);
        if ($operator == 'MNTC') {
            return redirect('/message');
        }

        $msisdn=ltrim($msisdn,'+');
        setMsisdn($msisdn);
        $row = UserModel::where('plain_msisdn', $msisdn)
                        ->first();
        if (!$row) {
            $row=new UserModel;
            $row->operator_id=3;
            $row->plain_msisdn=$msisdn;
            $row->encrypted_msisdn=getUUID();
            $row->save();
        }

        Session::put('user_id', $row->id);
        $paymentRequestId = getUUID();

        $wavehelper = new WavePaymentHelper;
        $result = $wavehelper->wavenotifyRequest($url, $wavemsisdn, $amount, $paymentRequestId, $client_secret, $client_id, $callback);
        \Log::info($result);
        $arr = json_decode($result, true);
        if ("102" == $arr['statusCode']) {
            Session::put('paymentRequestId', $paymentRequestId);
            return redirect('/loading');
        } else {
            return redirect('/failed');
        }

    }

    public function subscriber_log_creation($user_id,$sub_id)
    {
        $channel = getChannelId('WEB');
        if (Session::has('payment')) {
            $channel = getChannelId(strtoupper(Session::get('payment')));
        }
        $log=new SubscriberLogModel;
        $log->user_id=$user_id;
        $log->attempt_type=1;
        $log->channel_id= $channel;
        $log->attempt_type_status=1;
        $log->subscription_id=$sub_id;
        $log->event='SUBSCRIBED';
        $log->save();
    }

    public function dashboard() {
        $promotion = WavePromotion::find(1);
        return view('wave.dashboard', compact('promotion'));
    }

    public function promotion(Request $request) {
        $data = $request->all();
        $row = WavePromotion::find(1);
        $row->fromDate = $data['fromDate'];
        $row->toDate = $data['toDate'];
        if (array_key_exists('is_promotion', $data)) {
            $row->is_promotion = $data['is_promotion'];
        }else {
            $row->is_promotion = 0;
        }
        $row->save();

        Flash::success('successfully update wave money promotion');
        return redirect('/wave/dashboard');
    }

    public function callback(Request $request) {
        \Log::info($request->headers->all());
        $headers = $request->headers->all();
        \Log::info($request->getContent());
        \Log::info('===================================');
        $paymentRequestId = $headers['paymentrequestid'][0];
        \Log::info($paymentRequestId);

        $arr = json_decode($request->getContent(), true);
        $log = new WaveCallbackLogModel;
        $log->refId = $paymentRequestId;
        $log->statusCode = $arr['statusCode'];
        $log->resBody = $request->getContent();
        $log->save();

        // DB::table('status_codes')
        //     ->insert([
        //         'status_code' => $arr['statusCode'],
        //         'status_detail' => $arr['statusDescription']
        //     ]);
    }

    public function tandc() {
        $promotion = WavePromotion::find(1);
        $startDate = explode(" ", $promotion->fromDate);
        $endDate = explode(" ", $promotion->toDate);
        $fromDate = $this->changeformat($startDate[0]);
        $toDate = $this->changeformat($endDate[0]);
        return view('wave.TandC', compact('fromDate', 'toDate'));
    }

    private function changeformat($date) {
        $arr = explode("-", $date);
        if (Session::get('locale') == "mm") {
            $mmNumber = ['0' => '၀','1' => '၁','2' => '၂','3' => '၃','4' => '၄','5' => '၅','6' => '၆','7' => '၇','8' => '၈','9' => '၉', ];
            return convertEngToMyanmarNumber($arr[2].".".$arr[1].".".$arr[0]);

        }
        return $arr[2].".".$arr[1].".".$arr[0];
    }

}






