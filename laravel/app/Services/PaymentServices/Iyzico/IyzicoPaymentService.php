<?php


namespace App\Services\PaymentServices\Iyzico;

use App\Interfaces\PaymentInterfaces\IPayment;


class IyzicoPaymentService extends IyzicoBaseService implements IPayment
{

    public function pay(
        array $cardInformation,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        int $conversationId)
    {
        $this->setOptions();
        $request = self::createPaymentRequest($conversationId, $totalPrice);

        $paymentCard = self::createPaymentCard($cardInformation);
        $request->setPaymentCard($paymentCard);

        $buyer = self::createBuyer($buyerInformation);
        $request->setBuyer($buyer);

        $billingAddress = self::createBillingAddress($addressInformation);
        $request->setBillingAddress($billingAddress);

        $products = self::createBasketItems($products);
        $request->setBasketItems($products);
        $payment = \Iyzipay\Model\Payment::create($request, $this->options);
        return $payment;
    }

    private static function createPaymentRequest($conversationId, $totalPrice): \Iyzipay\Request\CreatePaymentRequest
    {
        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPrice((float)$totalPrice);
        $request->setPaidPrice((float)$totalPrice);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($conversationId);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        return $request;
    }

    private static function createBuyer($buyerInformation): \Iyzipay\Model\Buyer
    {
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($buyerInformation['id']);
        $buyer->setName($buyerInformation['name']);
        $buyer->setSurname($buyerInformation['surname']);
        $buyer->setGsmNumber($buyerInformation['phone']);
        $buyer->setEmail($buyerInformation['email']);
        $buyer->setIdentityNumber($buyerInformation['tc']);
        $buyer->setLastLoginDate($buyerInformation['updated_at']);
        $buyer->setRegistrationDate($buyerInformation['created_at']);
        $buyer->setRegistrationAddress($buyerInformation['address']);
        $buyer->setIp($buyerInformation['ip']);
        $buyer->setCity($buyerInformation['city']);
        $buyer->setCountry($buyerInformation['country']);
        $buyer->setZipCode($buyerInformation['zip_code']);
        return $buyer;
    }

    private static function createBillingAddress($addressInformation): \Iyzipay\Model\Address
    {
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($addressInformation['full_name']);
        $billingAddress->setCity($addressInformation['city']);
        $billingAddress->setCountry($addressInformation['country']);
        $billingAddress->setAddress($addressInformation['address']);
        $billingAddress->setZipCode($addressInformation['zip_code']);
        return $billingAddress;
    }

    private static function createBasketItems($products): array
    {
        $basketItems = [];
        foreach ($products as $product) {
            $newProduct = new \Iyzipay\Model\BasketItem();
            $newProduct->setId($product['id']);
            $newProduct->setName($product['name']);
            $newProduct->setCategory1($product['category']);
            $newProduct->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
            $newProduct->setPrice((float)$product['price']);
            $basketItems[] = $newProduct;
        }
        return $basketItems;
    }

    private static function createPaymentCard($cardInformation): \Iyzipay\Model\PaymentCard
    {
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        if (
            isset($cardInformation['cardHolderName']) &&
            isset($cardInformation['cardNumber']) &&
            isset($cardInformation['expireMonth']) &&
            isset($cardInformation['expireYear']) &&
            isset($cardInformation['cvc']) &&
            isset($cardInformation['registerCard'])
        ) {
            $paymentCard->setCardNumber($cardInformation['cardNumber']);
            $paymentCard->setCardHolderName($cardInformation['cardHolderName']);
            $paymentCard->setExpireMonth($cardInformation['expireMonth']);
            $paymentCard->setExpireYear($cardInformation['expireYear']);
            $paymentCard->setCvc($cardInformation['cvc']);
            if (isset($cardInformation['cardUserKey'])) {
                $paymentCard->setRegisterCard($cardInformation['registerCard']);
                $paymentCard->setCardUserKey($cardInformation['cardUserKey']);
            }
        }

        if (isset($cardInformation['cardUserKey']) && isset($cardInformation['cardToken'])) {
            $paymentCard->setCardToken($cardInformation['cardToken']);
            $paymentCard->setCardUserKey($cardInformation['cardUserKey']);
        }

        return $paymentCard;

    }

}
