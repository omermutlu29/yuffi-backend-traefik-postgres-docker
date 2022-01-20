<?php


namespace App\Services\PaymentServices\Iyzico;


use App\Interfaces\PaymentInterfaces\ISubMerchantService;
use Iyzipay\Request\CreateSubMerchantRequest;
use Iyzipay\Request\UpdateSubMerchantRequest;

class IyzicoSubMerchantService extends IyzicoBaseService implements ISubMerchantService
{
    public function insertIyzicoSubMerchant(array $data, CreateSubMerchantRequest $request)
    {
        parent::setOptions();
        $request = self::prepareRequest($request, $data);
        $request->setSubMerchantExternalId($data['tc']);
        $request->setSubMerchantType(\Iyzipay\Model\SubMerchantType::PERSONAL);
        $subMerchant = \Iyzipay\Model\SubMerchant::create($request, $this->options);
        return $subMerchant;
    }

    public function updateIyzicoSubMerchant(array $data, UpdateSubMerchantRequest $request)
    {
        try {
            parent::setOptions();
            $request = self::prepareRequest($request, $data);
            $request->setSubMerchantKey($data['sub_merchant']);
            $subMerchant = \Iyzipay\Model\SubMerchant::update($request, $this->options);
            return $subMerchant;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private static function prepareRequest($request, $data)
    {
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($data['tc']);
        $request->setIban($data['iban']);
        $request->setName($data['name'] . ' ' . $data['surname']);
        $request->setIdentityNumber($data['tc']);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setAddress($data['address']);
        $request->setContactName($data['name']);
        $request->setContactSurname($data['surname']);
        $request->setEmail($data['email']);
        $request->setGsmNumber($data['phone']);
        return $request;
    }

}
