<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginRequestVerify;
use App\Http\Resources\BabySitterResource;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\ILoginService;

class LoginController extends BaseController
{
    private ILoginService $loginService;
    private IUserRepository $userRepository;

    public function __construct(ILoginService $loginService, IUserRepository $userRepository)
    {
        $this->middleware('auth:baby_sitter', ['except' => ['loginOne', 'loginTwo']]);
        $this->loginService = $loginService;
        $this->userRepository = $userRepository;
    }

    public function loginOne(LoginRequest $request)
    {
        try {
            if ($this->loginService->login($request->only('phone', 'code'),$this->userRepository)) {
                $success['result'] = 'Telefonunuza SMS Gönderildi';
                return $this->sendResponse($success, 'Telefonunuza SMS Gönderildi');
            }
            return $this->sendError(false, ['Birşeyler ters gitti!']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function loginTwo(LoginRequestVerify $request)
    {
        try {
            $result = $this->loginService->loginVerifier($request->only('phone', 'code'),$this->userRepository);
            if ($result['status'] != false) {
                $success['accepted'] = $result['status'];
                $success['baby_sitter'] = BabySitterResource::make($result['user']);
                $success['token'] = $result['token'];
                return $this->sendResponse($success, 'Başarılı bir şekilde giriş yapıldı');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
