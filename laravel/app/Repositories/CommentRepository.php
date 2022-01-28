<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\ICommentRepository;
use App\Models\BabySitterComment;

class CommentRepository implements ICommentRepository
{

    public function store(int $babySitterId, array $data)
    {
        $data['baby_sitter_id'] = $babySitterId;
        return BabySitterComment::create($data);
    }

    public function delete($commentId)
    {
        return BabySitterComment::destroy($commentId);
    }

    public function getBabySitterComments(int $babySitterId)
    {
        return BabySitterComment::babySitter($babySitterId)->get();
    }

    public function getBabySitterCommentsCount(int $babySitterId)
    {
        return BabySitterComment::babySitter($babySitterId)->count();

    }
}
