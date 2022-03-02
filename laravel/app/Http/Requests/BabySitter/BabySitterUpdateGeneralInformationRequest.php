<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\BaseApiRequest;
use App\Rules\IBANRule;
use Carbon\Carbon;

class BabySitterUpdateGeneralInformationRequest extends BaseApiRequest
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
        $date = Carbon::make('2002-01-01')->format('d-m-Y');

        return [
            // 'name' => 'required',
            'surname' => 'required',
            // 'tc' => 'required',
            // 'gender_id' => 'required',
            // 'birthday' => 'required|date_format:d-m-Y|before_or_equal:' . $date,
            // 'criminal_record' => 'required|file|mimes:pdf|max:2048',
            // 'address' => '',
            'email' => 'required|unique:baby_sitters,email,'.auth()->id(),
            'photo' => 'image|mimes:jpg,png,jpeg|max:8192',
            'iban' => ['required', new IBANRule()],
            'introducing'=>'min:80|max:500'
        ];
    }


}
