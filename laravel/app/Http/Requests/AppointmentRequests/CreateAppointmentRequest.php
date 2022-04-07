<?php

namespace App\Http\Requests\AppointmentRequests;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;
use LVR\CreditCard\CardNumber;

class CreateAppointmentRequest extends BaseApiRequest
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
        $addThreeDays = today()->format('d-m-Y');
        $add15Days = today()->addDays(15)->format('d-m-Y');
        $startTime = (Carbon::make('10:00')->format('H:i'));
        $endTime = (Carbon::make('21:00')->format('H:i'));
        $ifItIsRegisteredCard = 'exclude_unless:create_params.paymentWithRegisteredCard,true';
        $ifItIsNotRegisteredCard = 'exclude_unless:create_params.paymentWithRegisteredCard,false';
        return [
            'create_params' => 'required',
            'create_params.baby_sitter_id' => 'required|exists:baby_sitters,id',
            'create_params.town_id' => 'required|exists:towns,id',
            'create_params.hour' => 'required|numeric|max:10|min:1',
            'create_params.location_id' => 'required|exists:appointment_locations,id',
            'create_params.children.*.gender_id' => 'required|exists:genders,id',
            'create_params.children.*.disable' => 'required|boolean',
            'create_params.children.*.child_year_id' => 'required|exists:child_years,id',
            'create_params.date' => 'required|date|date_format:d-m-Y|after_or_equal:' . $addThreeDays . '|before_or_equal:' . $add15Days,
            'create_params.time' => ['required', 'date_format:G:i', 'after_or_equal:' . $startTime, 'before_or_equal:' . $endTime],
            'create_params.gender_id' => 'required|exists:genders,id',
            'create_params.children' => 'required|array',
            'create_params.animal_status' => 'required|boolean',
            'create_params.wc_status' => 'required|boolean',
            'create_params.paymentWithRegisteredCard' => 'required|boolean',
            'create_params.creditCard' => 'required',
            //Kredi kartı bilgileri

            'create_params.creditCard.cardNumber' => [$ifItIsNotRegisteredCard, 'required', new CardNumber],
            'create_params.creditCard.cardHolderName' => [$ifItIsNotRegisteredCard, 'required', 'min:5'],


            'create_params.creditCard.expireYear' => [$ifItIsNotRegisteredCard, 'required'],
            'create_params.creditCard.expireMonth' => [$ifItIsNotRegisteredCard, 'required'],


            'create_params.creditCard.cvc' => [$ifItIsNotRegisteredCard, 'required'],
            'create_params.creditCard.registerCard' => [$ifItIsNotRegisteredCard, 'required', 'boolean'],
            //Kredi kartı son
            'create_params.creditCard.cardToken' => [$ifItIsRegisteredCard, 'required'],
            //Kayıtlı kredi kartı

        ];

    }

    public function manipulateData()
    {
        $data = $this->only('create_params');
        $data = $data['create_params'];
        $data['date'] = Carbon::createFromFormat('d-m-Y', $data['date']);
        return $data;
    }

    public function generateCardData($data)
    {
        $creditCard = $this->user()->card_parents()->first();
        if ($data['paymentWithRegisteredCard'] == true) {
            $creditCard ?? throw new \Exception('Kredi kartı bulunamadı!', 400);
            $cardData = [
                'cardToken' => $creditCard->cardtoken,
                'cardUserKey' => $creditCard->carduserkey
            ];
        } else {

            $cardData = [
                'cardNumber' => $this->get('create_params')['creditCard']['cardNumber'],
                'cardHolderName' => $this->get('create_params')['creditCard']['cardHolderName'],
                'expireYear' => $this->get('create_params')['creditCard']['expireYear'],
                'expireMonth' => $this->get('create_params')['creditCard']['expireMonth'],
                'cvc' => $this->get('create_params')['creditCard']['cvc'],
                'registerCard' => $this->get('create_params')['creditCard']['registerCard'],
            ];

            if ($creditCard && $cardData['registerCard'] == 1) {
                $cardData['cardUserKey'] = $creditCard->carduserkey;
            }

        }
        return $cardData;
    }
}
