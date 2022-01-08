<?php

namespace App\Http\Controllers\API\Parent\Card;

use App\Models\CardParent;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends BaseController
{
    private $apiKey;
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = "sandbox-lSbnjzUNb16LIlL7jS4GawM8jMNz5Am8";
        $this->secretKey = "sandbox-h46lZ9TxaCxuIHudfZ2ulOWyapHfwXzh";
        $this->baseUrl = "https://sandbox-api.iyzipay.com";
        $this->middleware('auth:parent');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holder' => 'required',
            'number' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvc' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = Auth::user();
        if (count($user->card_parents) > 0) {
            $result = $this->create_card($request);
        } else {
            $result = $this->create_user_and_add_card($request);
        }

        if ($result['status'] == "success") {
            $cardUser = new CardParent();
            $cardUser->cardtoken = @$result['cardToken'] ? @$result['cardToken'] : "-";
            $cardUser->carduserkey = @$result['cardUserKey'] ? @$result['cardUserKey'] : "-";
            $cardUser->parent_id = Auth::id();
            $cardUser->cardfamily = @$result['cardFamily'] ? @$result['cardFamily'] : "-";
            if ($request->has('alias')) {
                $cardUser->cardalias = $request->alias;

            } else {
                $cardUser->cardalias = @$result['cardBankName'] ? @$result['cardBankName'] : "-";

            }
            $cardUser->lastfourdigits = @$result['lastFourDigits'] ? @$result['lastFourDigits'] : "-";
            $cardUser->cardtype = @$result['cardType'] ? @$result['cardType'] : "-";
            $cardUser->cardassociation = @$result['cardAssociation'] ? @$result['cardAssociation'] : "-";
            $cardUser->cardbankname = @$result['cardBankName'] ? @$result['cardBankName'] : "-";
            $result = $cardUser->save();
            if ($result == true) {
                $success['card_user'] = $cardUser;
                return $this->sendResponse($success, 'Kartınız başarı ile eklendi');
            }
        } else {
            return $this->sendError('Bir sorun oluştu!');
        }
    }

    //Çalışıyor
    public function create_user_and_add_card(Request $request1)
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey($this->apiKey);
        $options->setSecretKey($this->secretKey);
        $options->setBaseUrl($this->baseUrl);

        $request = new \Iyzipay\Request\CreateCardRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId(Auth::id());
        if (Auth::user()->email) {
            $request->setEmail(Auth::user()->email);
        } else {
            $request->setEmail(Auth::user()->phone . '@flok.com.tr');
        }
        $request->setExternalId(Auth::id());
        $cardInformation = new \Iyzipay\Model\CardInformation();
        $cardInformation->setCardAlias($request1->alias);
        $cardInformation->setCardHolderName($request1->holder);
        $cardInformation->setCardNumber($request1->number);
        $cardInformation->setExpireMonth($request1->month);
        $cardInformation->setExpireYear($request1->year);
        $request->setCard($cardInformation);
        $card = \Iyzipay\Model\Card::create($request, $options);
        return json_decode($card->getRawResult(), true);
    }

    public function create_card(Request $request1)
    {
        $user = Auth::user();
        if (count($user->card_parents) > 0) {
            $options = new \Iyzipay\Options();
            $options->setApiKey($this->apiKey);
            $options->setSecretKey($this->secretKey);
            $options->setBaseUrl($this->baseUrl);
            # create request class
            $request = new \Iyzipay\Request\CreateCardRequest();
            $request->setLocale(\Iyzipay\Model\Locale::TR);
            $request->setConversationId(Auth::id());
            $request->setCardUserKey($user->card_parents[0]->carduserkey);

            $cardInformation = new \Iyzipay\Model\CardInformation();
            $cardInformation->setCardAlias($request1->alias);
            $cardInformation->setCardHolderName($request1->holder);
            $cardInformation->setCardNumber($request1->number);
            $cardInformation->setExpireMonth($request1->month);
            $cardInformation->setExpireYear($request1->year);
            $request->setCard($cardInformation);
            $card = \Iyzipay\Model\Card::create($request, $options);
            return json_decode($card->getRawResult(), true);
        } else {
            return $this->sendError('Kayıtlı kartınız bulunmamaktadır!');
        }
    }

    public function cardList()
    {
        $user = Auth::user();

        if (count($user->card_parents) > 0) {
            $success['cards'] = CardResource::collection($user->card_parents);
            return $this->sendResponse($success, 'Kart Listesi');
        } else {
            return $this->sendError('Kayıtlı kartınız bulunmamaktadır.');
        }
    }

    public function delete($id)
    {

        $options = new \Iyzipay\Options();
        $options->setApiKey($this->apiKey);
        $options->setSecretKey($this->secretKey);
        $options->setBaseUrl($this->baseUrl);

        $card = CardParent::find($id);
        if ($card) {
            $request = new \Iyzipay\Request\DeleteCardRequest();
            $request->setLocale(\Iyzipay\Model\Locale::TR);
            $request->setConversationId($card->id);
            $request->setCardToken($card->cardtoken);
            $request->setCardUserKey($card->carduserkey);
            $result = \Iyzipay\Model\Card::delete($request, $options);
            $result = json_decode($result->getRawResult());
            if ($result->status == "success" && $result->conversationId == $card->id) {
                $result1 = $card->delete();
                if ($result1) {
                    $success['id'] = $result->conversationId;
                    return $this->card_list();
                } else {
                    return $this->sendError('Eşleşme Hatası');
                }
            }
        } else {
            return $this->sendError('Kart bulunamadı!');
        }


    }
}
