<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IChildrenRepository;
use App\Interfaces\IRepositories\IUserRepository;
use App\Models\ParentChild;
use App\Services\HttpStatuses\HttpStatuses;

class ChildrenRepository implements IChildrenRepository
{
    private IUserRepository $parentRepository;

    public function __construct(IUserRepository $parentRepository)
    {
        $this->parentRepository = $parentRepository;
    }

    public function storeMany(int $id, array $data)
    {
        $user = $this->parentRepository->getUserById($id);
        $created = $user->parent_children()->createMany($data);
        if (!$created) throw new \Exception('Children could not inserted', HttpStatuses::HTTP_BAD_GATEWAY);
        return $created;
    }

    public function update(int $id, array $data)
    {
        try {
            $child = self::getById($id);
            $updated = $child->update($data);
            if (!$updated) throw new \Exception('Children could not updated', HttpStatuses::HTTP_BAD_GATEWAY);
            return $updated;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $child = self::getById($id);
            $deleted = $child->delete();
            if (!$deleted) throw new \Exception('Children could not deleted', HttpStatuses::HTTP_BAD_GATEWAY);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getParentChildren(int $parentId)
    {
        try {
            $children = ParentChild::byParent($parentId)->get();
            if (!$children) throw new \Exception('Children could not received', HttpStatuses::HTTP_BAD_GATEWAY);
            return $children;
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function getById(int $id)
    {
        $child = ParentChild::find($id);
        if (!$child) throw new \Exception('Children could not find', HttpStatuses::HTTP_BAD_REQUEST);
        return $child;
    }
}
