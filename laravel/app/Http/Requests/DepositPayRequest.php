<?php

namespace App\Http\Requests;

use App\Models\BabySitter;
use Illuminate\Foundation\Http\FormRequest;

class DepositPayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           /* 'cardHolderName'=>'required|min:5',
            'cardNumber'=>'required|alpha_num|min:16',
            'cvc'=>'min:3',
            'expireMonth'=>'required|min:2|max:2',
            'expireYear'=>'required|min:2|max:4'*/
        ];
    }
}
