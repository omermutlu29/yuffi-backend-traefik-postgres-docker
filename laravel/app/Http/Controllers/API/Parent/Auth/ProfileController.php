<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\ParentUpdateProfileRequest;
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
            return $this->parentProfileService->update(\auth()->id(), $request->only('name', 'surname', 'tc', 'birthday', 'service_contract', 'gender_id', 'photo'));
        } catch (\Exception $e) {
            throw $e;
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
