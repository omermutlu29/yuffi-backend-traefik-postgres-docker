<?php


namespace App\Http\Controllers\API\Parent\Filter;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\BabySitter\FindBabySitterRequest;
use App\Http\Requests\Parent\BabySitter\MakeOfferRequest;
use App\Http\Resources\BabySitterResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\ICommentRepository;
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
        $filterData=($filterData["search_param"]);
        try {
            return $this->sendResponse(
                $babySitterFilterService->findBabySitterForAppointment(\auth()->user(), $filterData),
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
            $isAvailable = $babySitterFilterService->isBabySitterStillAvailable(auth()->user(), $filterData, $babySitterId);
            if ($isAvailable) {
                return $this->sendResponse($appointmentService->create($babySitterId, auth()->id(), $filterData),'Appointment created');
            }
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);

        }
    }

    public function show(
        BabySitter $babySitter,
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository,
        ICommentRepository $commentRepository
    )
    {

        $data = [];
        $data['general'] = BabySitterResource::make($babySitter);
        $data['comment_count'] = $commentRepository->getBabySitterCommentsCount($babySitter->id);
        $data['comments'] = $commentRepository->getBabySitterComments($babySitter->id);
        $data['avg_point'] = $babySitter->points()->average('point');
        $data['appointment_count'] = $appointmentRepository->getPaidAppointments($babySitter->id);
        $data['clothing'] = $babySitter->points()->where('point_type_id', 1)->average('point');
        $data['timing'] = $babySitter->points()->where('point_type_id', 2)->average('point');
        $data['contact'] = $babySitter->points()->where('point_type_id', 3)->average('point');
        $data['choose'] = route('baby-sitter.choose', $babySitter->id);
        return $this->sendResponse($data, 'Bilgiler başarı ile getirildi!');
    }


}
