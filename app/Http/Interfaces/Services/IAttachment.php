<?php
namespace App\Http\Interfaces\Services;

use App\Repositories\FileRepository;

interface IAttachment
{
    public function __construct(FileRepository $fileRepository);

    public function get($posts);
}