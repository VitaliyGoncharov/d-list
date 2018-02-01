<?php
namespace App\Http\Interfaces\Services;

interface IComment
{
    public function __construct($comment);

    public function getComments(int $id, int $num = 1);
}