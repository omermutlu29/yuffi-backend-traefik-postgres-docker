<?php

namespace App\Http\Requests\Parent;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;

class ParentStoreProfileRequest extends BaseApiRequest
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
        $date = Carbon::make('2002-01-01')->format('d/m/Y');
        return [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
            //'tc' => 'required|max:11|min:11',
            //'birthday' => 'required|date_format:d/m/Y|before_or_equal:' . $date,
            //'gender_id' => 'required|numeric|exists:genders,id',
            //'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'email' => 'required|email',
        ];
    }


}
