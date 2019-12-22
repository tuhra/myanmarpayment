<?php

use App\Models\CountryModel;
use App\Models\OperatorModel;
use App\Models\UserModel;
use App\Models\ChannelModel;
use App\Models\SubscriptionTypeModel;
use App\Models\DndModel;
use Propaganistas\LaravelPhone\PhoneNumber;

if (!function_exists('load_asset')) {
    /*
     * Set where to load assets from based on secure or non secure http
     */

    function load_asset($asset_url) {
        return (env('APP_ENV') === 'PRODUCTION') ? secure_asset($asset_url) : asset($asset_url);
    }

}

function subcriptionID() {
    return "SUB" . time() . rand(100, 999);
}

function getChannelId($name) {
    // $row = ChannelModel::Channel($name)->first();
    $row = ChannelModel::where('name', $name)->first();
    if (!$row) {
        $row = new ChannelModel;
        $row->name = $name;
        $row->save();
    }
    return $row->id;
}

function setMsisdn($msisdn) {
    Session::put('msisdn', $msisdn);
}

function unsetMsisdn() {
    Session::forget('msisdn');
}

function getMsisdn() {
    return Session::get('msisdn');
}

function setCallback($callback) {
    Session::put('callbackURL', $callback);
}

function getCallback() {
    return Session::get('callbackURL');
}

function unsetCallback() {
    Session::forget('callback');
}

function getSubType($id) {
    $row = SubscriptionTypeModel::find($id);
    return $row;
}

function getSubscriptionType($type) {
    // $row = SubscriptionTypeModel::Name($type)->first();
    $row = SubscriptionTypeModel::where('name', $type)->first();
    if (!$row) {
        $row = new SubscriptionTypeModel;
        $row->name = $type;
        $row->save();
    }
    return $row->id;
}

function channel_id($name) {
    // $row = ChannelModel::Channel($name)->first();
    $row = ChannelModel::where('name', $name)->first();
    if (!$row) {
        $row = new ChannelModel;
        $row->name = $name;
        $row->save();
    }
    return $row->id;
}

function CheckValidDate($valid) {
    $now = date('Y-m-d h:i:s', time());
    if ($valid < $now) {
        return "Inactive";
    }

    return "Active";

}

function checkstatus($isSubscribe, $isActive) {
    if ($isSubscribe == 1 && $isActive == 1) {
        return true;
    }

    return false;
}


function getopr($msisdn) {
    $carrierMapper = \libphonenumber\PhoneNumberToCarrierMapper::getInstance();
    $chNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
    $operator_name=$carrierMapper->getNameForNumber($chNumber, 'en');
    return $operator_name;
}

function generateotp() {
    $encrypted = md5(uniqid(rand(), true));

    $x = 3; // Amount of digits
    $min = pow(10,$x);
    $max = pow(10,$x+1)-1;
    $otp = rand($min, $max);
    return [$otp, $encrypted];
}

function country($msisdn)
{
    $geoCoder = \libphonenumber\geocoding\PhoneNumberOfflineGeocoder::getInstance();
    $gbNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
    $country=$geoCoder->getDescriptionForNumber($gbNumber, 'en_GB', 'US');
    $row=CountryModel::where('name',$country)->first();
    if(!$row){
       $row=new CountryModel;
       $row->name=$country;
       $row->save();
    }
    return $row->id;
}

function operator($msisdn,$country_id)
{
    $carrierMapper = \libphonenumber\PhoneNumberToCarrierMapper::getInstance();
    $chNumber = \libphonenumber\PhoneNumberUtil::getInstance()->parse($msisdn, null);
    $operator_name=$carrierMapper->getNameForNumber($chNumber, 'en');
    $row=OperatorModel::where('name',$operator_name)->first();
    if(!$row){
       $row=new OperatorModel;
       $row->country_id=$country_id;
       $row->name=$operator_name;
       $row->save();
    }
    return $row->id;
}

function getOperator($id) {
    $row = OperatorModel::find($id);
    return $row->name;
}

function getUser($msisdn) {
    $row = UserModel::where('plain_msisdn', $msisdn)->first();
    if (empty($row)) {
        $row = new UserModel;
        $row->operator_id = 2;
        $row->plain_msisdn = $msisdn;
        $row->encrypted_msisdn = getUUID();
        $row->save();  
    }
    return $row;
}

function getMoResponse($status, $text) {
    $response = [];
    $response['status'] = $status;
    $response['message'] = $text;
    return json_encode($response);
}

