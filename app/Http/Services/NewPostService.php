<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\INewPostInfo;
use App\Models\File;

class NewPostService implements INewPostInfo
{
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getNewPostInfo()
    {
        if(request()->session()->has('news.addPost.files'))
        {
            $filesSession = request()->session()->get('news.addPost.files');
            $userId = request()->user()->id;
            // $images will contain all images
            // $files won't contain images
            $images = [];
            $files = [];
            $img_extensions = ['jpg','jpeg'];

            for($i = 0;$i<count($filesSession);$i++)
            {
                $filepath = $filesSession[$i];

                // explode filepath on '.'
                $filepath_arr = explode('.',$filepath);

                // extension is the last element in array
                $extension = end($filepath_arr);

                $isImage = false;

                for($j = 0;$j<count($img_extensions);$j++)
                {
                    if($extension==$img_extensions[$j])
                    {
                        $isImage = true;
                        break;
                    }
                }

                if($isImage)
                {
                    array_push($images,$filepath);
                }
                else
                {
                    $filename = $this->file->where([
                        ['user_id',$userId],
                        ['src',$filepath]
                    ])->select('filename')->get();


                    $file = [
                        'src' => $filepath,
                        'name' => $filename[0]->filename
                    ];


                    array_push($files,$file);
                }
            }

            return [
                'files' => $files,
                'images' => $images
            ];
        }
    }
}