<?php
namespace App\Http\Interfaces\Services;

use App\Repositories\PostRepository;

interface IPost
{
    public function __construct(PostRepository $postRepository,IDateTime $IDateTime,IAttachment $IAttachment);

    public function get(int $num = 10, $lastPostId = null);
}