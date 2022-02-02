<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;
use Carbon\Carbon;

class ParentProfileService implements IProfileService
{
    private IUserRepository $parentRepository;

    public function __construct(IUserRepository $parentRepository)
    {
        $this->parentRepository = $parentRepository;
    }

    public function update(int $id, array $data)
    {
        $data['birthday'] = Carbon::make($data['birthday'])->format('Y-m-d');
        if (isset($data['photo'])) {
            $data['photo'] = self::saveProfilePhoto($data['photo']);
        }
        return $this->parentRepository->update($id, $data);
    }

    public function getProfile($id)
    {
        return ($this->parentRepository->getUserById($id));
    }

    private function saveProfilePhoto($photo): string
    {
        $fileName = time() . '_' . $photo->getClientOriginalName();
        return '/storage/' . $photo->storeAs('uploads', $fileName, 'public');
    }
}
