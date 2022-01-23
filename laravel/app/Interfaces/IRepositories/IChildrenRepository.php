<?php


namespace App\Interfaces\IRepositories;


interface IChildrenRepository
{
    public function storeMany(int $id,array $data);

    public function update(int $id,array $data);

    public function delete(int $id);

    public function getParentChildren(int $parentId);
}
