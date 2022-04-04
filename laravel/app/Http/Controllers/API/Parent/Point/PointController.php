<?php


namespace App\Http\Controllers\API\Parent\Point;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\GivePointRequest;
use App\Interfaces\IServices\IPointService;
use App\Models\PointType;
use Illuminate\Support\Facades\Log;

class PointController extends BaseController
{
    private IPointService $pointService;

    public function __construct(IPointService $pointService)
    {
        $this->middleware('auth:parent');
        $this->pointService = $pointService;
    }

    public function getPointTypes(){
        try {
            return $this->sendResponse(PointType::all(), 'Puan Tipleri getirildi!');

        }catch (\Exception $exception){
            return $this->sendError('Hata', ['error' => 'Bir sorun oluştu'], 400);

        }
    }

    public function getUnratedAppointments()
    {
        try {
            return $this->sendResponse($this->pointService->getUnratedAppointments(auth()->user()), 'Puanlanmamış randevularınız getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError('Hata', ['error' => 'Bir sorun oluştu'], 400);
        }
    }

    public function rateAppointment(GivePointRequest $request)
    {
        $data = $request->only('appointment_points');
        //try {
            foreach ($data['appointment_points'] as $point) {
                $this->pointService->rateAppointment(
                    $point['appointment_id'],
                    $point['point_type'],
                    $point['point'],
                    $point['additional_text']
                );
            }
            return $this->sendResponse(true, 'Puan başarılı bir şekilde verildi!');
       /* } catch (\Exception $exception) {
            Log::info($exception);
            return $this->sendError('Bir sorun oluştu', ['error' => 'Bir sorun oluştu'], 400);
        }*/

    }

}
