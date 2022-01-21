<?php


namespace App\Http\Controllers\API\Parent\Auth;


use App\Http\Controllers\API\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\ParentResource;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\ILoginService;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    private ILoginService $loginService;
    private IUserRepository $userRepository;

    public function __construct(ILoginService $loginService, IUserRepository $userRepository)
    {
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


    public function loginTwo(Request $request)
    {
        try {
            $result = $this->loginService->loginVerifier($request->only('phone', 'code'), $this->userRepository);
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
