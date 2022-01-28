<?php


namespace App\Interfaces\IServices;


interface IProfileService
{
    public function update(int $id,array $data);
    public function getProfile($id);
}
