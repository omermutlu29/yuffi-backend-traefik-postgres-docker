<?php

namespace App\Http\Requests\AppointmentRequests;

use App\Http\Requests\BaseApiRequest;
use Carbon\Carbon;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;
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
        $ifItIsNotRegisteredCard = 'exclude_unless:create_params.paymentWithRegisteredCard,true';
        $ifItIsRegisteredCard = 'exclude_unless:create_params.paymentWithRegisteredCard,false';
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
            'create_params.creditCard.expireYear' => [$ifItIsNotRegisteredCard, 'required', new CardExpirationYear($this->get('expireMonth'))],
            'create_params.creditCard.expireMonth' => [$ifItIsNotRegisteredCard, 'required', new CardExpirationMonth($this->get('expireYear'))],
            //Kredi kartı son
            'create_params.creditCard.cardToken' => [$ifItIsRegisteredCard, 'required'],
            //Kayıtlı kredi kartı
        ];

    }
}
