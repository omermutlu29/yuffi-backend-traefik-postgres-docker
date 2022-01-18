<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use LVR\CreditCard\CardCvc;

class CVCRule extends CardCvc implements Rule
{
    const MSG_CARD_CVC_INVALID = 'CVC geçersiz';
    protected $card_number;
}
