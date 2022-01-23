<?php


namespace App\Services\PaymentServices\Iyzico;


use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Interfaces\PaymentInterfaces\IPayToSubMerchantService;
use Iyzipay\Model\Address;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

class IyzicoDirectPaymentService extends IyzicoPaymentBaseService implements IPaymentService, IPayToSubMerchantService
{

    public function pay(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId): \Iyzipay\Model\Payment
    {
        try {
            parent::setOptions();
            parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
            $this->paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
            parent::createPaymentCard($cardInformation);
            parent::createBuyer($buyerInformation);
            parent::createBillingAddress($addressInformation);
            parent::createShippingAddress($addressInformation);
            $basketItems = [];
            foreach ($products as $product) {
                $basketItems[] = self::generateBasketItemMerchant($product['id'], $product['name'], $product['category'], $product['price'],
                );
            }
            $this->paymentRequest->setBasketItems($basketItems);
            return \Iyzipay\Model\Payment::create($this->paymentRequest, $this->options);

        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function payToSubMerchant(array $cardInformation, array $products, array $addressInformation, array $buyerInformation, float $totalPrice, string $currency, int $installment, int $conversationId, string $subMerchant, float $subMerchantPrice,?string $callback): \Iyzipay\Model\Payment
    {
        parent::setOptions();
        parent::createPaymentRequest($totalPrice, $installment, $conversationId, $currency);
        $this->paymentRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        parent::createPaymentCard($cardInformation);
        parent::createBuyer($buyerInformation);
        parent::createBillingAddress($addressInformation);
        parent::createShippingAddress($addressInformation);
        $basketItems = [];
        foreach ($products as $product) {
            $basketItems[] = parent::generateBasketItemForSubMerchant($subMerchant, $subMerchantPrice, $product['id'], $product['name'], $product['category'], $product['price']);
        }
        $this->paymentRequest->setBasketItems($basketItems);
        return \Iyzipay\Model\Payment::create($this->paymentRequest, $this->options);
    }

}
