<?php

namespace App\Http\Requests\AvailableTimeRequests;

use App\Interfaces\IRepositories\IBabySitterCalendarRepository;
use Illuminate\Foundation\Http\FormRequest;

class AvailableTimeUpdate extends FormRequest
{
    private IBabySitterCalendarRepository $calendarRepository;

    public function __construct(IBabySitterCalendarRepository $babySitterCalendarRepository)
    {
        $this->calendarRepository = $babySitterCalendarRepository;
    }

    public function authorize()
    {
        $availableTime = $this->calendarRepository->getAvailableTimeByIdWithDate(request()->post('available_time_id'));
        return \request()->user()->can('update', $availableTime);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'available_time_id' => 'required|exists:baby_sitter_available_times,id',
            'time_status_id' => 'required|exists:time_statuses,id'
        ];
    }
}
