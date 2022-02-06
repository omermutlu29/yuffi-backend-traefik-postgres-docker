<?php

namespace App\Http\Requests\Parent;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ParentUpdateProfileRequest extends BaseApiRequest
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
            'photo' => 'image|mimes:jpg,png,jpeg|max:2048',
            'email'=>'email',
        ];
    }


}
