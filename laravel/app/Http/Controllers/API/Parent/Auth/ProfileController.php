<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
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


    public function updateProfile(ParentUpdateProfileRequest $request)
    {
        try {
            return $this->parentProfileService->update(
                \auth()->id(),
                $request->only('name', 'surname', 'tc', 'birthday', 'service_contract', 'gender_id', 'photo')
            ) ? $this->sendResponse(true, 'Profiliniz güncellendi') :
                $this->sendError('Bir hata ile karşılaşıldı',null,400);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null, $e->getCode());
        }
    }

    public function getProfile()
    {
        try {
            return $this->parentProfileService->getProfile(\auth()->id());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
