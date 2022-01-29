<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\CreditCardRequest;
use App\Rules\CVCRule;
use Illuminate\Contracts\Validation\Validator;

class DepositPayRequest extends CreditCardRequest
{

    public function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub
        $rules['cvc'] = ['required', new CVCRule($this->get('cardNumber'))];
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator); // TODO: Change the autogenerated stub
    }

    public function messages(): array
    {
        $messages = parent::messages(); // TODO: Change the autogenerated stub
        $messages['cvc.required'] = 'CVC kodu gereklidir';
        return $messages;
    }
}