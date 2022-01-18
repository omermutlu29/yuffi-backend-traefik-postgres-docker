<?php


namespace App\Http\Controllers\API\BabySitter\Preferences;


use App\Models\BabySitterPreferences;
use App\Models\BabySitterRegion;
use App\Http\Controllers\API\BabySitter\Auth\ProfileController;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BabySitterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PreferenceController extends BaseController
{

    public function __construct()
    {
        $this->middleware(['auth:baby_sitter', 'bs_first_step', 'bs_second_step', 'deposit']);
    }

    public function update(Request $request)
    {
        $baby_sitter = Auth::user();
        $json = json_encode($request->all());
        $data = json_decode($json);
        return $data;
        if ($request->hasFile('photo')) {
            $path=$request->file('criminal_record')->store('criminalrecords');
            $baby_sitter->photo = $path;
            $baby_sitter->save();
        }
        if ($request->has('price_per_hour')) {
            $baby_sitter->price_per_hour = $request->price_per_hour;
        }

        if (isset($data->child_gender_id)) {
            $baby_sitter->child_gender_id = $data->child_gender_id;
        }

        if (isset($data->child_year_id)) {
            $baby_sitter->child_year_id = $data->child_year_id;
        }

        if (isset($data->child_count)) {
            $baby_sitter->child_count = $data->child_count;
        }

        if (isset($data->disabled_status)) {
            $baby_sitter->disabled_status = $data->disabled_status;
        }

        if (isset($data->animal_status)) {
            $baby_sitter->animal_status = $data->animal_status;
        }


        if (isset($data->towns) && count($data->towns) > 0) {
            $baby_sitter->avaliable_towns()->sync($data->towns);
        } elseif (isset($data->towns) && count($data->towns) == 0) {
            $baby_sitter->avaliable_towns()->detach();
        }

        if (isset($data->accepted_locations) && count($data->accepted_locations) > 0) {
            $baby_sitter->accepted_locations()->sync($data->accepted_locations);
        } elseif (isset($data->accepted_locations) && count($data->accepted_locations) == 0) {
            $baby_sitter->accepted_locations()->detach();
        }


        $baby_sitter->baby_sitter_status_id = 5;
        $baby_sitter->save();

        return (new ProfileController())->getProfile();
        //$baby_sitter->load('baby_sitter_status');
        //  return $baby_sitter;
    }


}
