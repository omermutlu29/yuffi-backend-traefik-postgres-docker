<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardNumber;

abstract class CreditCardRequest extends FormRequest
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
            'cardHolderName' => 'required|min:5',
            'cardNumber' => ['required', new CardNumber],
            'expireYear' => ['required', new CardExpirationYear($this->get('expireMonth'))],
            'expireMonth' => ['required', new CardExpirationMonth($this->get('expireYear'))],
        ];
    }

    public function messages()
    {
        return [
            'expireYear' => 'Yıl geçersiz',
            'expireMonth' => 'Ay geçersiz',
            'cardNumber' => 'Kart numarası geçersiz'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}