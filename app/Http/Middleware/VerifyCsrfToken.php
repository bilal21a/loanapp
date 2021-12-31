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
        "/api/v1/payment_webhook_Mavunifs_2020",
        "/api/v1/payment_webhook_Mavunifs_2020_shago",
        "/api/v1/account/kyc",
        "/api/v1/payment_webhook_paystack"
    ];
}
