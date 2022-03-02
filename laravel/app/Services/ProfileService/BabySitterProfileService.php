<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;
use App\Interfaces\PaymentInterfaces\ISubMerchantService;
use App\Models\BabySitter;
use Carbon\Carbon;
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
    public function updateBasicInformation(BabySitter $babySitter, array $data): array
    {
        $result = ['status' => true, 'message' => 'İşlem başarılı'];
        try {
            if (isset($data['birthday']))
                $data['birthday'] = Carbon::make($data['birthday'])->toDateString();
            if (isset($data['photo']))
                $data['photo'] = self::saveProfilePhoto($data['photo']);
            if (isset($data['criminal_record']))
                $data['criminal_record'] = self::saveCriminalRecord($data['criminal_record']);
            $this->userRepository->update($babySitter->id, $data);
            $this->updateInsertSubmerchantIban($babySitter, $data);
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
        $relations = ['baby_sitter_status:id,name','shareable_talents', 'child_year:id,name', 'gender:id,name', 'child_gender:id,name', 'accepted_locations', 'available_towns'];
        return $this->userRepository->getUserWithRelations($id, $relations);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    private function updateInsertSubmerchantIban($babySitter, $data)
    {
        if (isset($data['iban']) && $data['iban'] !== $babySitter->iban) {
            $babySitter = $this->userRepository->getUserById($babySitter->id);
            if ($babySitter->sub_merchant != null) {
                $serviceResult = $this->subMerchantService->updateIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    throw new \Exception($serviceResult->getErrorMessage());
                }
            }
            if ($babySitter->sub_merchant == null) {
                $serviceResult = $this->subMerchantService->insertIyzicoSubMerchant($babySitter->attributesToArray());
                if ($serviceResult->getStatus() == "failure") {
                    throw new \Exception($serviceResult->getErrorMessage());
                }
                $data = ['sub_merchant' => $serviceResult->getSubMerchantKey()];
                $this->userRepository->update($babySitter->id, $data);
            }
        }
    }


    private function saveProfilePhoto($photo): string
    {
        $fileName = time() . '_' . $photo->getClientOriginalName();
        return '/storage/' . $photo->storeAs('uploads', $fileName, 'public');
    }

    private function saveCriminalRecord($file): string
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return '/storage/' . $file->storeAs('uploads', $fileName, 'public');
    }

}
