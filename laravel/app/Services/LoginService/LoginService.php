<?php


namespace App\Services\LoginService;


use App\Interfaces\IRepositories\IUserRepository;
use App\Interfaces\IServices\ILoginService;
use App\Interfaces\NotificationInterfaces\INotification;

class LoginService implements ILoginService
{
    private INotification $notificationService;

    public function __construct(INotification $notification)
    {
        $this->notificationService = $notification;
    }

    public function login(array $data, IUserRepository $userRepository): bool
    {
        try {
            $user = $userRepository->getUserByPhone($data['phone']);
            if (!$user) {
                $user = $userRepository->create($data);
            }
            $code = self::generateSmsCode();
            $userRepository->save_sms_code($user->id, $code);
            if (env('APP_ENV') == 'local') {
                return true;
            }
            return $this->notificationService->notify("ONAY SMS'i", $code, $user->phone);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function loginVerifier(array $data, IUserRepository $userRepository): array
    {
        $return = ['status' => false];
        try {
            $user = $userRepository->getUserByPhone($data['phone']);
            if ($user && $userRepository->get_last_sms_code($user->id, $data['code'])) {
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
