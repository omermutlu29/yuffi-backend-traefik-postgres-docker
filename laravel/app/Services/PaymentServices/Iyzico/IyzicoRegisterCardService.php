<?php


namespace App\Services\PaymentServices\Iyzico;

use App\Interfaces\PaymentInterfaces\IRegisterCardService;
use JetBrains\PhpStorm\ArrayShape;


class IyzicoRegisterCardService extends IyzicoBaseService implements IRegisterCardService
{


    #[ArrayShape(['carduserkey' => "", 'cardtoken' => "", "cardalias" => ""])]
    public function createCard(string $cardUserKey, array $cardData): array
    {
        self::setOptions();
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

    #[ArrayShape(['carduserkey' => "", 'cardtoken' => "", "cardalias" => ""])]
    public function createCardWithUser(array $cardData, string $email, string $externalId): array
    {
        self::setOptions();
        $request = new \Iyzipay\Request\CreateCardRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setEmail($email);
        $request->setExternalId($externalId);
        $cardInformation = self::prepareCardInformation($cardData);
        $request->setCard($cardInformation);
        $card = \Iyzipay\Model\Card::create($request, $this->options);
        if ($card->getStatus() != "success") throw new \Exception($card->getErrorMessage(), $card->getErrorCode());
        return [
            'carduserkey' => $card->getCardUserKey(),
            'cardtoken' => $card->getCardToken(),
            'cardalias' => $card->getCardAlias()
        ];
    }


    #[ArrayShape(['rawResult' => ""])]
    public function getCardList(string $cardUserKey): array
    {
        self::setOptions();
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
        self::setOptions();
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


    private static function prepareCardInformation(array $cardInformationParam): \Iyzipay\Model\CardInformation
    {
        $cardInformation = new \Iyzipay\Model\CardInformation();
        $cardInformation->setCardAlias($cardInformationParam['cardAlias']);
        $cardInformation->setCardHolderName($cardInformationParam['cardHolderName']);
        $cardInformation->setCardNumber($cardInformationParam['cardNumber']);
        $cardInformation->setExpireMonth($cardInformationParam['expireMonth']);
        $cardInformation->setExpireYear($cardInformationParam['expireYear']);
        return $cardInformation;
    }
}
