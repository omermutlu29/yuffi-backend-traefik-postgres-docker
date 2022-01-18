<?php


namespace App\Services\LoginService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\ILogin;
use App\Services\NotificationServices\NetGSMSmsNotification;

class LoginService implements ILogin
{
    private $notificationService;
    private $userRepository;

    public function __construct(NetGSMSmsNotification $netGSMSmsNotification, IUserRepository $userRepository)
    {
        $this->notificationService = $netGSMSmsNotification;
        $this->userRepository = $userRepository;
    }

    public function login(array $data): bool
    {
        try {
            $user = $this->userRepository->getUserByPhone($data['phone']);
            if (!$user) {
                $user = $this->userRepository->create($data);
            }
            $code = self::generateSmsCode();
            $this->userRepository->save_sms_code($user->id, $code);
            if (env('APP_ENV')=='local'){
                return true;
            }
            return $this->notificationService->notify("ONAY SMS'i", $code, $user->phone);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function loginVerifier(array $data): array
    {
        $return = ['status' => false];
        try {
            $user = $this->userRepository->getUserByPhone($data['phone']);
            if ($user && $this->userRepository->get_last_sms_code($user->id, $data['code'])) {
                $return['status'] = true;
                $return['token'] = $user->createToken('user')->accessToken;
                $return['user'] = $user;
            }
            return $return;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private static function generateSmsCode(): int
    {
        return 1111;
    }
}
