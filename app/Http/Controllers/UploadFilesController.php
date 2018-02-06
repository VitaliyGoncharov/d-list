<?php
namespace App\Http\Controllers;

use App\Repositories\FileRepository;
use App\Http\Controllers\Controller;


class UploadFilesController extends Controller
{
	/**
	 ******************************************************************************************
	 *	Function saves file on path "downloads/user_id/hash/hash/hash.jpg" (any file extension)
	 *	
	 *	@param  $request
	 *	@return $path_from_root	(It's path where file was saved)
	 ******************************************************************************************
	 **/
	public function uploadFiles(FileRepository $fileRepository)
	{
		$user_id = request()->user()->id;
		
		$filename = $_FILES['userfile']['name'];

		$user_folder_path = __DIR__.'/../../../public/downloads/'.$user_id;

		// substr(strrchr($filename,'.'), 1);
		$info = new \SplFileInfo($filename);
		$extension = $info->getExtension();

		$first_lvl = $user_folder_path.'/'.substr(md5($filename),0,2);
		$second_lvl = $first_lvl.'/'.substr(md5($filename),2,2);

		$file_path = $second_lvl.'/'.substr(md5($filename),4,1).' '.date("H_i_s Y_m_d").'.'.$extension;
		$path_from_root = 'downloads/'.$user_id.'/'.substr(md5($filename),0,2).'/'.substr(md5($filename),2,2).'/'.substr(md5($filename),4,1).' '.date("H_i_s Y_m_d").'.'.$extension;

		if(!is_dir($user_folder_path))
		{
			mkdir($user_folder_path);
		}

		if(is_dir($user_folder_path))
		{
			if(!is_dir($first_lvl))
				mkdir($first_lvl);

			if(is_dir($first_lvl))
			{
				if(!is_dir($second_lvl))
					mkdir($second_lvl);

				if(is_dir($second_lvl))
				{
					copy($_FILES['userfile']['tmp_name'], $file_path);

					$i = 0;

                    if(request()->session()->has('news.addPost.files')) {
                        $files = request()->session()->get('news.addPost.files');
                        $i = count($files);
                    }

                    request()->session()->put('news.addPost.files.'.$i,$path_from_root);

                    $fileRepository->create([
                       'user_id' => $user_id,
                       'filename' => $filename,
                       'src' => $path_from_root
                    ]);

                    echo $path_from_root;
				}
			}
			
		}

	}
}