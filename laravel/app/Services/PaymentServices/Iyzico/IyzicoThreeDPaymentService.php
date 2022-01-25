<?php


namespace App\Services\PaymentServices\Iyzico;

use App\Interfaces\PaymentInterfaces\ICompleteThreeDPayment;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentToSubMerchant;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentInitialize;

class IyzicoThreeDPaymentService extends IyzicoPaymentBaseService
    implements IThreeDPaymentInitialize, IThreeDPaymentToSubMerchant, ICompleteThreeDPayment
{
    public function initializeThreeDPayment(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId, ?string $callbackUrl): \Iyzipay\Model\ThreedsInitialize
    {
        parent::setOptions();
        parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->paymentRequest->setCallbackUrl($callbackUrl);//For ThreeD
        $this->paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
        parent::createPaymentCard($cardInformation);
        parent::createBuyer($buyerInformation);
        parent::createBillingAddress($addressInformation);
        parent::createShippingAddress($addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = parent::generateBasketItemMerchant(
                $product['id'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
        }
        $this->paymentRequest->setBasketItems($basketItems);
        $result = \Iyzipay\Model\ThreedsInitialize::create($this->paymentRequest, $this->options);
        if ($result->getStatus() !== "success") throw new \Exception($result->getErrorMessage(), $result->getErrorCode());
        return $result;
    }

    public function initializeThreeDForSubMerchant(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId, string $subMerchant, float $subMerchantPrice, string $callbackUrl)
    {
        parent::setOptions();
        parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->paymentRequest->setCallbackUrl($callbackUrl);//For ThreeD
        $this->paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
        parent::createPaymentCard($cardInformation);
        parent::createBuyer($buyerInformation);
        parent::createBillingAddress($addressInformation);
        parent::createShippingAddress($addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = parent::generateBasketItemForSubMerchant(
                $subMerchant,
                $subMerchantPrice,
                $product['id'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
        }
        $this->paymentRequest->setBasketItems($basketItems);
        $result = \Iyzipay\Model\ThreedsInitialize::create($this->paymentRequest, $this->options);
        if ($result->getStatus() !== "success") throw new \Exception($result->getErrorMessage(), $result->getErrorCode());
        return $result;
    }

    public function completeThreeDPayment(string $conversationId, string $paymentId, ?string $conversationData): \Iyzipay\Model\ThreedsPayment
    {
        parent::setOptions();
        $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPaymentId($paymentId);
        $request->setConversationData("conversation data");
        $result =  \Iyzipay\Model\ThreedsPayment::create($request, $this->options);
        if ($result->getStatus() != "success") throw new \Exception($result->getErrorMessage(),$result->getErrorCode());
        return $result;
    }
}
