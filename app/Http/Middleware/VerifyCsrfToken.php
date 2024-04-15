<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    	'install/setupenv',
    	'install/setupuser',
        'paypal-payment-ipn',
        'candidate-paypal-payment-ipn',
        'razorpay-verify',
        'candidate-razorpay-verify',
        'employer/job/transfer',
    ];
}
