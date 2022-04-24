<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\ParentStoreProfileRequest;
use App\Http\Requests\Parent\ParentUpdateProfileRequest;
use App\Interfaces\IServices\IProfileService;

class ProfileController extends BaseController
{
    private IProfileService $parentProfileService;

    public function __construct(IProfileService $profileService)
    {
        $this->middleware('auth:parent');
        $this->parentProfileService = $profileService;
    }

    public function storeProfile(ParentStoreProfileRequest $request)
    {
        try {
            return $this->parentProfileService->update(
                \auth()->id(),
                $request->only('name', 'surname', 'tc', 'birthday', 'service_contract', 'gender_id', 'photo', 'email','optional_contact')
            ) ? $this->sendResponse($this->parentProfileService->getProfile(\auth()->id()), 'Profiliniz kaydedildi') :
                $this->sendError('Bir hata ile karşılaşıldı', 400);
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function updateProfile(ParentUpdateProfileRequest $request)
    {
        try {
            return $this->parentProfileService->update(
                \auth()->id(),
                $request->only('name', 'surname', 'tc', 'birthday', 'service_contract', 'gender_id', 'photo', 'email','address','city','optional_contact')
            ) ? $this->sendResponse($this->parentProfileService->getProfile(\auth()->id()), 'Profiliniz güncellendi') :
                $this->sendError('Bir hata ile karşılaşıldı', 400);
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function getProfile()
    {
        try {
            return $this->sendResponse($this->parentProfileService->getProfile(\auth()->id()), 'Profil bilgileri getirildi');
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function isReadyToCreateAppointment()
    {
        try {
            $user = auth()->user();
            if ($user->isReadyToCreateAppointment()) {
                return $this->sendResponse(true, 'Randevu oluşturabilir');
            }
            throw new \Exception('Profilinizi tamamlamadan randevu oluşturamazsınız!', 400);
        } catch (\Exception $exception) {
            return $this->sendError('Uyarı!', ['message' => $exception->getMessage()],$exception->getCode());
        }

    }

}
