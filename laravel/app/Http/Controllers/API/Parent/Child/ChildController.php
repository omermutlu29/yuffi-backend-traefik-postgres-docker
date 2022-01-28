<?php

namespace App\Http\Controllers\API\Parent\Child;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Parent\ParentChildRequests\DeleteChildRequest;
use App\Http\Requests\Parent\ParentChildRequests\StoreChildrenRequest;
use App\Http\Requests\Parent\ParentChildRequests\UpdateChildRequest;
use App\Interfaces\IServices\IChildrenService;
use App\Models\ParentChild;

class ChildController extends BaseController
{
    private IChildrenService $childrenService;

    public function __construct(IChildrenService $childrenService)
    {
        $this->middleware('auth:parent');
        $this->childrenService = $childrenService;
    }

    public function index()
    {
        try {
            return $this->sendResponse($this->childrenService->getChildren(\auth()->user()), 'You have successfully receive the children');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function store(StoreChildrenRequest $request)
    {
        try {
            $children =($request->only('children'));
            return $this->sendResponse($this->childrenService->store(\auth()->user(),$children['children']),'Çocuklar eklendi!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }

    }

    public function update(UpdateChildRequest $request, ParentChild $child)
    {
        try {
            $data = $request->only('child_year_id', 'gender_id', 'disable');
            return $this->childrenService->update($child, $data);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function destroy(DeleteChildRequest $request, ParentChild $child)
    {
        try {
            return $this->childrenService->delete($child);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
