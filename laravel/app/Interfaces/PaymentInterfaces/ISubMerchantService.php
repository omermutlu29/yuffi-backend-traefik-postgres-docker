<?php


namespace App\Interfaces\PaymentInterfaces;


use Iyzipay\Request\CreateSubMerchantRequest;
use Iyzipay\Request\UpdateSubMerchantRequest;

interface ISubMerchantService
{
    public function insertIyzicoSubMerchant(array $data, CreateSubMerchantRequest $request);

    public function updateIyzicoSubMerchant(array $data, UpdateSubMerchantRequest $request);
}
