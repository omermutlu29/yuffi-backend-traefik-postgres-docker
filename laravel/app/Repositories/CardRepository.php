<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\ICardRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Models\CardParent;
use App\Services\HttpStatuses\HttpStatuses;

class CardRepository implements ICardRepository
{
    private IUserRepository $parentRepository;

    public function __construct(IUserRepository $parentRepository)
    {
        $this->parentRepository = $parentRepository;
    }

    public function store(int $userId, array $data)
    {
        $user = $this->parentRepository->getUserById($userId);
        $card = $user->card_parents()->create($data);
        if (!$card) throw new \Exception('Card data could not inserted to db', HttpStatuses::HTTP_BAD_GATEWAY);
        return $card;
    }

    public function delete(string $cardToken): bool
    {
        $deleted = CardParent::where('cardtoken', $cardToken)->delete();
        if (!$deleted) throw new \Exception('Card data could not inserted to db', HttpStatuses::HTTP_BAD_GATEWAY);
        return true;
    }

    private function getUserCards(int $userId)
    {
        return CardParent::where('parent_id', $userId)->get();
    }

    public function getUserCardByCardToken(string $cardToken)
    {
        return CardParent::where('card_token', $cardToken)->first();
    }


    public function getUserKey(int $userId)
    {
        $userCards = self::getUserCards($userId);
        if (count($userCards) > 0) {
            return $userCards[0]->carduserkey;
        }
        return null;
    }
}
