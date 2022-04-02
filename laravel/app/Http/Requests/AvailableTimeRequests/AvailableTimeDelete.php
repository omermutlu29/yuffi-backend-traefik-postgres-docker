<?php

namespace App\Http\Requests\AvailableTimeRequests;

use App\Http\Requests\BaseApiRequest;
use App\Interfaces\IRepositories\IBabySitterCalendarRepository;

class AvailableTimeDelete extends BaseApiRequest
{
    private IBabySitterCalendarRepository $calendarRepository;

    public function __construct(IBabySitterCalendarRepository $babySitterCalendarRepository)
    {
        $this->calendarRepository = $babySitterCalendarRepository;
    }

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
        return [
            'available_time_id'=>'required|exists:baby_sitter_available_times,id|numeric'
        ];
    }
}
