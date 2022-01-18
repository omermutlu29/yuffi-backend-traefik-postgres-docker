<?php


namespace App\Interfaces\IRepositories;


interface IUserRepository
{
    public function getUserByPhone(string $phone);

    public function save_sms_code(int $id, string $smsCode);

    public function create(array $data);

    public function update(int $id,array $data);

    public function get_last_sms_code($id, $code);
}
