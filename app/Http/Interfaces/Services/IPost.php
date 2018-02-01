<?php
namespace App\Http\Interfaces\Services;

interface IPost
{
    public function __construct($post,$file,$auth);

    public function get(int $num = 10, $lastPostId = false);
}