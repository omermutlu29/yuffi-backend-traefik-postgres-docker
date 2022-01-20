<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\BabySitterStoreGeneralInformationRequest;
use App\Models\BabySitter;
use App\Services\ProfileService\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->middleware('auth:baby_sitter');
        $this->middleware('bs_first_step', ['except' => ['storeGeneralInformation', 'getProfile']]);
        $this->profileService = $profileService;
    }

    public function storeGeneralInformation(BabySitterStoreGeneralInformationRequest $request): \Illuminate\Http\Response
    {
        try {
            $result = $this->profileService->updateBasicInformation(\auth()->user(), $request);
            return $this->sendResponse($result['status'], $result['message']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProfile(): \Illuminate\Http\Response
    {
        $baby_sitter = BabySitter::with('baby_sitter_status:id,name', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name', 'accepted_locations', 'avaliable_towns')->find(Auth::id());
        $success['baby_sitter'] = $baby_sitter;
        return $this->sendResponse($success, 'Veri Başarı ile Getirildi!');
    }


}
