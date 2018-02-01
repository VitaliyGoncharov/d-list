<?php
namespace App\Http\Interfaces\Services;

interface INewPostInfo
{
    public function __construct($request,$file,$auth);

    public function getNewPostInfo();
}