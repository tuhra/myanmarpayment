<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use App\Traits\RespondTrait;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerVerificationController extends Controller
{
    use RespondTrait;
    private $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function playerVerify(Request $request){
        $current_date = date("Y-m-d h:i:s");
        if(!$request->has('user')) return $this->responseBadRequest();

        $user = UserModel::with('subscriber','operator')->where('encrypted_msisdn',$request->get('user'))->first();
        if(!$user) return $this->responseNotFound('User Not found');
        if(!isset($user->subscriber) || $user->subscriber->valid_date < $current_date || $user->subscriber->is_subscribed != 1 || $user->subscriber->is_active != 1)
            return $this->responseForbidden('User is not subscribed!');

        return $this->setResponseData($this->userTransformer->transform($user))->responseSuccess();
    }
}
