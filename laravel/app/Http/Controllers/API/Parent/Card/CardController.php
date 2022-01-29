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
            $response = $this->registerCardService->getCardList($userKey);
            if (isset($response['rawResult'])) {
                $response = json_decode($response['rawResult']);
                $cardDetails = $response->cardDetails;
                return $this->sendResponse($cardDetails, 'Kayıtlı kartlar getirildi');
            }
            throw new \Exception('Kredi kartı servisi yanıt vermiyor');
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);

        }
    }

    public function store(StoreCardRequest $request)
    {
        try {
            $cardData = $request->only('cardHolderName', 'cardNumber', 'expireMonth', 'expireYear', 'cardAlias');
            $userKey = $this->cardRepository->getUserKey(\auth()->id());
            if ($userKey != null) {
                $result = $this->registerCardService->createCard($userKey, $cardData);
            } else {
                $result = $this->registerCardService->createCardWithUser($cardData, \auth()->user()->email, \auth()->id());
            }
            $this->cardRepository->store(\auth()->id(), $result);
            return $this->sendResponse(true, "You have registered card successfully", 401);
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function delete(DeleteCardRequest $request, CardParent $cardParent)
    {
        try {
            $userKey = $this->cardRepository->getUserKey(auth()->id());
            if (!$userKey) throw new \Exception('Kayıtlı kartınız bulunamadı', 400);
            $this->registerCardService->deleteCard($userKey, $cardParent->cardtoken);
            if ($this->cardRepository->delete($cardParent->id)) {
                return $this->sendResponse(true, 'Kayıtlı kartınız başarı ile silindi!');
            }
            throw new \Exception('Kayıtlı kartınız silinemedi');
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }
}
