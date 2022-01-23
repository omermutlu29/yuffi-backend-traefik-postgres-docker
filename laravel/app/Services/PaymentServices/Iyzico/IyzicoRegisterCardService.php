<?php


namespace App\Services\PaymentServices\Iyzico;

use App\Interfaces\PaymentInterfaces\IRegisterCardService;
use JetBrains\PhpStorm\ArrayShape;


class IyzicoRegisterCardService extends IyzicoBaseService implements IRegisterCardService
{
    public function __construct()
    {
        $this->setOptions();
    }

    #[ArrayShape(['carduserkey' => "", 'cardtoken' => "", "cardalias" => ""])]
    public function createCard(string $cardUserKey, array $cardData): array
    {
        $request = new \Iyzipay\Request\CreateCardRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setCardUserKey($cardUserKey);
        $cardInformation = self::prepareCardInformation($cardData);
        $request->setCard($cardInformation);
        $card = \Iyzipay\Model\Card::create($request, $this->options);
        if ($card->getStatus() != "success") {
            throw new \Exception($card->getErrorMessage(), $card->getErrorCode());
        }
        return ['carduserkey' => $card->getCardUserKey(), 'cardtoken' => $card->getCardToken(), "cardalias" => $card->getCardAlias()];

    }

    #[ArrayShape(['carduserKey' => "", 'cardtoken' => "", "cardalias" => ""])]
    public function createCardWithUser(array $cardData, string $email, string $externalId): array
    {
        $request = new \Iyzipay\Request\CreateCardRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setEmail($email);
        $request->setExternalId($externalId);
        $cardInformation = self::prepareCardInformation($cardData);
        $request->setCard($cardInformation);
        $card = \Iyzipay\Model\Card::create($request, $this->options);
        return $card->getStatus() != "success" ? throw new \Exception($card->getErrorMessage(), $card->getErrorCode()) :
            ['carduserKey' => $card->getCardUserKey(), 'cardtoken' => $card->getCardToken(), 'cardalias' => $card->getCardAlias()];
    }


    #[
        ArrayShape(['rawResult' => ""])]
    public function getCardList(string $cardUserKey): array
    {
        $request = new \Iyzipay\Request\RetrieveCardListRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setCardUserKey($cardUserKey);
        $cardList = \Iyzipay\Model\CardList::retrieve($request, $this->options);
        if ($cardList->getStatus() != "success") {
            throw new \Exception($cardList->getErrorMessage(), $cardList->getErrorCode());
        }
        return ['rawResult' => $cardList->getRawResult()];


    }

    public function deleteCard(string $cardUserKey, string $cardToken): bool
    {
        $request = new \Iyzipay\Request\DeleteCardRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setCardToken($cardToken);
        $request->setCardUserKey($cardUserKey);
        $card = \Iyzipay\Model\Card::delete($request, $this->options);
        if ($card->getStatus() != "success") {
            throw new \Exception($card->getErrorMessage(), $card->getErrorCode());
        }
        return true;
    }


    private static function prepareCardInformation(array $cardInformation): \Iyzipay\Model\CardInformation
    {
        $cardInformation = new \Iyzipay\Model\CardInformation();
        $cardInformation->setCardAlias($cardInformation['cardAlias']);
        $cardInformation->setCardHolderName($cardInformation['cardHolderName']);
        $cardInformation->setCardNumber($cardInformation['cardNumber']);
        $cardInformation->setExpireMonth($cardInformation['expireMonth']);
        $cardInformation->setExpireYear($cardInformation['expireYear']);
        return $cardInformation;
    }


}
