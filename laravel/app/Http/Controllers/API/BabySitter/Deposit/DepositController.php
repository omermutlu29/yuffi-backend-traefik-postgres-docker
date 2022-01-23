<?php

namespace App\Http\Controllers\API\BabySitter\Deposit;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\DepositPayRequest;
use App\Interfaces\DepositService\IDeposit;
use Illuminate\Http\Request;

class DepositController extends BaseController
{
    private IDeposit $depositService;

    public function __construct(IDeposit $depositService)
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step']);
        $this->depositService = $depositService;
    }

    public function deposit()
    {
        $baby_sitter = \auth()->user();
        if ($baby_sitter->deposit < 30) {
            return $this->sendResponse(false, 30 - $baby_sitter->deposit . ' TL Ödemeniz bulunmaktadır.');
        } else {
            return $this->sendResponse(true, 'Ödemeniz bulunmamaktadır.');
        }
    }

    public function pay(DepositPayRequest $request)
    {
        try {
            $babySitter = \auth()->user();
            if ($babySitter->deposit == 30) {
                return $this->sendError('Error', 'Depozitonuz zaten ödenmiş görünüyor');
            }
            $cardInformation = $request->only('cardHolderName', 'cardNumber', 'cvc', 'expireMonth', 'expireYear');
            $paymentResult = $this->depositService->pay($babySitter, $cardInformation);
            if ($paymentResult['status'] != 'success') {
                return $this->sendError($paymentResult['errorCode'], $paymentResult['errorMessage']);
            }
            return $this->sendResponse(true, 'Ödemeniz başarı ile alındı.');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function depositPay3d(DepositPayRequest $request)
    {
        try {
            $babySitter = \auth()->user();
            if ($babySitter->deposit == 30) {
                return $this->sendError('Error', 'Depozitonuz zaten ödenmiş görünüyor');
            }
            $cardInformation = $request->only('cardHolderName', 'cardNumber', 'cvc', 'expireMonth', 'expireYear');
            $paymentResult = $this->depositService->payThreeD($babySitter, $cardInformation);
            if ($paymentResult['status'] != 'success') {
                return $this->sendError($paymentResult['errorCode'], $paymentResult['errorMessage']);
            }
            $success['result'] = 'Bankaya yönlendiriliyorsunuz...';
            $success['threeds'] = view("threeds.success")->render();
            $success['threedsPage'] = $paymentResult['htmlContent'];
            return $this->sendResponse($success, 'Ödeme Sayfası!');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function threeDComplete(Request $request)
    {
        $paymentResult = $this->depositService->completeThreeD($request->only('mdStatus', 'status', 'paymentId', 'conversationData', 'conversationId'));
        if ($paymentResult['status'] != 'success') {
            return $this->sendError($paymentResult['errorCode'], $paymentResult['errorMessage']);
        }
        return $this->sendError($paymentResult['errorCode'], $paymentResult['errorMessage']);
    }


}
