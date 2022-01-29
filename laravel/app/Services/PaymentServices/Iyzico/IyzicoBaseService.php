<?php


namespace App\Services\PaymentServices\Iyzico;

use Iyzipay\Options;

abstract class IyzicoBaseService
{
    protected string $apiKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected Options $options;

    public function __construct(Options $options)
    {
        $this->apiKey = env('IYZICO_API_KEY');
        $this->secretKey = env('IYZICO_SECRET_KEY');
        $this->baseUrl = env('IYZICO_BASEURL');
        $this->options = $options;
    }

    protected function setOptions()
    {
        $this->options->setApiKey($this->apiKey);
        $this->options->setSecretKey($this->secretKey);
        $this->options->setBaseUrl($this->baseUrl);
    }
}
