<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\UploadService;
use App\Models\Attachment;
use App\Utils\Support\AttachmentName;
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

        $res = $this->uploadFile($request);
        return response()->json($res);
    }

    public function edit(Request $request)
    {
        if (is_null($request->inspPhotoId)) {
            $res = $this->uploadFile($request);
            return response()->json($res);
        } else {
            $attachmentId = $request->inspPhotoId;
            $attachment = Attachment::find($attachmentId);
            if ($attachment) {
                Storage::disk('s3')->delete($attachment->getAttributes()['url_thumbnail']);
                Storage::disk('s3')->delete($attachment->getAttributes()['url_media']);
                $filesUpload = $request->files;
                foreach ($filesUpload as $files) {
                    try {
                        foreach ($files as $file) {
                            $fileName = Helper::getFileNameNormal($file);
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
            } else {
                return response()->json([
                    'message' => 'Attachment does not exist'
                ]);
            }
        }
    }
    public function delete(Request $request)
    {
        $attachmentId = $request->id;
        $attachment = Attachment::find($attachmentId);
        Storage::disk('s3')->delete($attachment->getAttributes()['url_thumbnail']);
        Storage::disk('s3')->delete($attachment->getAttributes()['url_media']);
        $attachment->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ]);
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
    private function uploadFile($request)
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
                        $fileName = AttachmentName::slugifyImageName($file,  $attachments);
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
                    return [
                        'message' => 'Successfully',
                    ];
                } catch (\Exception $e) {
                    return [
                        'message' => $e->getMessage(),
                    ];
                }
            }
        } catch (\Throwable $th) {
            return [
                'message' => $th,
            ];
        }
    }
}
