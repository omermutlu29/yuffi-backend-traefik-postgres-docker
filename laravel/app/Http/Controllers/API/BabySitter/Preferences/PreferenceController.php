<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\BabySitter\PreferencesUpdateRequest;
use App\Services\ProfileService\BabySitterProfileService;

class PreferenceController extends BaseController
{

    private BabySitterProfileService $profileService;

    public function __construct(BabySitterProfileService $profileService)
    {
        $this->middleware(
            ['auth:baby_sitter',
                'bs_first_step',
            //    'bs_second_step'
            ]);
        $this->profileService = $profileService;
    }

    public function update(PreferencesUpdateRequest $request)
    {
        $data = [];
        $data['base_preferences'] = $request->only('price_per_hour', 'child_year_id', 'child_gender_id', 'parent_gender_id', 'child_count', 'disabled_status', 'animal_status','wc_status');
        $data['relational_preferences'] = $request->only('towns', 'accepted_locations','shareable_talents');
        try {
            $this->profileService->updatePreferences(auth()->user(), $data);
        } catch (\Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
        return $this->sendResponse($this->profileService->getProfile(auth()->id()), 'Bilgiler güncellendi');
    }

}
