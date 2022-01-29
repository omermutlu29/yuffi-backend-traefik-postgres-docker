<?php

namespace App\Http\Requests\BabySitter\CreditCard;

use App\Interfaces\IRepositories\ICardRepository;
use Illuminate\Foundation\Http\FormRequest;

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
        $card = $this->cardRepository->getUserCardByCardToken(\request()->only('cardToken'));
        if (!$card) return false;
        return $card->parent_id == auth()->id();
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
