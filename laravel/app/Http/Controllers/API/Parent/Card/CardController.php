<?php

namespace App\Http\Controllers\API\Parent\Card;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\BabySitter\CreditCard\DeleteCardRequest;
use App\Http\Requests\Parent\CreditCard\StoreCardRequest;
use App\Interfaces\IRepositories\ICardRepository;
use App\Interfaces\PaymentInterfaces\IRegisterCardService;
use App\Models\CardParent;

class CardController extends BaseController
{
    private IRegisterCardService $registerCardService;
    private ICardRepository $cardRepository;

    public function __construct(IRegisterCardService $registerCardService, ICardRepository $cardRepository)
    {
        $this->middleware('auth:parent');
        $this->registerCardService = $registerCardService;
        $this->cardRepository = $cardRepository;
    }

    public function index()
    {
        try {
            $userKey = $this->cardRepository->getUserKey(auth()->id());
            if (!$userKey) throw new \Exception('You have no card in our database', 400);
            return $this->registerCardService->getCardList($userKey);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), [], $exception->getCode());
        }
    }

    public function store(StoreCardRequest $request)
    {
        try {
            $cardData = $request->only('cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cardAlias');
            $userKey = $this->cardRepository->getUserKey(\auth()->id());
            $result = $userKey ?
                $this->registerCardService->createCard($userKey, $cardData) :
                $this->registerCardService->createCardWithUser($cardData, \auth()->user()->email, \auth()->user()->id());
            $this->cardRepository->store(\auth()->id(), $result);
            return $this->sendResponse(true, "You have registered card successfully", 401);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function delete(DeleteCardRequest $request, CardParent $cardParent)
    {
        try {
            $userKey = $this->cardRepository->getUserKey(auth()->id());
            if (!$userKey) throw new \Exception('You have no card in our database', 400);
            $this->registerCardService->deleteCard($userKey, $cardParent->cardtoken);
            $this->cardRepository->delete($cardParent->id);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), [], $exception->getCode());
        }
    }
}
