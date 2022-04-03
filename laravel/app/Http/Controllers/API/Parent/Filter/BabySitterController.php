<?php


namespace App\Http\Controllers\API\Parent\Filter;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\BabySitter\FindBabySitterRequest;
use App\Http\Requests\Parent\BabySitter\MakeOfferRequest;
use App\Http\Resources\BabySitterResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Models\BabySitter;
use App\Services\Appointment\BabySitterFilterService;

class BabySitterController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:parent');
    }

    public function findBabySitter(FindBabySitterRequest $request, BabySitterFilterService $babySitterFilterService)
    {
        $filterData = $request->only('search_param');
        $filterData = ($filterData["search_param"]);
        try {
            return $this->sendResponse(
                $babySitterFilterService->findBabySitterForAppointment($filterData, auth()->user()),
                'Bakıcı listesi');
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function makeOfferToBabySitter(MakeOfferRequest $request, BabySitterFilterService $babySitterFilterService, IAppointmentService $appointmentService)
    {
        try {
            $filterData = $request->only('search_param');
            $babySitterId = $request->baby_sitter_id;
            $isAvailable = $babySitterFilterService->isBabySitterStillAvailable($filterData, $babySitterId);
            if ($isAvailable) {
                return $this->sendResponse($appointmentService->create($babySitterId, auth()->id(), $filterData), 'Appointment created');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);

        }
    }

    public function show(
        BabySitter $babySitter,
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository,
    )
    {

        $data = [];
        $data['general'] = BabySitterResource::make($babySitter);
        $data['avg_point'] = ceil(4.3);
        $data['appointment_count'] = 1;
        $data['clothing'] = ceil(3.1);
        $data['timing'] = ceil(2.4);
        $data['contact'] = ceil(3.4);

        return $this->sendResponse($data, 'Bilgiler başarı ile getirildi!');
    }


}
