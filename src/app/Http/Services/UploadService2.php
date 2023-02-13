<?php

namespace App\Http\Services;

use App\Helpers\Helper;
use App\Models\Attachment;
use App\Utils\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService2
{
    public function destroy($toBeDeletedIds)
    {
        try {
            foreach ($toBeDeletedIds as $id) {
                $attachment = Attachment::find($id);
                Storage::disk('s3')->delete($attachment->url_thumbnail);
                Storage::disk('s3')->delete($attachment->url_media);
                $attachment->delete();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function store($request)
    {
        $thumbnailW = 150;
        $thumbnailH = 150;
        $allowedExts = ['jpeg', 'png', 'jpg', 'gif', 'webp'];

        $path = env('MEDIA_ROOT_FOLDER', 'media') . "/" . date(Constant::FORMAT_YEAR_MONTH) . "/";
        try {
            $fields = Helper::getDataDbByName('fields', 'name', 'id');
            $filesUpload = $request->files;
            $attachmentRows = [];
            foreach ($filesUpload as $fieldName => $files) {
                $files = $files['toBeUploaded'];
                foreach ($files as $file) {
                    $fileName = Helper::customizeSlugData($file, 'attachments', $attachmentRows);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                    $mimeType = $file->getMimeType();
                    $imagePath = $path . $fileName;

                    Storage::disk('s3')->put($imagePath, file_get_contents($file), 'public');
                    // dump($fileName, $imagePath, $mimeType);

                    //Only crunch if the attachment is a photo
                    if (in_array($fileExt, $allowedExts)) {
                        $thumbnailImage = Image::make($file);
                        $thumbnailImage->fit($thumbnailW, $thumbnailH);
                        $resource = $thumbnailImage->stream();
                        $thumbnailFileName = $fileNameWithoutExt . "-{$thumbnailW}x{$thumbnailH}." . $fileExt;
                        $thumbnailPath = $path . $thumbnailFileName;
                        Storage::disk('s3')->put($thumbnailPath, $resource->__toString(), 'public');
                    }
                    // dd($fields[$fieldName);
                    array_push($attachmentRows, [
                        'url_thumbnail' => isset($thumbnailPath) ? $thumbnailPath : "",
                        'url_media' => $imagePath,
                        'url_folder' => $path,
                        'filename' => basename($imagePath),
                        'extension' => $fileExt,
                        'category' => $fields[$fieldName],
                        'owner_id' =>  (int)Auth::user()->id,
                        'mime_type' => $mimeType,
                    ]);
                }
            }

            // dd($attachmentRows);
            $invertedFields = array_flip($fields);
            $result = [];
            foreach ($attachmentRows as $row) {
                $insertedRow = Attachment::create($row);
                $result[$insertedRow['id']] = $invertedFields[$row['category']];
            }
            // dd($result);
            return $result;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
