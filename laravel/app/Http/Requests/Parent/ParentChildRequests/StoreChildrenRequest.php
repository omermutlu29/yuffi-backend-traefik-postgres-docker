<?php

namespace App\Http\Requests\Parent\ParentChildRequests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreChildrenRequest extends FormRequest
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
            'children' => 'required|array',
            'children.*.child_year_id' => ['required','exists:child_years,id',function ($attribute, $value, $fail) {
                if ($value === 1) {
                    $fail('Farketmez seçilemez');
                }
            },],
            'children.*.gender_id' => 'required|exists:genders,id',
            'children.*.disable' => 'required|boolean',
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
