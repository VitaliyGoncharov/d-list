<?php
namespace App\Http\Interfaces\Services;

interface ICheckIfDisliked
{
    public function __construct($dislike);

    public function check(int $postId);
}