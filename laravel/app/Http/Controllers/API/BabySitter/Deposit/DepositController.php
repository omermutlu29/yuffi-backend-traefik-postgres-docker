<?php

namespace App\Http\Controllers\API\BabySitter\Deposit;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\DepositPayRequest;
use App\Models\BabySitter;
use App\Models\BabySitterDeposit;
use App\Services\DepositService\DepositService;
use Illuminate\Support\Facades\Auth;


class DepositController extends BaseController
{
    private $depositService;

    public function __construct(DepositService $depositService)
    {
        //$this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step']);
        $this->depositService=$depositService;
    }

    public function deposit()
    {
        $baby_sitter = Auth::user();
        if ($baby_sitter->deposit < 30) {
            return $this->sendResponse(false, 30 - $baby_sitter->deposit . ' TL Ödemeniz bulunmaktadır.');
        } else {
            return $this->sendResponse(true, 'Ödemeniz bulunmamaktadır.');
        }
    }

    public function pay(DepositPayRequest $request)
    {
        try {
            $babySitter= BabySitter::find(8);
            $cardInformation = $request->only('cardHolderName', 'cardNumber', 'cvc', 'expireMonth', 'expireYear');
            if ($this->depositService->pay($babySitter, $cardInformation) == 'success'){
               return $this->sendResponse(true,'Ödemeniz başarı ile geçekleşti');
            }
            return $this->sendError('Error','Ödemeniz alınırken bir hata ile karşılaşıldı');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function depositPay3d()
    {
        $price = 30 - Auth::user()->deposit;
        $baby_sitter = Auth::user();
        if ($price > 1) {
            $baby_sitter_deposit = new BabySitterDeposit();
            $baby_sitter_deposit->baby_sitter_id = $baby_sitter->id;
            $baby_sitter_deposit->price = $price;
            $baby_sitter_deposit->save();
            $result = $this->pay_3d($baby_sitter_deposit->id, $price, $baby_sitter, 'Depozito');

            if ($result->getStatus() == 'success') {
                $success['result'] = 'Bankaya yönlendiriliyorsunuz...';
                //$success['threeds'] = view("threeds.success")->render();
                $success['threedsPage'] = $result->getHtmlContent();
                return $this->sendResponse($success, 'Ödeme Sayfası!');
            } else {
                $baby_sitter_deposit->status = 0;
                $baby_sitter_deposit->raw_result = $result->getRawResult();
                $baby_sitter_deposit->save();
                return $this->sendError($result->getErrorCode(), $result->getErrorMessage(), 400);
            }

        } else {
            return $this->sendError('Depozit :' . $baby_sitter->deposit, 'Herhangi bir ödeme yapmanıza gerek yoktur.', 400);
        }
    }


}
