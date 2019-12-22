<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 4/8/19
 * Time: 4:31 PM
 */

namespace App\AffiliateServices;


interface AffiliateService
{
    public function successfulConversion($userId);
    public function storeDataInSession($req);

}