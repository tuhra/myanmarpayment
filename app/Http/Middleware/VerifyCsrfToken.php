<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    	'/telenor/callback',
    	'/mpt/mo/callback',
        '/mpt/mt/notify',
        '/mpt/notify',
        '/wave/callback',
        '/test_kp',
        '/playerVerify',
        'telenor/mo/unsubscribe',
    ];
}
