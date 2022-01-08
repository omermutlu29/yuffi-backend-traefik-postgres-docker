<?php


namespace App\Http\Controllers\API\BabySitter\Deposit;


use App\Models\BabySitter;
use App\Models\BabySitterDeposit;
use App\Http\Controllers\API\BaseController;


class PaymentController extends BaseController
{
    private $apiKey = "sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8";
    private $secretKey = "sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh";
    private $baseUrl = "https://sandbox-api.iyzipay.com";


    public function pay($conversationId, $totalPrice = null, BabySitter $babySitter, $productType, $cardInfos)
    {

        $options = new \Iyzipay\Options();
        $options->setApiKey($this->apiKey);
        $options->setSecretKey($this->secretKey);
        $options->setBaseUrl($this->baseUrl);


        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPrice($totalPrice);
        $request->setPaidPrice($totalPrice);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($conversationId);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($cardInfos['cardHolderName']);
        $paymentCard->setCardNumber($cardInfos['cardNumber']);
        $paymentCard->setExpireMonth($cardInfos['expireMonth']);
        $paymentCard->setExpireYear($cardInfos['expireYear']);
        $paymentCard->setCvc($cardInfos['cvc']);
        $paymentCard->setRegisterCard($cardInfos['registerCard']);
        $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId((string)$babySitter->id);
        $buyer->setName($babySitter->name);
        $buyer->setSurname($babySitter->surname);
        $buyer->setGsmNumber($babySitter->phone);
        $buyer->setEmail($babySitter->email);
        $buyer->setIdentityNumber($babySitter->tc);
        $buyer->setLastLoginDate($babySitter->last_login);
        $buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $buyer->setRegistrationAddress($babySitter->address);
        $buyer->setIp(\request()->getClientIp());
        $buyer->setCity("İstanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("İstanbul");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($babySitter->name . ' ' . $babySitter->surname);
        $shippingAddress->setCity("İstanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress($babySitter->address);
        $shippingAddress->setZipCode("İstanbul");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($babySitter->name . ' ' . $babySitter->surname);
        $billingAddress->setCity("İstanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($babySitter->address);
        $billingAddress->setZipCode("İstanbul");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();

        $basketItem = new \Iyzipay\Model\BasketItem();
        //$basketItem->setSubMerchantKey('ou2gqr+NsKmmkUpDaneKRRkVW4k=');
        //$basketItem->setSubMerchantPrice(15);
        $basketItem->setId($conversationId);
        $basketItem->setName($productType);
        $basketItem->setCategory1($productType);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($totalPrice);
        $basketItems[] = $basketItem;
        $request->setBasketItems($basketItems);

        return \Iyzipay\Model\Payment::create($request, $options);
    }

    public function pay_3d($conversationId, $totalPrice = null, BabySitter $babySitter, $productType)
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey($this->apiKey);
        $options->setSecretKey($this->secretKey);
        $options->setBaseUrl($this->baseUrl);
        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPrice($totalPrice);
        $request->setPaidPrice($totalPrice);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($conversationId);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
        $request->setCallbackUrl(env("APP_URL") . "/api/baby-sitter/deposit/pay_3d_complete");
        //KART YARAT
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setRegisterCard(0);
        $paymentCard->setCardHolderName("John Doe");
        $paymentCard->setCardNumber("5528790000000008");
        $paymentCard->setExpireMonth("12");
        $paymentCard->setExpireYear("2030");
        $paymentCard->setCvc("123");
        $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId((string)$babySitter->id);
        $buyer->setName($babySitter->name);
        $buyer->setSurname($babySitter->surname);
        $buyer->setGsmNumber($babySitter->phone);
        $buyer->setEmail($babySitter->email);
        $buyer->setIdentityNumber($babySitter->tc);
        $buyer->setLastLoginDate($babySitter->last_login);
        $buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $buyer->setRegistrationAddress($babySitter->address);
        $buyer->setIp(\request()->getClientIp());
        $buyer->setCity("İstanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("İstanbul");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($babySitter->name . ' ' . $babySitter->surname);
        $shippingAddress->setCity("İstanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress($babySitter->address);
        $shippingAddress->setZipCode("İstanbul");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($babySitter->name . ' ' . $babySitter->surname);
        $billingAddress->setCity("İstanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($babySitter->address);
        $billingAddress->setZipCode("İstanbul");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();

        $basketItem = new \Iyzipay\Model\BasketItem();
        //$basketItem->setSubMerchantKey('ou2gqr+NsKmmkUpDaneKRRkVW4k=');
        //$basketItem->setSubMerchantPrice(15);
        $basketItem->setId($conversationId);
        $basketItem->setName($productType);
        $basketItem->setCategory1($productType);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($totalPrice);
        $basketItems[] = $basketItem;
        $request->setBasketItems($basketItems);
        $threedsInitialize = \Iyzipay\Model\ThreedsInitialize::create($request, $options);
        return $threedsInitialize;
    }

    public function pay_3d_complete(Request $requesta)
    {
        if ($requesta->status == "success") {
            $options = new \Iyzipay\Options();
            $options->setApiKey($this->apiKey);
            $options->setSecretKey($this->secretKey);
            $options->setBaseUrl($this->baseUrl);

            $request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
            $request->setLocale(\Iyzipay\Model\Locale::TR);
            $request->setConversationId($requesta->get('conversationId'));
            $request->setPaymentId($requesta->get('paymentId'));
            $request->setConversationData($requesta->get('conversationData'));

            $threedsPayment = \Iyzipay\Model\ThreedsPayment::create($request, $options);

            $res = false;
            if ($threedsPayment->getStatus() == "success") {
                $res = true;
                $baby_sitter_deposit = BabySitterDeposit::query()->where("id", "=", $requesta->get('conversationId'))->first();
                $baby_sitter_deposit->status = 1;//Hazırlanıyor
                $baby_sitter_deposit->raw_result = $threedsPayment->getRawResult();
                $baby_sitter_deposit->save();

                //LOGLARI KAYDET
            } else {
                $baby_sitter_deposit = BabySitterDeposit::query()->where("id", "=", $requesta->get('conversationId'))->first();
                $baby_sitter_deposit->status = 0;//Hazırlanıyor
                $baby_sitter_deposit->raw_result = $threedsPayment->getRawResult();
                $baby_sitter_deposit->save();

                //LOGLARI KAYDET
            }

            if ($res) {
                $success['result'] = 'Ödeme işlemi başarı ile gerçekleştirildi!';
                return response()->view("threeds.success");
                return $this->sendResponse($success, 'Ödeme işlemi başarı ile gerçekleştirildi!');
            } else {
                return response()->view("threeds.error", ["error" => $threedsPayment->getErrorMessage()]);
                $success['result'] = 'Ödeme işlemi gerçekleştirilemedi!';
                return $this->sendResponse($success, 'Ödeme  işlemi gerçekleştirilemedi!');
            }
        } else {
            return response()->view("threeds.error", ["error" => "3D doğrulama gerçekleşemedi."]);
            return $this->sendError('Ödeme işlemi esnasında bir problemle karşılaşıldı!');
        }
    }

}
