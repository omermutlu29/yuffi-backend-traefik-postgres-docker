<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\ParentResource;
use App\Services\LoginService\LoginService;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
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


    public function loginTwo(Request $request)
    {
        try {
            $result = $this->loginService->loginVerifier($request->only('phone', 'code'));
            if ($result['status'] != false) {
                $success['accepted'] = $result['status'];
                $success['user'] = ParentResource::make($result['user']);
                $success['token'] = $result['token'];
                return $this->sendResponse($success, 'Başarılı bir şekilde giriş yapıldı');
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }


}
