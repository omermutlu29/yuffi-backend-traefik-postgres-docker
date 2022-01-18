<?php


namespace App\Http\Controllers\API\BabySitter\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginRequestVerify;
use App\Http\Resources\BabySitterResource;
use App\Services\LoginService\LoginService;

class LoginController extends BaseController
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->middleware('auth:baby_sitter', ['except' => ['loginOne', 'loginTwo']]);
        $this->loginService = $loginService;
    }

    public function loginOne(LoginRequest $request)
    {
        try {
            if ($this->loginService->login($request->only('phone', 'code'))) {
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
            $result = $this->loginService->loginVerifier($request->only('phone', 'code'));
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
