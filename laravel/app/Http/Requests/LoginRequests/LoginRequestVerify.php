<?php

namespace App\Http\Requests\LoginRequests;

use App\Http\Requests\BaseApiRequest;

class LoginRequestVerify extends BaseApiRequest
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
            'phone'=>'required',
            'google_st'=>'required',
            'code'=>'required|max:4|min:4'
        ];
    }


}
