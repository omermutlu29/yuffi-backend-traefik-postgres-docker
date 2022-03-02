<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\BabySitter\BabySitterStoreGeneralInformationRequest;
use App\Http\Requests\BabySitter\BabySitterUpdateGeneralInformationRequest;
use App\Interfaces\IServices\IProfileService;


class ProfileController extends BaseController
{
    private IProfileService $profileService;

    public function __construct(IProfileService $profileService)
    {
        $this->middleware('auth:baby_sitter');
        $this->middleware('bs_first_step', ['except' => ['storeGeneralInformation', 'getProfile']]);
        $this->profileService = $profileService;
    }

    public function storeGeneralInformation(BabySitterStoreGeneralInformationRequest $request)
    {
        try {
            $data = $request->only('name', 'surname', 'tc', 'gender_id', 'birthday', 'criminal_record', 'address', 'email', 'photo', 'iban', 'introducing');
            $result = $this->profileService->updateBasicInformation(\auth()->user(), $data);
            return $this->sendResponse($result['status'], $result['message']);
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }

    public function updateGeneralInformation(BabySitterUpdateGeneralInformationRequest $request)
    {
        try {
            $data = $request->only('surname', 'email', 'photo', 'iban', 'introducing');
            $result = $this->profileService->updateBasicInformation(\auth()->user(), $data);
            return $this->sendResponse($result['status'], $result['message']);
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);
        }
    }


    public function getProfile()
    {
        try {
            $success['baby_sitter'] = $this->profileService->getProfile(\auth()->id());
            return $this->sendResponse($success, 'Veri Başarı ile Getirildi!');
        } catch (\Exception $exception) {
            return $this->sendError('Hata!', ['message' => [$exception->getMessage()]], 400);

        }

    }

}
