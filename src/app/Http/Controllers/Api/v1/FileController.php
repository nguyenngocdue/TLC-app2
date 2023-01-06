<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\UploadService;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{

    public function upload(Request $request)
    {
        try {
            $modelName = $request->modelName;
            $modelId = $request->modelId;
            $nameIdFields = Helper::getDataDbByName('fields', 'name', 'id');
            $filesUpload = $request->files;
            $attachments = [];
            foreach ($filesUpload as $key => $files) {
                try {
                    foreach ($files as $file) {
                        $fileName = Helper::customizeSlugData($file, 'attachments', $attachments);
                        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                        $dt = Carbon::now('Asia/Ho_Chi_Minh');
                        $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                        $pathImage = $path . $fileName;
                        Storage::disk('s3')->put($pathImage, file_get_contents($file), 'public');
                        $pathThumbnail = $this->createThumbnailGiveImage($imageFileType, $file, $fileName, $path);
                        array_push($attachments, [
                            'url_thumbnail' => isset($pathThumbnail) ? $pathThumbnail : "",
                            'url_media' => $pathImage,
                            'url_folder' => $path,
                            'filename' => basename($pathImage),
                            'extension' => $imageFileType,
                            'category' => $nameIdFields[$key],
                            'owner_id' =>  (int)Auth::user()->id,
                            'object_id' => (int)$modelId,
                            'object_type' => $modelName,
                        ]);
                    }
                    $this->createAttachment($attachments);
                    return response()->json('Completed');
                } catch (\Exception $e) {
                    return response()->json($e);
                }
            }
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function edit(Request $request)
    {
        if (is_null($request->attachmentId)) {
            try {
                $modelName = $request->modelName;
                $modelId = $request->modelId;
                $nameIdFields = Helper::getDataDbByName('fields', 'name', 'id');
                $filesUpload = $request->files;
                $attachments = [];
                foreach ($filesUpload as $key => $files) {
                    try {
                        foreach ($files as $file) {
                            $fileName = Helper::customizeSlugData($file, 'attachments', $attachments);
                            $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                            $dt = Carbon::now('Asia/Ho_Chi_Minh');
                            $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                            $pathImage = $path . $fileName;
                            Storage::disk('s3')->put($pathImage, file_get_contents($file), 'public');
                            $pathThumbnail = $this->createThumbnailGiveImage($imageFileType, $file, $fileName, $path);
                            array_push($attachments, [
                                'url_thumbnail' => isset($pathThumbnail) ? $pathThumbnail : "",
                                'url_media' => $pathImage,
                                'url_folder' => $path,
                                'filename' => basename($pathImage),
                                'extension' => $imageFileType,
                                'category' => $nameIdFields[$key],
                                'owner_id' =>  (int)Auth::user()->id,
                                'object_id' => (int)$modelId,
                                'object_type' => $modelName,
                            ]);
                        }
                        $this->createAttachment($attachments);
                        return response()->json('Completed');
                    } catch (\Exception $e) {
                        return response()->json($e);
                    }
                }
            } catch (\Throwable $th) {
                return response()->json($th);
            }
        } else {
            $attachmentId = $request->attachmentId;
            $attachment = Attachment::find($attachmentId);
            Storage::disk('s3')->delete($attachment->getAttributes()['url_thumbnail']);
            Storage::disk('s3')->delete($attachment->getAttributes()['url_media']);
            $filesUpload = $request->files;
            foreach ($filesUpload as $key => $files) {
                try {
                    foreach ($files as $file) {
                        $fileName = Helper::customizeSlugData($file, 'attachments', []);
                        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                        $dt = Carbon::now('Asia/Ho_Chi_Minh');
                        $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                        $pathImage = $path . $fileName;
                        Storage::disk('s3')->put($pathImage, file_get_contents($file), 'public');
                        $this->createThumbnailGiveImage($imageFileType, $file, $fileName, $path);
                    }
                    return response()->json('Completed');
                } catch (\Exception $e) {
                    return response()->json($e);
                }
            }
        }
    }
    public function delete(Request $request)
    {
        $attachmentId = $request->id;
        $attachment = Attachment::find($attachmentId);
        Storage::disk('s3')->delete($attachment->getAttributes()['url_thumbnail']);
        Storage::disk('s3')->delete($attachment->getAttributes()['url_media']);
        return response()->json('Successfully');
    }
    /**
     * createThumbnailGiveImage
     *
     * @param  mixed $imageFileType Type of image file
     * @param  mixed $file Image file
     * @param  mixed $fileName Name Image file
     * @param  mixed $path Link Path image file
     * @param  mixed $imageFileTypeFrame Type target image file type
     * @return void
     */
    private function createThumbnailGiveImage($imageFileType, $file, $fileName, $path, $imageFileTypeFrame = ['jpeg', 'png', 'jpg', 'gif', 'svg'])
    {
        $fileNameNormal = pathinfo($fileName, PATHINFO_FILENAME);
        if (in_array($imageFileType, $imageFileTypeFrame)) {
            $thumbnailImage = Image::make($file);
            $thumbnailImage->fit(150, 150);
            $resource = $thumbnailImage->stream();
            $fileNameThumbnail = $fileNameNormal . '-150x150.' . $imageFileType;
            $pathThumbnail = $path . $fileNameThumbnail;
            Storage::disk('s3')->put($pathThumbnail, $resource->__toString(), 'public');
        }
        return $pathThumbnail;
    }
    /**
     * createAttachment
     *
     * @param  mixed $attachments 
     * @return void
     */
    private function createAttachment($attachments)
    {
        foreach ($attachments as $attachment) {
            Attachment::create($attachment);
        }
    }
}
