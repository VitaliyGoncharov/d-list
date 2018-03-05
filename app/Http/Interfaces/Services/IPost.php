<?php
namespace App\Http\Interfaces\Services;

use App\Repositories\PostRepository;

interface IPost
{
    public function get(int $num = 10, $lastPostId = null);
}