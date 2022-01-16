<?php


namespace App\Services\PaymentServices\Iyzico;


use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentService;
use Iyzipay\Model\Address;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

class IyzicoThreeDPaymentService extends IyzicoBaseService implements IThreeDPaymentService, IPayToSubMerchantService
{
    public function __construct(Options $options, CreatePaymentRequest $createPaymentRequest, PaymentCard $paymentCard, Buyer $buyer, Address $address)
    {
        parent::__construct($options, $createPaymentRequest, $paymentCard, $buyer, $address);
    }

    public function initializeThreeDPayment(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId, string $callbackUrl): \Iyzipay\Model\ThreedsInitialize
    {
        parent::setOptions();
        parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->paymentRequest->setCallbackUrl(env('IYZICO_CALLBACK_URL'));//For ThreeD
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
        return \Iyzipay\Model\ThreedsInitialize::create($this->paymentRequest, $this->options);
    }

    public function payToSubMerchant(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId, string $subMerchantKey, float $subMerchantPrice): \Iyzipay\Model\ThreedsInitialize
    {
        parent::setOptions();
        parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->paymentRequest->setCallbackUrl(env('IYZICO_CALLBACK_URL'));//For ThreeD
        parent::createPaymentCard($cardInformation);
        parent::createBuyer($buyerInformation);
        parent::createBillingAddress($addressInformation);
        parent::createShippingAddress($addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = parent::generateBasketItemForSubMerchant(
                $subMerchantKey,
                $subMerchantPrice,
                $product['id'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
        }
        $this->paymentRequest->setBasketItems($basketItems);
        return \Iyzipay\Model\ThreedsInitialize::create($this->paymentRequest, $this->options);
    }

    public function completeThreeDPayment(string $conversationId, string $paymentId, ?string $conversationData): \Iyzipay\Model\ThreedsPayment
    {
        parent::setOptions();
        $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPaymentId($paymentId);
        $request->setConversationData("conversation data");
        return \Iyzipay\Model\ThreedsPayment::create($request, $this->options);
    }

}
