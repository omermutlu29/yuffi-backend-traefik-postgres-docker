<?php

namespace App\Http\Requests\LoginRequests;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends BaseApiRequest
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
}
