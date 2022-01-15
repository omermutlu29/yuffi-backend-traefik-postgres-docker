<?php


namespace App\Services;


use Iyzipay\Model\Buyer;
use Iyzipay\Options;
use Iyzipay\Request;

class IyzicoService
{
    private $apiKey = "sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8";
    private $secretKey = "sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh";
    private $baseUrl = "https://sandbox-api.iyzipay.com";
    private Options $options;
    private $conversationId;
    private Request $request;
    private $cardData;
    private Buyer $buyer;
    private $totalPrice;

    /**
     * IyzicoService constructor.
     * @param $conversationId
     * @param $totalPrice
     * @param $cardData
     * @param $buyer: {'id','name','surname',phone,email,tc,address,country,city}
     * @param $address : {'name,surname,address,city,country}
     * @param $shipping : {'name,surname,address,city,country} or null
     */
    public function __construct($conversationId, $totalPrice, $cardData, $buyer, $address, $shipping=null)
    {
        $this->totalPrice=$totalPrice;
        $this->setOptions();
        $this->createPaymentMethod($conversationId, $totalPrice);
        $this->setCardData($cardData);
        $this->setBuyer($buyer->id,
            $buyer->name,
            $buyer->surname,
            $buyer->phone,
            $buyer->email,
            $buyer->tc,
            $buyer->last_login,
            $buyer->address,
            $buyer->country,
            $buyer->city
        );
        $this->request->setShippingAddress($this->createAddress($address->name_surname,$address->address,$address->city,$address->country));
        if ($shipping == null){
            $this->request->setBillingAddress($this->createAddress($address->name_surname,$address->address,$address->city,$address->country));
        }else{
            $this->request->setBillingAddress($this->createAddress($shipping->name_surname,$shipping->address,$shipping->city,$shipping->country));
        }


    }

    protected function setOptions()
    {
        $this->options = new Options();
        $this->options->setApiKey($this->apiKey);
        $this->options->setSecretKey($this->secretKey);
        $this->options->setBaseUrl($this->baseUrl);
    }

    protected function createPaymentMethod($conversationID, $totalPrice)
    {
        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationID);
        $request->setPrice($totalPrice);
        $request->setPaidPrice($totalPrice);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($conversationID);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $this->request = $request;
    }

    /**
     * @param $cardData
     * Eğer cardData objesinin içersinde register özelliği true gönderilirse kart kaydedilir
     */
    protected function setCardData($cardData)
    {
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($cardData->cardHolderName);
        $paymentCard->setCardNumber($cardData->cardNumber);
        $paymentCard->setExpireMonth($cardData->expireMonth);
        $paymentCard->setExpireYear($cardData->expireYear);
        $paymentCard->setCvc($cardData->cvc);
        $paymentCard->setRegisterCard($cardData->register ? 1 : 0);
        $this->request->setPaymentCard($paymentCard);
    }

    protected function setBuyer($id,$name,$surname,$phone,$email,$tc,$last_login,$address,$country,$city){
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($id);
        $buyer->setName($name);
        $buyer->setSurname($surname);
        $buyer->setGsmNumber($phone);
        $buyer->setEmail($email);
        $buyer->setIdentityNumber($tc);
        $buyer->setLastLoginDate($last_login);
        $buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $buyer->setRegistrationAddress($address);
        $buyer->setIp(\request()->getClientIp());
        $buyer->setCity($city);
        $buyer->setCountry($country);
        $buyer->setZipCode($city);
        $this->request->setBuyer($buyer);
    }

    protected function createAddress($name_surname,$address,$city,$country){
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($name_surname);
        $shippingAddress->setCity($city);
        $shippingAddress->setCountry($country);
        $shippingAddress->setAddress($address);
        $shippingAddress->setZipCode($city);
        return $shippingAddress;
    }

    public function payToMerchant($subMerchant)
    {
        $basketItems = array();
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setSubMerchantKey($subMerchant);
        $basketItem->setSubMerchantPrice($this->totalPrice - 5);
        $basketItem->setId($this->conversationId);
        $basketItem->setName('Bakıcılık Hizmeti');
        $basketItem->setCategory1('Bakıcılık Hizmeti');
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($this->totalPrice);
        $basketItems[] = $basketItem;
        $this->request->setBasketItems($basketItems);
        return \Iyzipay\Model\Payment::create($this->request, $this->options);
    }




}
