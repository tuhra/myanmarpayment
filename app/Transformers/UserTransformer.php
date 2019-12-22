<?php
/**
 * Created by PhpStorm.
 * User: ashraful
 * Date: 3/31/19
 * Time: 10:59 AM
 */

namespace App\Transformers;


use Illuminate\Support\Facades\Storage;

class UserTransformer
{
    public function transform($user)
    {
        $userRes = array();
        $userRes['user_name'] = $user->plain_msisdn;
        $userRes['user_identifier'] = $user->encrypted_msisdn;
        $userRes['is_active'] = $user->subscriber->is_active;
        $userRes['is_subscribed'] = $user->subscriber->is_subscribed;
        $userRes['next_renewal_date'] = $user->subscriber->valid_date;
        $userRes['operator_name'] = $user->operator->name;
        $userRes['apk_download_link'] = config('c2p.apk_download_link');

        return $userRes;

    }

}