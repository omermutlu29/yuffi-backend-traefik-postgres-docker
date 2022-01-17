<?php


namespace App\Services\PaymentServices\Iyzico;

use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

abstract class IyzicoBaseService
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    protected $options;
    protected $paymentRequest;
    protected $paymentCard;
    protected $buyer;

    public function __construct(
        Options $options,
        CreatePaymentRequest $createPaymentRequest,
        PaymentCard $paymentCard,
        Buyer $buyer,
        Address $address
    )
    {
        $this->apiKey = env('IYZICO_API_KEY');
        $this->secretKey = env('IYZICO_SECRET_KEY');
        $this->baseUrl = env('IYZICO_BASEURL');
        $this->options = $options;
        $this->paymentRequest = $createPaymentRequest;
        $this->paymentCard = $paymentCard;
        $this->buyer = $buyer;
        $this->address = $address;
    }

    protected function setOptions()
    {
        $this->options->setApiKey($this->apiKey);
        $this->options->setSecretKey($this->secretKey);
        $this->options->setBaseUrl($this->baseUrl);
    }

    protected function createPaymentRequest(float $totalPrice, int $installment, $conversationID, $currency)
    {
        $this->paymentRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $this->paymentRequest->setConversationId($conversationID);
        $this->paymentRequest->setPrice($totalPrice);
        $this->paymentRequest->setPaidPrice($totalPrice);
        $this->paymentRequest->setCurrency($currency);
        $this->paymentRequest->setInstallment($installment);
        $this->paymentRequest->setBasketId($conversationID);
        $this->paymentRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        /**
         * if user wants to pay with threeD user can add below method
         * $this->paymentRequest->setCallback('example.com');
         */
    }

    protected function createPaymentCard(array $cardInformation)
    {
        $this->paymentCard->setCardHolderName($cardInformation['cardHolderName']);
        $this->paymentCard->setCardNumber($cardInformation['cardNumber']);
        $this->paymentCard->setExpireMonth($cardInformation['expireMonth']);
        $this->paymentCard->setExpireYear($cardInformation['expireYear']);
        $this->paymentCard->setCvc($cardInformation['cvc']);
        $this->paymentCard->setRegisterCard(0);
        $this->paymentRequest->setPaymentCard($this->paymentCard);
        /**
         * if user want to pay with registered card, this method can change
         */
    }

    protected function createBuyer(array $buyerInformation)
    {
        $this->buyer->setId((string)$buyerInformation['id']);
        $this->buyer->setName($buyerInformation['name']);
        $this->buyer->setSurname($buyerInformation['surname']);
        $this->buyer->setGsmNumber($buyerInformation['phoneNumber']);
        $this->buyer->setEmail($buyerInformation['email']);
        $this->buyer->setIdentityNumber($buyerInformation['identity']);
        $this->buyer->setLastLoginDate(date('Y-m-d H:m:s', time()));
        $this->buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $this->buyer->setRegistrationAddress($buyerInformation['address']);
        $this->buyer->setIp(\request()->getClientIp());
        $this->buyer->setCity($buyerInformation["city"]);
        $this->buyer->setCountry($buyerInformation['country']);
        $this->buyer->setZipCode($buyerInformation['zip_code']);
        $this->paymentRequest->setBuyer($this->buyer);
    }

    protected function createBillingAddress(array $addressInformation)
    {
        $this->address->setContactName($addressInformation['contact_name']);
        $this->address->setCity($addressInformation['city']);
        $this->address->setCountry($addressInformation['country']);
        $this->address->setAddress($addressInformation['address']);
        $this->address->setZipCode($addressInformation['zip_code']);
        $this->paymentRequest->setBillingAddress($this->address);
    }

    protected function createShippingAddress(array $addressInformation)
    {
        $this->address->setContactName($addressInformation['contact_name']);
        $this->address->setCity($addressInformation['city']);
        $this->address->setCountry($addressInformation['country']);
        $this->address->setAddress($addressInformation['address']);
        $this->address->setZipCode($addressInformation['zip_code']);
        $this->paymentRequest->setShippingAddress($this->address);
    }

    protected static function generateBasketItemMerchant(
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice): BasketItem
    {
        $basketItem = new BasketItem();
        $basketItem->setId($basketItemId);
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1($basketItemCategory);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($basketItemPrice);
        return $basketItem;
    }

    protected static function generateBasketItemForSubMerchant(
        string $subMerchantKey,
        float $subMerchantPrice,
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice): BasketItem
    {
        $basketItem = new BasketItem();
        $basketItem->setSubMerchantKey($subMerchantKey);
        $basketItem->setSubMerchantPrice($subMerchantPrice);
        $basketItem->setId($basketItemId);
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1($basketItemCategory);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $basketItem->setPrice($basketItemPrice);
        return $basketItem;
    }
}
