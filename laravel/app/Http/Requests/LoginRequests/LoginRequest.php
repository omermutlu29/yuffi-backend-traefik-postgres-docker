<?php

namespace App\Http\Requests\LoginRequests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'google_st'=>'required',
            'kvkk'=>['required','boolean',function ($attribute, $value, $fail) {
                if (!$value) {
                    $fail('Uygulamayı kullanabilmeniz için KVKK sözleşmesini kabul etmeniz gerekmektedir.');
                }
            }],
            'service_contract'=>['required','boolean',function ($attribute, $value, $fail) {
                if (!$value) {
                    $fail('Uygulamayı kullanabilmeniz için hizmet sözleşmesini kabul etmeniz gerekmektedir.');
                }
            }],
            'phone'=>'required|digits:10',
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
