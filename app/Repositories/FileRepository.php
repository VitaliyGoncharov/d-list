<?php
namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function get($src)
    {
        $filename = $this->file
            ->where('src',$src)
            ->select('filename')
            ->first();

        return $filename;
    }

    public function create($data)
    {
        foreach($data as $key => $value)
        {
            $this->file->{$key} = $value;
        }
        $this->file->save();
    }
}