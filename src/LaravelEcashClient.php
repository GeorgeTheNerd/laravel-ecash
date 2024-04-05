<?php

namespace MhdGhaithAlhelwany\LaravelEcash;

use MhdGhaithAlhelwany\LaravelEcash\DataObjects\PaymentDataObject;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\ArrayToUrl;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\PaymentUrlGenerator;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\UrlEncoder;
use MhdGhaithAlhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;

class LaravelEcashClient
{
    private PaymentUrlGenerator $paymentUrlGenerator;

    public function __construct($gatewayUrl, $terminalKey, $merchantId, $merchantSecret)
    {
        $this->paymentUrlGenerator = new PaymentUrlGenerator(
            $gatewayUrl,
            $terminalKey,
            $merchantId,
            new ArrayToUrl,
            new VerificationCodeGenerator($merchantId, $merchantSecret),
            new UrlEncoder
        );
    }

    public static function getInstance()
    {
        return new self(
            config('ecash.gatewayUrl'),
            config('ecash.terminalKey'),
            config('ecash.merchantId'),
            config('ecash.merchantSecret')
        );
    }

    public function generateUrl(PaymentDataObject $paymentDataObject)
    {
        return $this->paymentUrlGenerator->generateUrl($paymentDataObject);
    }
}
