<?php
namespace App\Http\Interfaces\Services;

interface ICheckIfLiked
{
    public function __construct($like,$auth);

    public function check(int $postId);
}