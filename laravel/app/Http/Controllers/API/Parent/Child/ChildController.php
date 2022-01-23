<?php

namespace App\Http\Controllers\API\Parent\Child;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\ParentChildRequest\DeleteChildRequest;
use App\Http\Requests\ParentChildRequest\StoreChildrenRequest;
use App\Http\Requests\ParentChildRequests\UpdateChildRequest;
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
            return $this->sendResponse($this->childrenService->view(\auth()->user()), 'You have successfully receive the children');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), null, $exception->getCode());
        }
    }

    public function store(StoreChildrenRequest $request)
    {
        try {
            return $this->childrenService->store(\auth()->user(), $request->only('children'));
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
