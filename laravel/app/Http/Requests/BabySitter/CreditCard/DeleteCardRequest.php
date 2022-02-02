<?php

namespace App\Http\Requests\BabySitter\CreditCard;

use App\Http\Requests\BaseApiRequest;
use App\Interfaces\IRepositories\ICardRepository;

class DeleteCardRequest extends BaseApiRequest
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


}
