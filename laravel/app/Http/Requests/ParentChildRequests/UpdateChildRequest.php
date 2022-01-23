<?php

namespace App\Http\Requests\ParentChildRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $child=($this->route('child'));
        return (auth()->user()->can('update',$child));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "child_year_id" => 'required|exists:child_years,id',
            "gender_id" => 'required|exists:genders,id',
            "disable" => 'required|boolean'
        ];
    }
}
