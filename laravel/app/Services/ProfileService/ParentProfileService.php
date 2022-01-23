<?php


namespace App\Services\ProfileService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\IProfileService;

class ParentProfileService implements IProfileService
{
    private IUserRepository $parentRepository;

    public function __construct(IUserRepository $parentRepository)
    {
        $this->parentRepository=$parentRepository;
    }

    public function update(int $id,array $data){
        return $this->parentRepository->update($id,$data);
    }

    public function getProfile($id){
        return $this->parentRepository->getUserWithRelations($id,['parent_children']);
    }
}
