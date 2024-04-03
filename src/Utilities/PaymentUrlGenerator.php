<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

use GeorgeTheNerd\LaravelEcash\DataObjects\PaymentDataObject;
use GeorgeTheNerd\LaravelEcash\Utilities\ArrayToUrl;
use GeorgeTheNerd\LaravelEcash\Utilities\VerificationCodeGenerator;

class PaymentUrlGenerator
{
    private $gatewayUrl;

    private $terminalKey;

    private $merchantId;

    private ArrayToUrl $arrayToUrl;

    private VerificationCodeGenerator $verificationCodeGenerator;

    private UrlEncoder $urlEncoder;


    public function __construct(string $gatewayUrl, string $terminalKey, string $merchantId, ArrayToUrl $arrayToUrl, VerificationCodeGenerator $verificationCodeGenerator, UrlEncoder $urlEncoder)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->terminalKey = $terminalKey;
        $this->merchantId = $merchantId;
        $this->arrayToUrl = $arrayToUrl;
        $this->verificationCodeGenerator = $verificationCodeGenerator;
        $this->urlEncoder = $urlEncoder;
    }

    /**
     * Generates Payment Url to be redirected to
     *
     * @param PaymentDataObject $paymentDataObject
     * @return string
     */
    public function generateUrl(PaymentDataObject $paymentDataObject): string
    {
        $orderRef = 1;
        return $this->arrayToUrl->generate($this->gatewayUrl, [
            'checkout',
            $paymentDataObject->getCheckoutType()->value,
            $this->terminalKey,
            $this->merchantId,
            $this->verificationCodeGenerator->generate($paymentDataObject->getAmount(), $orderRef),
            $paymentDataObject->getCurrency()->value,
            $paymentDataObject->getAmount(),
            $paymentDataObject->getLang()->value,
            1,
            $this->urlEncoder->encode($paymentDataObject->getRedirectUrl()),
            $this->urlEncoder->encode(route('ecash.callback'))
        ]);
    }
}