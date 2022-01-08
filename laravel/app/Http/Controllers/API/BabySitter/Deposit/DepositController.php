<?php
/**
 * Created by PhpStorm.
 * User: o.mutlu
 * Date: 12/29/2019
 * Time: 6:47 PM
 */

namespace App\Http\Controllers\API\BabySitter\Deposit;


use App\Models\BabySitterDeposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class DepositController extends PaymentController
{
    public function __construct()
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step']);
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

    public function deposit_pay(\Illuminate\Http\Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cardHolderName' => 'required',
            'cardNumber' => 'required',
            'expireMonth' => 'required',
            'expireYear' => 'required',
            'cvc' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $cardInfos = $request->only(['cardHolderName', 'cvc', 'cardNumber', 'expireMonth', 'expireYear', 'registerCard']);
        $price = 30 - Auth::user()->deposit;
        $baby_sitter = Auth::user();
        if ($price > 1) {
            $baby_sitter_deposit = new BabySitterDeposit();
            $baby_sitter_deposit->baby_sitter_id = $baby_sitter->id;
            $baby_sitter_deposit->price = $price;
            $baby_sitter_deposit->save();
            $result = $this->pay($baby_sitter_deposit->id, $price, $baby_sitter, 'Depozito', $cardInfos);
            $baby_sitter_deposit->raw_result = $result->getRawResult();
            if ($result->getStatus() != 'failure') {
                $baby_sitter_deposit->status = 1;
                $baby_sitter_deposit->save();
                $baby_sitter->deposit = $baby_sitter->deposit + $price;
                $baby_sitter->baby_sitter_status_id = 4;
                $baby_sitter->save();

                return $this->sendResponse('Ödemeniz Alınmıştır', 'Ödeme başarı ile gerçekleşti!');
            } else {
                $baby_sitter_deposit->status = 0;
                $baby_sitter_deposit->save();
                return $this->sendError($result->getErrorCode(), $result->getErrorMessage(), 400);
            }

        } else {
            return $this->sendError('Depozit :' . $baby_sitter->deposit, 'Herhangi bir ödeme yapmanıza gerek yoktur.', 400);
        }
    }

    public function deposit_pay_3d()
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
