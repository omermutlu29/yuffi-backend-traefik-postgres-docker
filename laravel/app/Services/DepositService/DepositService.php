<?php


namespace App\Services\DepositService;


use App\Http\Resources\AddressPaymentResource;
use App\Http\Resources\BabySitterPaymentResource;
use App\Interfaces\DepositService\IDeposit;
use App\Interfaces\PaymentInterfaces\IPaymentService;
use App\Models\BabySitter;
use App\Repositories\DepositRepository;

class DepositService implements IDeposit
{
    private $directPaymentService;
    private $depositRepository;

    public function __construct(IPaymentService $paymentService, DepositRepository $depositRepository)
    {
        $this->directPaymentService = $paymentService;
        $this->depositRepository = $depositRepository;
    }

    public function pay(BabySitter $babySitter, array $cardInformation) : array
    {
        try {

            $buyer = new BabySitterPaymentResource($babySitter);
            $address = new AddressPaymentResource($babySitter);
            $address = $address->toArray($babySitter);
            $buyer = $buyer->toArray($babySitter);
            $totalPrice = 30 - $babySitter->deposit;
            $currency = 'TRY';
            $installment = 1;
            $babySitterDeposit = $this->depositRepository->create(['price' => 30, 'baby_sitter_id' => $babySitter->id, 'raw_result' => '{}', 'status' => false]);
            $conversationId = $babySitterDeposit->id;
            $products = [['id' => $babySitterDeposit->id, 'name' => 'Depozit', 'category' => 'Depozit', 'price' => '30.00']];
            $result = $this->directPaymentService->pay($cardInformation, $products, $address, $buyer, $totalPrice, $currency, $installment, $conversationId);
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
