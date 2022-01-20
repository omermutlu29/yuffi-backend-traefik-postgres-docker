<?php

namespace App\Http\Requests;

use App\Rules\IBANRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BabySitterStoreGeneralInformationRequest extends FormRequest
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
            'name' => 'required',
            'surname' => 'required',
            'tc' => 'required',
            'gender_id' => 'required',
            'birthday' => 'required',
            'criminal_record' => 'required|file',
            'address' => 'required',
            'email' => 'required',
            'photo' => 'required|file',
            'iban' => ['required', new IBANRule()]
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
