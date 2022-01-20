<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\PaymentInterfaces\ISubMerchantService;
use App\Models\BabySitter;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;


class ProfileService implements IProfileService
{
    private IUserRepository $userRepository;
    private ISubMerchantService $subMerchantService;

    public function __construct(IUserRepository $userRepository, ISubMerchantService $subMerchantService)
    {
        $this->subMerchantService = $subMerchantService;
        $this->userRepository = $userRepository;
    }

    #[ArrayShape(['status' => "bool", 'message' => "mixed|string"])]
    public function updateBasicInformation(BabySitter $babySitter, Request $request): array
    {
        $result = ['status' => false, 'message' => ''];
        try {
            $criminalRecordPath = $request->file('criminal_record')->store('public/criminal-records');
            $profilePhotoPath = $request->file('photo')->store('public/profile-photo');
            $request['criminal_record'] = $criminalRecordPath;
            $request['photo'] = $profilePhotoPath;
            $this->userRepository->update($babySitter->id, $request->all());
            if ($babySitter->sub_merchant != null) {
                $serviceResult = $this->subMerchantService->updateIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    $result['message'] = $serviceResult->getErrorMessage();
                }
            } else {
                $serviceResult = $this->subMerchantService->insertIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    $result['message'] = $serviceResult->getErrorMessage();
                }
            }
            $data = ['sub_merchant' => $serviceResult->getSubMerchantKey()];
            $this->userRepository->update($babySitter->id, $data);
            $result['status'] = true;
            $result['message'] = 'İşlem tamamlandı';
            return $result;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function updatePreferences(BabySitter $babySitter, $validatedData)
    {
        try {
            $towns = $validatedData['relational_preferences']['towns'] ?? [];
            $acceptedLocations = $validatedData['relational_preferences']['accepted_locations'] ?? [];
            $babySitter->update($validatedData['base_preferences']);
            if ($towns) {
                $babySitter->avaliable_towns()->sync($towns);
            }
            if ($acceptedLocations) {
                $babySitter->accepted_locations()->sync($acceptedLocations);
            }
            $this->userRepository->update($babySitter->id, ['baby_sitter_status_id' => 5]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getProfile(BabySitter $babySitter)
    {
        $relations = ['baby_sitter_status:id,name', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name', 'accepted_locations', 'avaliable_towns'];
        return $this->userRepository->getUserWithRelations($babySitter,$relations);
    }
}
