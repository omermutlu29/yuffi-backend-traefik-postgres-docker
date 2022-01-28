<?php


namespace App\Interfaces\IRepositories;


interface ICommentRepository
{
    public function store(int $babySitterId,array $data);
    public function delete($commentId);
    public function getBabySitterComments(int $babySitterId);
    public function getBabySitterCommentsCount(int $babySitterId);
}
