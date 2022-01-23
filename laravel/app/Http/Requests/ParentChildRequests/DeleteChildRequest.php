<?php

namespace App\Http\Requests\ParentChildRequest;

use Illuminate\Foundation\Http\FormRequest;

class DeleteChildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $child=($this->route('child'));
        return (auth()->user()->can('delete',$child));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
