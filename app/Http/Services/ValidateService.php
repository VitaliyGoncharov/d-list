<?php
namespace App\Http\Services;

class ValidateService
{
    public function validatePostId($postId)
    {
        $postIdIsNumber = preg_match('~^[\d]+$~',$postId);

        return $postIdIsNumber ? true : false;
    }
}