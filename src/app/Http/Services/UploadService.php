<?php

namespace App\Http\Services;

use App\Helpers\Helper;
use App\Models\Attachment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;


class UploadService
{
    public function store($request)
    {

        // dd($request);
        try {
            $id_name_cate = Helper::getDataDbByName('attachment_categories', 'name', 'id');
            $filesUpload = $request->files;
            $nameControls = [];
            $tempMedia = [];
            $colNameMedia = [];
            foreach ($filesUpload as $key => $files) {
                array_push($nameControls, $key);
                try {
                    foreach ($files as $file) {
                        $fileName = Helper::customizeSlugData($file, 'attachments', $tempMedia);

                        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                        $fileNameNormal = pathinfo($fileName, PATHINFO_FILENAME);
                        $dt = Carbon::now('Asia/Ho_Chi_Minh');
                        $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                        $path_image = $path . $fileName;

                        Storage::disk('s3')->put($path_image, file_get_contents($file), 'public');
                        // dd($fileNameNormal, $save, file_get_contents($file), $path_image);

                        $imageFileTypeFrame = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                        if (in_array($imageFileType, $imageFileTypeFrame)) {
                            $thumbnailImage = Image::make($file);
                            $thumbnailImage->fit(150, 150);
                            $resource = $thumbnailImage->stream();
                            $fileNameThumbnail = $fileNameNormal . '-150x150.' . $imageFileType;
                            $path_thumbnail = $path . $fileNameThumbnail;
                            // dd($fileName);
                            Storage::disk('s3')->put($path_thumbnail, $resource->__toString(), 'public');
                        }

                        // dd($id_name_cate[$key);
                        array_push($tempMedia, [
                            'url_thumbnail' => isset($path_thumbnail) ? $path_thumbnail : "",
                            'url_media' => $path_image,
                            'url_folder' => $path,
                            'filename' => basename($path_image),
                            'extension' => $imageFileType,
                            'category' => $id_name_cate[$key],
                            'owner_id' =>  (int)Auth::user()->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    dd($e->getMessage());
                    return $e;
                }
            }

            // dd($tempMedia);
            foreach ($tempMedia as $key => $media) {

                $flip_name_id_cate = array_flip($id_name_cate);
                $newMedia = Attachment::create($media);
                $colNameMedia[$newMedia['id']] = $flip_name_id_cate[$media['category']];   // [id-media = "attachment-name"]
            }
            // dd($colNameMedia);
            return $colNameMedia;
        } catch (\Exception $e) {
            return  $e;
        }
    }
}