function getMoUser($msisdn) {
    $row = UserModel::where('plain_msisdn', $msisdn)->first();
    if (empty($row)) {
        $row = new UserModel;
        $country_id=country('+'.$msisdn);//country creation.
        $operator_id=operator('+'.$msisdn,$country_id);//operator creation.
        $row->operator_id = 2;
        $row->moStatus = 1;
        $row->plain_msisdn = $msisdn;
        $row->encrypted_msisdn = getUUID();
        $row->save();  
    }
    return $row;
}

function getSMSDate($date) {
    $start = new \Carbon\Carbon;
    $date = $start->now()->addDays($date);
    $valid = $date->toDateTimeString();
    $renewal_date = $date->toDateString();
    return date('d/m/Y', strtotime($renewal_date));
}

function getRenewalDate() {
    $start = new \Carbon\Carbon;
    $date = $start->now()->addDays(7);
    $valid = $date->toDateTimeString();
    $renewal_date = $date->toDateString();
    return [$renewal_date, $valid];
}

function getMaRenewalDate($date = 1) {
    $start = new \Carbon\Carbon;
    $date = $start->now()->addDays(1);
    $valid = $date->toDateTimeString();
    $renewal_date = $date->toDateString();
    return $valid;
}

function renewalDate() {
    $start = new \Carbon\Carbon;
    $date = $start->now()->addDays(1);
    $valid = $date->toDateTimeString();
    $renewal_date = $date->toDateString();
    return $valid;   
}

function loadxml($xmlfile) {
    $xml = simplexml_load_string($xmlfile);
    return $xml;
}


function getUUID()
{
    return rand(100,999).time().rand(100,999);
}

function setOptId($operatorId) {
    Session::put('operator_id', $operatorId);
}

function getOptId() {
    return Session::get('operator_id');
}

function setKpValue($kp_value){
    Session::put('kp_value',$kp_value);
}

function unsetKpValue(){
    Session::forget('kp_value');
} 

function getKpValue(){
    return Session::get('kp_value');
}

function getTimestamp() {
    $current_date = date("Y-m-d h:i:s");
    return strtotime($current_date);
}

function setEncMsisdn($enc_msisdn) {
    Session::put('encrypted_msisdn',$enc_msisdn);
}

function getEncMissdn() {
    return Session::get('encrypted_msisdn');
}

function setUserType($user_type) {
    Session::put('user_type',$user_type);
}

function getUserType() {
    return Session::get('user_type');
}

function xmlToArray($xml, $options = array()) {
    $defaults = array(
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => '$',       //key used for the text content of elements
        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces();
    $namespaces[''] = null; //add base (empty) namespace
 
    //get attributes from all namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            //replace characters in attribute name
            if ($options['keySearch']) $attributeName =
                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }

 
    //get child nodes from all namespaces
    $tagsArray = array();
    $array = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->children($namespace) as $childXml) {
            //recurse into child nodes
            $childArray = xmlToArray($childXml, $options);
            list($childTagName, $childProperties) = thura($childArray);
 
            //replace characters in tag name
            if ($options['keySearch']) $childTagName =
                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            //add namespace prefix, if any
            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
 
            if (!isset($tagsArray[$childTagName])) {
                //only entry with this key
                //test if tags of this type should always be arrays, no matter the element count
                $tagsArray[$childTagName] =
                        in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                        ? array($childProperties) : $childProperties;
            } elseif (
                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                //key already exists and is integer indexed array
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                //key exists so convert to integer indexed array with previous value in position 0
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }
    
    //get text content of node
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
 
    //stick it all together
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
 
    //return node as array
    return array(
        $xml->getName() => $propertiesArray
    );
}

function thura(&$arr) {
    $key = key($arr);
    $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
    next($arr);
    return $result;
}

function setotpencrypted($encrypted) {
    Session::put('encrypted', $encrypted);
}

function getotpencrypted() {
    return Session::get('encrypted');
}

function setotp($otp) {
    Session::put('otp', $otp);
}

function getotp() {
    return Session::get('otp');
}

function setSocialID($social_id) {
    Session::put('social_id', $social_id);
}

function getSocialID() {
    return Session::get('social_id');
}

function unsetSocialID() {
    Session::forget('social_id');
}

function check_dnd_status($msisdn) {
    $row = DndModel::where('msisdn', $msisdn)->first();
    if ($row) {
        // $row = new DndModel;
        // $row->msisdn = $msisdn;
        // $row->dnd_status = 'telenor';
        // $row->created_at = \Carbon\Carbon::now()->toDateTimeString();
        // $row->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        // $row->save();

        $start = new \Carbon\Carbon;
        $now = $start->now();
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $row->created_at);
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $now);
        $diff_in_days = $to->diffInDays($from);
        if ($diff_in_days < 90) {
            return true;
        }
    }

    return false;

}

