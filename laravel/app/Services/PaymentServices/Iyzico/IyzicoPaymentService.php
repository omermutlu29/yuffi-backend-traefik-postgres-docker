<?php


namespace App\Services\PaymentServices\Iyzico;

use App\Interfaces\PaymentInterfaces\IPaymentWithRegisteredCard;


class IyzicoPaymentService extends IyzicoBaseService implements IPaymentWithRegisteredCard
{
    public function payWithRegisteredCardForVirtualProducts(
        string $cardToken,
        string $cardUserKey,
        array $products,
        array $addressInformation,
        array $buyerInformation,
        float $totalPrice,
        int $conversationId)
    {
        $this->setOptions();
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

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardToken($cardToken);
        $paymentCard->setCardUserKey($cardUserKey);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

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
        $request->setBuyer($buyer);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($addressInformation['full_name']);
        $billingAddress->setCity($addressInformation['city']);
        $billingAddress->setCountry($addressInformation['country']);
        $billingAddress->setAddress($addressInformation['address']);
        $billingAddress->setZipCode($addressInformation['zip_code']);
        $request->setBillingAddress($billingAddress);

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
        $request->setBasketItems($basketItems);
        $payment = \Iyzipay\Model\Payment::create($request, $this->options);
        \Illuminate\Support\Facades\Log::info($payment->getRawResult());
        return $payment->getRawResult();
    }
}
