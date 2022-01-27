<?php


namespace App\Http\Controllers\API\Parent\Filter;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\BabySitter\FindBabySitterRequest;
use App\Http\Resources\BabySitterResource;
use App\Models\BabySitter;
use App\Services\Appointment\BabySitterFilterService;

class BabySitterController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function findBabySitter(FindBabySitterRequest $request, BabySitterFilterService $appointmentFilterService)
    {
        $data = $request->only('search_param');
        try {
            return $appointmentFilterService->findBabySitterForAppointment(\auth()->user(), $data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null, $e->getCode());
        }
    }

    public function sendOfferToBabySitter(){

    }

    public function show(BabySitter $babySitter)
    {
        $data = [];
        $data['general'] = BabySitterResource::make($babySitter);
        $data['comment_count'] = count($babySitter->comments);
        $data['comments'] = $babySitter->comments()->with('appointment.parent')->get();
        $data['avg_point'] = $babySitter->points()->average('point');
        $data['appointment_count'] = $babySitter->appointments()->count();
        $data['clothing'] = $babySitter->points()->where('point_type_id', 1)->average('point');
        $data['timing'] = $babySitter->points()->where('point_type_id', 2)->average('point');
        $data['contact'] = $babySitter->points()->where('point_type_id', 3)->average('point');
        $data['choose'] = route('baby-sitter.choose', $babySitter->id);
        return $this->sendResponse($data, 'Bilgiler başarı ile getirildi!');
    }





}
