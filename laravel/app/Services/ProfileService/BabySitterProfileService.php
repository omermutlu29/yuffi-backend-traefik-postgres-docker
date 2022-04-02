<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IChangableActiveStatus;
use App\Interfaces\IServices\IProfileService;
use App\Models\BabySitter;
use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;


class BabySitterProfileService implements IProfileService, IChangableActiveStatus
{
    private IUserRepository $userRepository;
    private IBabySitterRepository $babySitterRepository;

    public function __construct(IUserRepository $userRepository, IBabySitterRepository $babySitterRepository)
    {
        $this->userRepository = $userRepository;
        $this->babySitterRepository = $babySitterRepository;
    }

    #[ArrayShape(['status' => "bool", 'message' => "mixed|string"])]
    public function updateBasicInformation(BabySitter $babySitter, array $data): array
    {
        $result = ['status' => true, 'message' => 'İşlem başarılı'];
        try {
            if (isset($data['birthday']))
                $data['birthday'] = Carbon::createFromFormat('d/m/Y', ($data['birthday']));
            if (isset($data['photo']))
                $data['photo'] = self::saveProfilePhoto($data['photo']);
            if (isset($data['criminal_record']))
                $data['criminal_record'] = self::saveCriminalRecord($data['criminal_record']);
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
            $childYears = $validatedData['relational_preferences']['child_years'] ?? [];
            $shareableTalents = $validatedData['relational_preferences']['shareable_talents'] ?? [];
            $babySitter->update($validatedData['base_preferences']);
            $towns ? $this->babySitterRepository->updateAvailableTowns($babySitter, $towns) : null;
            $acceptedLocations ? $this->babySitterRepository->updateAcceptedLocations($babySitter, $acceptedLocations) : null;
            $shareableTalents ? $this->babySitterRepository->updateShareableTalents($babySitter, $shareableTalents) : $this->babySitterRepository->removeShareableTalents($babySitter);
            $childYears ? $this->babySitterRepository->updateChildYears($babySitter, $childYears) : null;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getProfile($id)
    {
        $relations = ['shareable_talents', 'child_years:id,name', 'gender:id,name', 'child_gender:id,name', 'accepted_locations', 'available_towns'];
        return $this->userRepository->getUserWithRelations($id, $relations);
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
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

    public function changeActiveStatus(int $userId)
    {
        try {
            $user = $this->userRepository->getUserById(auth()->id());
            return $this->userRepository->update($userId, ['is_active' => !$user->is_active]);
        } catch (\Exception $exception) {
            throw new \Exception('Durum değiştirilemedi', 400);
        }
    }
}
