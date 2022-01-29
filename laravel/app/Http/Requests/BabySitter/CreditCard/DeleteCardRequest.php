<?php

namespace App\Http\Requests\BabySitter\CreditCard;

use App\Interfaces\IRepositories\ICardRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteCardRequest extends FormRequest
{
    private $cardRepository;

    public function __construct(ICardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

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
            'cardToken' => 'required|exists:card_parents,cardtoken'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ],400));
    }
}
