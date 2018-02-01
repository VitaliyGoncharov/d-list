<?php
namespace App\Http\Interfaces\Services;

interface ICheckIfDisliked
{
    public function __construct($dislike,$auth);

    public function check(int $postId);
}