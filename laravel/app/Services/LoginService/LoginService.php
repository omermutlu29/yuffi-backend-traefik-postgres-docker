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
            $data["kvkk"] = true;

            if (!$user) {
                $user = $userRepository->create($data);
            }
            $code = self::generateSmsCode();
            $userRepository->save_sms_code($user->id, $code);
            if (env('APP_ENV') == 'local') {
                return true;
            }
            return $this->notificationService->notify([], "ONAY SMS'i", $code, $user->phone);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function loginVerifier(array $data, IUserRepository $userRepository): array
    {
        $return = ['status' => false];
        try {
            $user = $userRepository->getUserByPhone($data['phone']);
            if (!$user) {
                throw new \Exception('User could not find!', 400);
            }
            if (!$userRepository->get_last_sms_code($user->id, $data['code'])) {
                throw new \Exception('SMS code does not match', 400);
            }
            if (\request()->ip()) {
                $user->network = \request()->ip();
            }
            $return['status'] = true;
            $return['token'] = $user->createToken('user')->accessToken;
            $return['user'] = $user;
            $userRepository->update($user->id, ['google_st' => $data['google_st']]);
            return $return;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private static function generateSmsCode(): int
    {
        return 1111;
    }

    public function logout($user)
    {
        $user->google_st = null;
        $user->save();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
    }
}
