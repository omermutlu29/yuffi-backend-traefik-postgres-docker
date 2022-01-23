<?php


namespace App\Interfaces\IServices;


use App\Models\ParentChild;
use App\Models\Parents;

interface IChildrenService
{
    public function store(Parents $parent, array $data);

    public function update(ParentChild $parentChild, array $data);

    public function delete(ParentChild $parentChild);

    public function getChildren(Parents $parent);

}
