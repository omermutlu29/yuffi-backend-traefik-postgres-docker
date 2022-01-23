<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\PreferencesUpdateRequest;
use App\Services\ProfileService\BabySitterProfileService;

class PreferenceController extends BaseController
{

    private BabySitterProfileService $profileService;

    public function __construct(BabySitterProfileService $profileService)
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step', 'deposit']);
        $this->profileService = $profileService;
    }

    public function update(PreferencesUpdateRequest $request): \Illuminate\Http\Response
    {
        $data = [];
        $babySitter = auth()->user();
        $data['base_preferences'] = $request->only('price_per_hour', 'child_year_id', 'child_gender_id', 'child_count', 'disabled_status', 'animal_status', 'accepted_locations');
        $data['relational_preferences'] = $request->only('towns', 'accepted_locations');
        try {
            $this->profileService->updatePreferences($babySitter, $data);
        } catch (\Exception $e) {
            return $this->sendError(false, 'Birşeyler ters gitti!');
        }
        return $this->sendResponse($this->profileService->getProfile(auth()->id()), 'Bilgiler güncellendi');
    }

}
