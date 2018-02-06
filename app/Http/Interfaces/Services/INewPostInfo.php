<?php
namespace App\Http\Interfaces\Services;

use App\Models\File;

interface INewPostInfo
{
    public function __construct(File $file);

    public function getNewPostInfo();
}