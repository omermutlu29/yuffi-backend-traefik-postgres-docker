<?php


namespace App\Services\PaymentServices;


use App\Interfaces\PaymentInterfaces\ICardStoreService;
use App\Interfaces\PaymentInterfaces\IPayableToSubMerchant;
use App\Interfaces\PaymentInterfaces\IPayment;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

class IyzicoTestService implements IPayment, ICardStoreService, IPayableToSubMerchant
{
    private $apiKey;
    private $secretKey;
    private $baseUrl;

    private $options;
    private $paymentRequest;
    private $paymentCard;
    private $buyer;

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

    private function setOptions()
    {
        $this->options->setApiKey($this->apiKey);
        $this->options->setSecretKey($this->secretKey);
        $this->options->setBaseUrl($this->baseUrl);
    }

    private function createPaymentRequest(float $totalPrice, int $installment, $conversationID, $currency)
    {
        $this->paymentRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $this->paymentRequest->setConversationId($conversationID);
        $this->paymentRequest->setPrice($totalPrice);
        $this->paymentRequest->setPaidPrice($totalPrice);
        $this->paymentRequest->setCurrency($currency);
        $this->paymentRequest->setInstallment($installment);
        $this->paymentRequest->setBasketId($conversationID);
        $this->paymentRequest->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $this->paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
    }

    private function createPaymentCard(array $cardInformation)
    {
        $this->paymentCard->setCardHolderName($cardInformation['cardHolderName']);
        $this->paymentCard->setCardNumber($cardInformation['cardNumber']);
        $this->paymentCard->setExpireMonth($cardInformation['expireMonth']);
        $this->paymentCard->setExpireYear($cardInformation['expireYear']);
        $this->paymentCard->setCvc($cardInformation['cvc']);
        $this->paymentCard->setRegisterCard(isset($cardInformation['save']) ? 1 : 0);
        $this->paymentRequest->setPaymentCard($this->paymentCard);
    }

    private function createBuyer(array $buyerInformation)
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

    private function createBillingAddress(array $addressInformation)
    {
        $this->address->setContactName($addressInformation['contact_name']);
        $this->address->setCity($addressInformation['city']);
        $this->address->setCountry($addressInformation['country']);
        $this->address->setAddress($addressInformation['address']);
        $this->address->setZipCode($addressInformation['zip_code']);
        $this->paymentRequest->setBillingAddress($this->address);
    }

    public function payToMerchant(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId): \Iyzipay\Model\Payment
    {
        $this->setupForPayment($totalPrice, $installment, $conversationId, $currency, $cardInformation, $buyerInformation, $addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = $this->generateBasketItemMerchant(
                $product['id'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
        }
        $this->paymentRequest->setBasketItems($basketItems);
        return \Iyzipay\Model\Payment::create($this->paymentRequest, $this->options);
    }

    public function payToSubMerchant(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        string $currency,
        int $installment,
        int $conversationId,
        string $subMerchantKey,
        float $subMerchantPrice,
    )
    {
        $this->setupForPayment($totalPrice, $installment, $conversationId, $currency, $cardInformation, $buyerInformation, $addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = $this->generateBasketItemForSubMerchant(
                $subMerchantKey,
                $subMerchantPrice,
                $product['id'],
                $product['name'],
                $product['category'],
                $product['price'],
            );
        }
        $this->paymentRequest->setBasketItems($basketItems);
        return \Iyzipay\Model\Payment::create($this->paymentRequest, $this->options);
    }


    private function setupForPayment($totalPrice, $installment, $conversationId, $currency, $cardInformation, $buyerInformation, $addressInformation)
    {
        $this->setOptions();
        $this->createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->createPaymentCard($cardInformation);
        $this->createBuyer($buyerInformation);
        $this->createBillingAddress($addressInformation);
    }

    public static function generateBasketItemMerchant(
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice)
    {
        $basketItem = new BasketItem();
        $basketItem->setId($basketItemId);
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1($basketItemCategory);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($basketItemPrice);
        return $basketItem;
    }

    public static function generateBasketItemForSubMerchant(
        string $subMerchantKey,
        float $subMerchantPrice,
        int $basketItemId,
        string $basketItemName,
        string $basketItemCategory,
        float $basketItemPrice)
    {
        $basketItem = new BasketItem();
        $basketItem->setSubMerchantKey($subMerchantKey);
        $basketItem->setSubMerchantPrice($subMerchantPrice);
        $basketItem->setId($basketItemId);
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1($basketItemCategory);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($basketItemPrice);
        return $basketItem;
    }

    public function storeCard(array $cardInformation)
    {
        // TODO: Implement storeCard() method.
    }

    public function deleteCard()
    {
        // TODO: Implement deleteCard() method.
    }

}
