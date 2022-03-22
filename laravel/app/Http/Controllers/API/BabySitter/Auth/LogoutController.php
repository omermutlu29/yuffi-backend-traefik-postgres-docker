<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Interfaces\IServices\ILoginService;


class LogoutController extends BaseController
{
    private ILoginService $loginService;

    public function __construct(ILoginService $loginService)
    {
        $this->middleware('auth:baby_sitter');
        $this->loginService = $loginService;
    }

    public function logout()
    {
        $this->loginService->logout(auth()->user());
    }

}