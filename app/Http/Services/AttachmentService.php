<?php
namespace App\Http\Services;

use App\Models\File;

class AttachmentService
{
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function get($post)
    {
        $attachments = new \stdClass();

        //for photos we need only src
        $attachments->photos = json_decode($post->photos);

        // for files we need not only src, we also need filename
        $attachments->files = json_decode($post->files);

        if(!empty($attachments->files))
        {
            $attachmentsWithFilename = array();

            foreach($attachments->files as $file)
            {
                $filename = $this->file->get($file)->filename;

                $attachmentWithFilename = [
                    'src' => $file,
                    'name' => $filename
                ];

                array_push($attachmentsWithFilename,$attachmentWithFilename);
            }

            $attachments->files = $attachmentsWithFilename;
        }

        return $attachments;
    }
}