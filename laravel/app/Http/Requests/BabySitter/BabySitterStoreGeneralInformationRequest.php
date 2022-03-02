<?php

namespace App\Http\Requests\BabySitter;

use App\Http\Requests\BaseApiRequest;
use App\Rules\IBANRule;
use Carbon\Carbon;

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
        $date = Carbon::make('2002-01-01')->format('d-m-Y');

        return [
            'name' => 'required',
            'surname' => 'required',
            'tc' => 'required|unique:baby_sitters,tc',
            'gender_id' => 'required',
            'birthday' => 'required|date_format:d/m/Y|before_or_equal:' . $date,
            'criminal_record' => 'required|file|mimes:pdf|max:2048',
            'address' => 'required',
            'email' => 'required|unique:baby_sitters,email',
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'iban' => ['required', new IBANRule()],
            'introducing' => 'required|min:80|max:500'
        ];
    }


}
