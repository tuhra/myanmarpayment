<?php
/**
 * Created by PhpStorm.
 * User: ashraful
 * Date: 5/21/19
 * Time: 12:33 PM
 */

namespace App\AffiliateServices;


use App\Models\Affise;
use Illuminate\Support\Facades\Session;

class AffiseService implements AffiliateService
{
    public function storeDataInSession($req){
        $data = [
            'affiseClickId' => $req->get('affise_click_id'),
            'offerId' => $req->get('offer_id'),
            'offerName' => $req->get('offer_name'),
            'affiliateId' => $req->get('affiliate_id'),
            'affiliateName' => $req->get('affiliate_name'),
            'affiliateClickId' => $req->get('affiliate_click_id'),
            'source' => $req->get('source'),
            'publisher' => $req->get('publisher'),
            'payout' => $req->get('payout'),
            'lifeTimeBudget' => $req->get('lifetime_budget')
        ];

        $affiseInstance = $this->saveAffiseData($data);
        $data['id'] = $affiseInstance->id;
        session(['affiseData'=>$data]);
    }

    //only for new subscriber
    public function successfulConversion($userId){
        if(!$this->isExistInSession()) return;

        $affiseData = Session::get('affiseData');

        $affiseIns = Affise::where('id', $affiseData['id'])->first();
        if(!$affiseIns) return;

        $affiseIns->user_id = $userId;
        $affiseIns->raw_request = $this->generateCallbackUrl($affiseData['affiseClickId']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $affiseIns->raw_request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content = curl_exec($ch);
        curl_close($ch);

        if($content){
            $response = json_decode($content);
            if($response->status == 1){
                $affiseIns->conversion_happened = 1;
            }
        }

        $affiseIns->raw_response = $content;
        $affiseIns->is_new_user = 1;
        $affiseIns->save();

        Session::forget('affiseData');
    }

    //mark those user who's record exist in tbl_subscriber and coming through affiliates and hit subscribe button
    public function markReturningUser($userId){

        if(!$this->isExistInSession()) return;

        $affiseData = Session::get('affiseData');

        $affiseIns = Affise::where('id', $affiseData['id'])->first();
        if(!$affiseIns) return;

        $affiseIns->user_id = $userId;
        $affiseIns->is_new_user = 0;
        $affiseIns->save();

        Session::forget('affiseData');
    }

    private function saveAffiseData($dataArr){
        $affiseInstance = new Affise();
        $affiseInstance->affise_click_id = $dataArr['affiseClickId'];
        $affiseInstance->offer_id = $dataArr['offerId'];
        $affiseInstance->offer_name = $dataArr['offerName'];
        $affiseInstance->affiliate_id = $dataArr['affiliateId'];
        $affiseInstance->affiliate_name = $dataArr['affiliateName'];
        $affiseInstance->affiliate_click_id = $dataArr['affiliateClickId'];
        $affiseInstance->source = $dataArr['source'];
        $affiseInstance->publisher = $dataArr['publisher'];
        $affiseInstance->payout = $dataArr['payout'];
        $affiseInstance->lifetime_budget = $dataArr['lifeTimeBudget'];

        $affiseInstance->click_happened = 1;
        $affiseInstance->conversion_happened = 0;
        $affiseInstance->is_new_user = 0;

        $affiseInstance->save();


        return $affiseInstance;
    }

    private function isExistInSession(){
        if(Session::get('affiseData')){
            return true;
        } else{
            return false;
        }
    }

    private function generateCallbackUrl($affiseClickId){

        return 'http://offers.gogames.affise.com/postback?clickid=' . $affiseClickId;
    }

}