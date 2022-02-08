<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\PaymentInterfaces\ISubMerchantService;
use App\Models\BabySitter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;


class BabySitterProfileService implements IProfileService
{
    private IUserRepository $userRepository;
    private IBabySitterRepository $babySitterRepository;
    private ISubMerchantService $subMerchantService;

    public function __construct(IUserRepository $userRepository, ISubMerchantService $subMerchantService, IBabySitterRepository $babySitterRepository)
    {
        $this->subMerchantService = $subMerchantService;
        $this->userRepository = $userRepository;
        $this->babySitterRepository = $babySitterRepository;
    }

    #[ArrayShape(['status' => "bool", 'message' => "mixed|string"])]
    public function updateBasicInformation(BabySitter $babySitter, Request $request): array
    {
        $result = ['status' => true, 'message' => 'İşlem başarılı'];
        try {
            $request['birthday'] = Carbon::make($request['birthday'])->format('Y-m-d');
            $criminalRecordPath = $request->file('criminal_record')->store('public/criminal-records');
            $profilePhotoPath = $request->file('photo')->store('public/profile-photo');
            $request['criminal_record'] = $criminalRecordPath;
            $request['photo'] = $profilePhotoPath;
            $this->userRepository->update($babySitter->id, $request->all());
            if ($babySitter->sub_merchant != null) {
                $serviceResult = $this->subMerchantService->updateIyzicoSubMerchant($babySitter->attributesToArray());
            } else {
                $serviceResult = $this->subMerchantService->insertIyzicoSubMerchant($babySitter->attributesToArray());
            }
            if ($serviceResult->getStatus() == "failure") {
                $result['status'] = false;
                $result['message'] = $serviceResult->getErrorMessage();
            }
            $data = ['sub_merchant' => $serviceResult->getSubMerchantKey()];
            $this->userRepository->update($babySitter->id, $data);
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
            $towns ? $this->babySitterRepository->updateAvailableTowns($babySitter, $towns) : null;
            $acceptedLocations ? $this->babySitterRepository->updateAcceptedLocations($babySitter, $acceptedLocations) : null;
            $this->userRepository->update($babySitter->id, ['baby_sitter_status_id' => 5]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getProfile($id)
    {
        $relations = ['baby_sitter_status:id,name', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name', 'accepted_locations', 'available_towns'];
        return $this->userRepository->getUserWithRelations($id, $relations);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }
}
