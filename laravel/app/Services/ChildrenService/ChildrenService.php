<?php


namespace App\Services\ChildrenService;

use App\Http\Resources\ChildResource;
use App\Interfaces\IRepositories\IChildrenRepository;
use App\Interfaces\IServices\IChildrenService;
use App\Models\ParentChild;
use App\Models\Parents;

class ChildrenService implements IChildrenService
{
    private IChildrenRepository $childrenRepository;

    public function __construct(IChildrenRepository $childrenRepository)
    {
        $this->childrenRepository = $childrenRepository;
    }

    public function store(Parents $parent, array $data)
    {
        $dbResult = $this->childrenRepository->storeMany($parent->id, $data);
        if (!$dbResult) throw new \Exception('Database process could not completed!', 502);
        return ChildResource::collection($dbResult);;
    }

    public function update(ParentChild $parentChild, array $data)
    {
        $dbResult = $this->childrenRepository->update($parentChild->id, $data);
        if (!$dbResult) throw new \Exception('Database process could not completed!', 502);
        return $dbResult;
    }

    public function delete(ParentChild $parentChild)
    {
        $result = $this->childrenRepository->delete($parentChild->id);
        if (!$result) throw new \Exception('Database process could not completed!', 502);
        return $result;
    }

    public function getChildren(Parents $parent)
    {
        $dbResult = $this->childrenRepository->getParentChildren($parent->id);
        if (!$dbResult) throw new \Exception('Database process could not completed!', 502);
        return $dbResult;
        return ChildResource::collection($dbResult);
    }
}
