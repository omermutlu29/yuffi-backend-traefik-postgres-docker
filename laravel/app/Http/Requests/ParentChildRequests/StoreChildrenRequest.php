<?php

namespace App\Http\Requests\ParentChildRequest;

use Illuminate\Foundation\Http\FormRequest;

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
            'children.*.child_year_id' => 'required|exists:child_years,id',
            'children.*.gender_id' => 'required|exists:genders,id',
            'children.*.disable' => 'required|boolean',
        ];
    }
}
