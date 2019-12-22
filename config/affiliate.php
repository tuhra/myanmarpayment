<?php
/**
 * Created by PhpStorm.
 * User: ashraful
 * Date: 5/21/19
 * Time: 12:52 PM
 */

return [
    'affiliatesRequiredParam' => [
                                    'affise'=> [
                                        'affise_click_id' =>'required',
                                        'offer_id' =>'required',
                                        'affiliate_id' =>'required',
                                        'affiliate_click_id' =>'required'
                                    ],
                                    'hasOffer'=> [
                                        'offerid' =>'required',
                                        'trans' =>'required',
                                        'affiliate_id' =>'required',
                                        'aff_click_id' =>'required',
                                        'aff_sub3' =>'required'
                                    ],
                                    's2s' => [
                                        'kp' => 'required'
                                    ]
                                ],

];