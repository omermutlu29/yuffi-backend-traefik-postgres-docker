<?php


namespace App\Services\DepositService;


use App\Http\Resources\AddressPaymentResource;
use App\Http\Resources\BabySitterPaymentResource;
use App\Interfaces\DepositService\IDepositService;
use App\Interfaces\PaymentInterfaces\ICompleteThreeDPayment;
use App\Interfaces\PaymentInterfaces\IPayment;
use App\Interfaces\PaymentInterfaces\IThreeDPaymentInitialize;
use App\Models\BabySitter;
use App\Models\BabySitterDeposit;
use App\Repositories\DepositRepository;

class DepositServiceService implements IDepositService
{
    const CURRENCY = 'TRY';
    const INSTALLMENT = 1;
    const DEPOSIT = 30;
    const MD_STATUSES = [
        '0' => '3-D Secure imzası geçersiz veya doğrulama',
        '2' => 'Kart sahibi veya bankası sisteme kayıtlı değil',
        '3' => 'Kartın bankası sisteme kayıtlı değil',
        '4' => 'Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş',
        '5' => 'Doğrulama yapılamıyor',
        '6' => '3-D Secure hatası',
        '7' => 'Sistem hatası',
        '8' => 'Bilinmeyen kart no',
    ];

    private IPayment $directPaymentService;
    private IThreeDPaymentInitialize $threeDPaymentService;
    private ICompleteThreeDPayment $completeThreeDPayment;
    private DepositRepository $depositRepository;
    private $cardInformation;

    private $buyer;
    private $products = [['id' => null, 'name' => 'Depozit', 'category' => 'Depozit', 'price' => null]];
    private $address;
    private $totalPrice;

    private function prepareDataForPayment(BabySitter $babySitter, array $cardInformation)
    {
        $buyer = new BabySitterPaymentResource($babySitter);
        $address = new AddressPaymentResource($babySitter);
        $this->address = $address->toArray($babySitter);
        $this->buyer = $buyer->toArray($babySitter);
        $this->totalPrice = self::DEPOSIT - $babySitter->deposit;
        $this->cardInformation = $cardInformation;
        $this->products[0]['price'] = $this->totalPrice;
    }

    public function __construct(IPayment $paymentService, DepositRepository $depositRepository, IThreeDPaymentInitialize $threeDPaymentService,ICompleteThreeDPayment $completeThreeDPayment)
    {
        $this->completeThreeDPayment=$completeThreeDPayment;
        $this->directPaymentService = $paymentService;
        $this->depositRepository = $depositRepository;
        $this->threeDPaymentService = $threeDPaymentService;
    }

    public function pay(BabySitter $babySitter, array $cardInformation): array
    {
        try {
            self::prepareDataForPayment($babySitter, $cardInformation);
            $babySitterDeposit = $this->depositRepository->create(['price' => 30, 'baby_sitter_id' => $babySitter->id, 'raw_result' => '{}', 'status' => false]);
            $conversationId = $babySitterDeposit->id;
            $this->products[0]['id'] = $babySitterDeposit->id;
            $result = $this->directPaymentService->pay($cardInformation, $this->products, $this->address, $this->buyer, $this->totalPrice, self::CURRENCY, self::INSTALLMENT, $conversationId);
            $babySitterDeposit->update(
                [
                    'raw_result' => json_decode($result->getRawResult()),
                    'status' => $result->getStatus() == 'success' ? true : false
                ]
            );
            return ['status' => $result->getStatus(), 'errorCode' => $result->getErrorCode(), 'errorMessage' => $result->getErrorMessage()];
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function payThreeD(BabySitter $babySitter, array $cardInformation): array
    {
        try {
            self::prepareDataForPayment($babySitter, $cardInformation);
            $babySitterDeposit = $this->depositRepository->create(['price' => 30, 'baby_sitter_id' => $babySitter->id, 'raw_result' => '{}', 'status' => false]);
            $conversationId = $babySitterDeposit->id;
            $this->products[0]['id'] = $babySitterDeposit->id;
            $result = $this->threeDPaymentService->initializeThreeDPayment($cardInformation, $this->products, $this->address, $this->buyer, $this->totalPrice, self::CURRENCY, self::INSTALLMENT, $conversationId, route('babysitter.deposit.callback'));
            return ['status' => $result->getStatus(), 'errorCode' => $result->getErrorCode(), 'errorMessage' => $result->getErrorMessage(), 'htmlContent' => $result->getHtmlContent()];
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function completeThreeD(array $data): array
    {
        try {
            if (isset(self::MD_STATUSES[$data['mdStatus']])) {
                return ['status' => $data['status'], 'errorCode' => $data['mdStatus'], 'errorMessage' => self::MD_STATUSES[$data['mdStatus']]];
            }
            $result = $this->completeThreeDPayment->completeThreeDPayment($data['conversationId'], $data['paymentId'], $data['conversationData']);
            $babySitterDeposit=BabySitterDeposit::find($data['conversationId']);
            $babySitterDeposit->update(
                [
                    'raw_result' => json_decode($result->getRawResult()),
                    'status' => $result->getStatus() == 'success' ? true : false
                ]
            );
            return ['status' => $result->getStatus(), 'errorCode' => $result->getErrorCode(), 'errorMessage' => $result->getErrorMessage()];
        } catch (\Exception $exception) {
            throw $exception;
        }

    }
}
