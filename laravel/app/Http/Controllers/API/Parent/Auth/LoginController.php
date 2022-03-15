<?php


namespace App\Http\Controllers\API\Parent\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\LoginRequests\LoginRequest;
use App\Http\Requests\LoginRequests\LoginRequestVerify;
use App\Http\Resources\ParentResource;
use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\ILoginService;

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
            if ($this->loginService->login($request->only('phone', 'kvkk', 'google_st'), $this->userRepository)) {
                $success['result'] = 'Telefonunuza SMS Gönderildi';
                return $this->sendResponse($success, 'Telefonunuza SMS Gönderildi');
            }
            return $this->sendError(false, ['Birşeyler ters gitti!']);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), ['message' => [$exception->getMessage()]], 400);
        }
    }


    public function loginTwo(LoginRequestVerify $request)
    {
        //try {
            $result = $this->loginService->loginVerifier($request->only('phone', 'code','google_st'), $this->userRepository);
            if ($result['status'] != false) {
                $success['accepted'] = $result['status'];
                $success['user'] = (ParentResource::make($result['user']));
                $success['token'] = $result['token'];


                return $success;
            }
        /*} catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), ['message' => [$exception->getMessage()]], 400);
        }*/
    }


}
