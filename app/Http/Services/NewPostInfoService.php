<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\INewPostInfo;

class NewPostInfoService implements INewPostInfo
{
    public function __construct($request,$file,$auth)
    {
        $this->request = $request;
        $this->file = $file;
        $this->auth = $auth;
    }

    public function getNewPostInfo()
    {
        if($this->request->session()->has('news.addPost.files'))
        {
            $filesSession = $this->request->session()->get('news.addPost.files');
            $userId = $this->auth::user()->id;
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