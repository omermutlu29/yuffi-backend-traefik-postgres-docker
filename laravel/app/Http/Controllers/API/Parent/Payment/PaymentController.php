<?php


namespace App\Http\Controllers\API\Parent\Payment;


use App\Models\BabySitter;
use App\Models\BabySitterDeposit;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;


class PaymentController extends BaseController
{
    private $apiKey = "sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8";
    private $secretKey = "sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh";
    private $baseUrl = "https://sandbox-api.iyzipay.com";


    public function pay($conversationId, $totalPrice = null, BabySitter $babySitter,$cardData)
    {
        $parent=Auth::user();
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
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($cardData->cardHolderName);
        $paymentCard->setCardNumber($cardData->cardNumber);
        $paymentCard->setExpireMonth($cardData->expireMonth);
        $paymentCard->setExpireYear($cardData->expireYear);
        $paymentCard->setCvc($cardData->cvc);
        $paymentCard->setRegisterCard(1);
        $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($parent->id);
        $buyer->setName($parent->name);
        $buyer->setSurname($parent->surname);
        $buyer->setGsmNumber($parent->phone);
        $buyer->setEmail($parent->email);
        $buyer->setIdentityNumber($parent->tc);
        $buyer->setLastLoginDate($parent->last_login);
        $buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $buyer->setRegistrationAddress($parent->address);
        $buyer->setIp(\request()->getClientIp());
        $buyer->setCity("İstanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("İstanbul");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($parent->name . ' ' . $parent->surname);
        $shippingAddress->setCity("İstanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress($parent->address);
        $shippingAddress->setZipCode("İstanbul");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($parent->name . ' ' . $parent->surname);
        $billingAddress->setCity("İstanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($parent->address);
        $billingAddress->setZipCode("İstanbul");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();

        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setSubMerchantKey($babySitter->sub_merchant);

        $basketItem->setSubMerchantPrice($totalPrice-5);
        $basketItem->setId($conversationId);
        $basketItem->setName('Bakıcılık Hizmeti');
        $basketItem->setCategory1('Bakıcılık Hizmeti');
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($totalPrice);
        $basketItems[] = $basketItem;
        $request->setBasketItems($basketItems);

        return \Iyzipay\Model\Payment::create($request, $options);
    }

    public function pay_3d($conversationId, $totalPrice = null, BabySitter $babySitter, $productType,$cardInformation)
    {
        $authUser=Auth::user();
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
        //Düzeltilecek
        $request->setCallbackUrl(env("APP_URL") . "/api/baby-sitter/deposit/pay_3d_complete");
        //KART YARAT
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setRegisterCard(0);
        $paymentCard->setCardHolderName($cardInformation->cardHolderName);
        $paymentCard->setCardNumber($cardInformation->cardNumber);
        $paymentCard->setExpireMonth($cardInformation->expireMonth);
        $paymentCard->setExpireYear($cardInformation->expireYear);
        $paymentCard->setCvc($cardInformation->cvc);
        $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId((string)$authUser->id);
        $buyer->setName($authUser->name);
        $buyer->setSurname($authUser->surname);
        $buyer->setGsmNumber($authUser->phone);
        $buyer->setEmail($authUser->email);
        $buyer->setIdentityNumber($authUser->tc);
        $buyer->setLastLoginDate($authUser->last_login);
        $buyer->setRegistrationDate(date('Y-m-d H:m:s', time()));
        $buyer->setRegistrationAddress($authUser->address);
        $buyer->setIp(\request()->getClientIp());
        $buyer->setCity("İstanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("İstanbul");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($authUser->name . ' ' . $authUser->surname);
        $shippingAddress->setCity("İstanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress($authUser->address);
        $shippingAddress->setZipCode("İstanbul");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($authUser->name . ' ' . $authUser->surname);
        $billingAddress->setCity("İstanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($authUser->address);
        $billingAddress->setZipCode("İstanbul");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();

        $basketItem = new \Iyzipay\Model\BasketItem();
        //$basketItem->setSubMerchantKey($babySitter->sub_merchant);
        //$basketItem->setSubMerchantPrice($totalPrice*0.9);
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


