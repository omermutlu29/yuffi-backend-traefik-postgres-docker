<?php


namespace App\Interfaces\IServices;


use App\Interfaces\IRepositories\IUserRepository;

interface ILoginService
{
    public function login(array $data, IUserRepository $userRepository);

    public function loginVerifier(array $data, IUserRepository $userRepository);

    public function logout($user);
}
