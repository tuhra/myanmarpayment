<?php

namespace App\Http\Controllers\Advertise;

use App\AffiliateServices\AffiliateService;
use App\AffiliateServices\AffiseService;
use App\AffiliateServices\HasOfferService;
use App\Kimia\Kimia;
use App\Models\HasOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KimiaCpcModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Return_;

class AdvertiseController extends Controller
{
    private $affiliateService;
    private $s2sService;

    function __construct(AffiliateService $affiliateService, Kimia $kimia)
    {
        $this->affiliateService = $affiliateService;
        $this->s2sService = $kimia;
    }

    public function handleAdsRequest(Request $request){
        $adNetwork = $request->has('adnetwork') ? $request->get('adnetwork') : 's2s';

        if(method_exists($this, Str::camel($adNetwork))){
            return $this->{Str::camel($adNetwork)}($request);
        }else{
            return response()->json([
                'message' => 'adnetwork value mismatch',
            ],400);
        }


    }

    private function validateRequest($request, $rulesArray){
        return $validator = Validator::make($request->all(), $rulesArray);
    }

    private function affise($request){
        if($this->validateRequest($request, config('affiliate.affiliatesRequiredParam')['affise'])->fails()){
            return response()->json([
                'message' => 'Parameter is missing.',
            ],400);
        }

        $this->getAffiseService()->storeDataInSession($request);
        return redirect('/');
    }

    private function hasoffer($request){
        if($this->validateRequest($request, config('affiliate.affiliatesRequiredParam')['hasOffer'])->fails()){
            return response()->json([
                'message' => 'Parameter is missing.',
            ],400);
        }

        $this->getHasOfferService()->storeDataInSession($request);
        return redirect('/');
    }

    private function s2s($request){
        if($this->validateRequest($request, config('affiliate.affiliatesRequiredParam')['s2s'])->fails()){
            return response()->json([
                'message' => 'Parameter(kp) is missing.',
            ],400);
        }
        $this->s2sService->handleKpRequest($request);
        return redirect('/');
    }

    private function getHasOfferService(){
        return $this->affiliateService = new HasOfferService();
    }

    private function getAffiseService(){
        return $this->affiliateService = new AffiseService();
    }







}