function dnd($msisdn) {
    $dnd = DndModel::where('msisdn', $msisdn)->first();
    if (empty($dnd)) {
        $dnd = new DndModel;
        $dnd->msisdn = $msisdn;
        $dnd->dnd_status = 'Telenor';
        $dnd->created_at = \Carbon\Carbon::now()->toDateTimeString();
        $dnd->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        $dnd->save();   
    } else {
        $now = date("Y-m-d h:i:s");;

        DndModel::where('id', $dnd->id)
                ->update(['created_at' => $now]);
    }
}

function getOtt() {
    return Session::get('ott');
}

function createTmpChannel() {
    $channel = (getSocialID()) ? 'Social' : 'WEB';
    if (Session::has('ott')) {
        $channel = 'App';
    }

    if(getKpValue()) {
        $channel = 'MPT_Armor';
    }

    if(Session::has('he_msisdn')) {
        $msisdn = Session::get('he_msisdn');
    }

    if(getMsisdn()) {
        $msisdn = Session::get('msisdn');
    }

    DB::table('tbl_tmp_channel')->insert([
        'plain_msisdn' => $msisdn,
        'channel' => $channel
    ]);
}

function getTmpChannel($msisdn) {
    $row = DB::table('tbl_tmp_channel')->where('plain_msisdn', $msisdn)->first();
    DB::table('tbl_tmp_channel')->where('id', $row->id)->delete();
    return $row;
}

function convertEngToMyanmarNumber($eng_number)
{
    if (strlen($eng_number) == 0) return $eng_number;
    $myCharacter = array('0' => '၀', '1' => '၁', '2' => '၂', '3' => '၃', '4' => '၄', '5' => '၅', '6' => '၆', '7' => '၇', '8' => '၈', '9' => '၉');
    $my_number = "";
    foreach (str_split($eng_number) as $character) {
        $my_number .= array_key_exists($character, $myCharacter) ? $myCharacter[$character] : $character;
    }
    return $my_number;
}


function telenorConsentPageRequest($endUserId) {
    // $url = 'https://api-mocks.radiocut.site/td-consent?';
    $url = 'https://dob.payment.io/v1/purchase/consent?';
    // $okurl = url('telenor/subscription?test=test');
    $okurl = url('telenor/subscription');
    // if(Session::get("HE")) {
    //     $okurl = url('pricepoint');
    // }
    $okurl = urlencode($okurl);
    $denyurl = urlencode('http://mm.gogamesapp.com');
    $errorurl = urlencode('http://mm.gogamesapp.com');

    $customerReference = $endUserId;
    $productDescription = urlencode('GoGames Subscription');
    $subscriptionPeriod = 'P1W';
    $partner = 'miaki';
    $serviceId = 'theServiceId';
    $stopMessage = 'STOP GGMM';
    $consent_key = 'nmNo5zIq1LkmRxqTI1q5jDjoNtaO2KVD9HVbHz586r3bPzc864x4SilpwoQ4Hrj'; 
    $amount = '499';
    $querystring = 'amount='.$amount.'&currency=MMK&customerReference='.$customerReference.
        '&denyUrl='.$denyurl.'&errorUrl='.$errorurl.'&okUrl='.$okurl.'&operatorId=TLN-MM&partner='.$partner.
        "&productDescription=".$productDescription.
        // "&serviceId=".$serviceId.
        "&subscriptionPeriod=".$subscriptionPeriod.
        "&stopMessage=". $stopMessage;
    $signature = generateSignature($consent_key, $querystring);
    $url = $url.$querystring."&signature=".$signature;
    \Log::info($url);
    return $url;
}

function setConsentId($data) {
    if (array_key_exists('consentId', $data)) {
        Session::put('consent_id', $data['consentId']);
    }
}

function getConsentId() {
    return Session::get('consent_id');
}

function generateSignature($consent_key, $querystring) {
    $array = explode("&", $querystring);
    $count = count($array);
    unset($array[$count-1]); // Removes stopMessage
    // TODO: remove serviceId if it's empty

    $array = array_map("urldecode", $array);
    $string = implode("\n", $array) . "\n";
    $signature = hash_hmac('sha256', $string, $consent_key, false /*returns hexdigest */);
    return $signature;
}





