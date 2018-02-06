<?php
namespace App\Http\Interfaces\Services;

interface ICheckIfLiked
{
    public function __construct($like);

    public function check(int $postId);
}