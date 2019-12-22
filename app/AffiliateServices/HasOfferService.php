<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 4/8/19
 * Time: 4:29 PM
 */

namespace App\AffiliateServices;

use App\Models\HasOffer;
use Illuminate\Support\Facades\Session;

class HasOfferService implements AffiliateService
{
     public function successfulConversion($userId){

            \Log::info('Start successfulConversion'. $userId);
            if(!$this->getHasOfferData()) return;

            $hasOfferData = Session::get('hasOfferData');

            $hasOfferIns = HasOffer::where('id', $hasOfferData['id'])->first();
            if(!$hasOfferIns) return;

            $hasOfferIns->user_id = $userId;
            $hasOfferIns->raw_request = $this->generateHasOfferCallbackUrl($hasOfferData['offerId'], $hasOfferData['transaction_id']);
            \Log::info('Raw Request'. $hasOfferIns->raw_request);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $hasOfferIns->raw_request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $content = curl_exec($ch);
            curl_close($ch);
            \Log::info('Raw Response'. $content);


            if($content){
                $responseArr = explode(';',$content);
                $isSuccess = false;

                foreach ($responseArr as $temp){
                    $tempArr = explode('=',$temp);
                    if($tempArr[0] == "success") {
                        $isSuccess = $tempArr[1];
                        break;
                    }
                }
                if($isSuccess == 'true'){
                    $hasOfferIns->conversion_happened = 1;
                }
            }

            $hasOfferIns->raw_response = $content;
            \Log::info('HasOfferInstance'. json_encode($hasOfferIns->toArray()));
            $hasOfferIns->save();

            Session::forget('hasOfferData');


    }

    public function storeDataInSession($req){

        $data = [
            'offerId' => $req->get('offerid'),
            'transaction_id' => $req->get('trans'),
            'affiliate_id' => $req->get('affiliate_id'),
            'aff_click_id' => $req->get('aff_click_id'),
            'sourceId' => $req->has('source') ? $req->get('source') : null,
            'publisher' =>$req->get('aff_sub3'),

        ];

        $hasOfferIns = $this->saveHasOfferData($data);
        $data['id'] = $hasOfferIns->id;
        session(['hasOfferData'=>$data]);

    }

    private function saveHasOfferData($data){

        $hasOfferInstance = new HasOffer();
        $hasOfferInstance->offer_id = $data['offerId'];
        $hasOfferInstance->transaction_id = $data['transaction_id'];
        $hasOfferInstance->affiliate_id = $data['affiliate_id'];
        $hasOfferInstance->source_id = $data['sourceId'];
        $hasOfferInstance->publisher_id = $data['publisher'];
        $hasOfferInstance->aff_click_id = $data['aff_click_id'];
        $hasOfferInstance->click_happened = 1;
        $hasOfferInstance->conversion_happened = 0;
        //dd($hasOfferInstance);
        $hasOfferInstance->save();


        return $hasOfferInstance;

    }

    private function getHasOfferData(){
        if(Session::get('hasOfferData')){
            return true;
        }
        else{
            return false;
        }
    }

    private function generateHasOfferCallbackUrl($offerId, $transId){
        return 'http://gogames.go2cloud.org/aff_lsr?offer_id='.$offerId.'&transaction_id='.$transId;
    }


}