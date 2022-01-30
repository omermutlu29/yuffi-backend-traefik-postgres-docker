<?php

namespace App\Http\Requests\Parent;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ParentStoreProfileRequest extends FormRequest
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
            //'service_contract' => 'required',
            'gender_id' => 'required|exists:genders,id',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
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
