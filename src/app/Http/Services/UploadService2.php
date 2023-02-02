<?php

namespace App\Http\Services;

use App\Helpers\Helper;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService2
{
    public function store($request)
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
        try {
            $fields = Helper::getDataDbByName('fields', 'name', 'id');
            $filesUpload = $request->files;
            $attachmentRows = [];
            $colNameMedia = [];
            foreach ($filesUpload as $fieldName => $files) {
                $files = $files['toBeUploaded'];
                foreach ($files as $file) {
                    $fileName = Helper::customizeSlugData($file, 'attachments', $attachmentRows);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME);
                    $mimeType = $file->getMimeType();
                    $path_image = $path . $fileName;

                    Storage::disk('s3')->put($path_image, file_get_contents($file), 'public');
                    // dd($fileName, $save, file_get_contents($file), $path_image);

                    $imageFileTypeFrame = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                    //Only crunch if the attachment is a photo
                    if (in_array($fileExt, $imageFileTypeFrame)) {
                        $thumbnailImage = Image::make($file);
                        $thumbnailImage->fit(150, 150);
                        $resource = $thumbnailImage->stream();
                        $thumbnailFileName = $fileName . '-150x150.' . $fileExt;
                        $thumbnailPath = $path . $thumbnailFileName;
                        // dd($fileName);
                        Storage::disk('s3')->put($thumbnailPath, $resource->__toString(), 'public');
                    }

                    // dd($fields[$fieldName);
                    array_push($attachmentRows, [
                        'url_thumbnail' => isset($thumbnailPath) ? $thumbnailPath : "",
                        'url_media' => $path_image,
                        'url_folder' => $path,
                        'filename' => basename($path_image),
                        'extension' => $fileExt,
                        'category' => $fields[$fieldName],
                        'owner_id' =>  (int)Auth::user()->id,
                        'mime_type' => $mimeType,
                    ]);
                }
            }

            // dd($attachmentRows);
            $invertedFields = array_flip($fields);
            foreach ($attachmentRows as $row) {
                $insertedRow = Attachment::create($row);
                $colNameMedia[$insertedRow['id']] = $invertedFields[$row['category']];   // [id-media = "attachment-name"]
            }
            // dd($colNameMedia);
            return $colNameMedia;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return  $e;
        }
    }
}
