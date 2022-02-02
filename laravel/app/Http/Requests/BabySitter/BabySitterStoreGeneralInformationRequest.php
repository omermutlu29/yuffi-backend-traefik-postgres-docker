<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\BaseApiRequest;
use App\Rules\IBANRule;

class BabySitterStoreGeneralInformationRequest extends BaseApiRequest
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
            'criminal_record' => 'required|file|mimes:pdf|max:2048',
            'address' => 'required',
            'email' => 'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'iban' => ['required', new IBANRule()]
        ];
    }


}
