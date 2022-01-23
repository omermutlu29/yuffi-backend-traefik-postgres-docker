<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ParentUpdateProfileRequest extends FormRequest
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
            'tc' => 'required|max:11|min:11',
            'birthday' => 'required|date_format:d-m-Y|before_or_equal:' . $date,
            'service_contract' => 'required',
            'gender_id' => 'required|exists:genders,id',
            // 'photo' => 'required|file|mimes:jpeg,jpg,png'
        ];
    }
}
