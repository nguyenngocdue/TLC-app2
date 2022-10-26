<?php

namespace App\Http\Services;

use App\Models\Media;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService
{
    public function store($request, $id)
    {

        $cateAttachment = DB::table('media_categories')->select('id', 'name')->get();

        $cateIdName = [];
        foreach ($cateAttachment as $key => $value) {
            $cateIdName[$value->name] = $value->id;
        }

        $filesUpload = $request->files;
        $nameControls = [];
        $medias = [];
        $controlsMedia = [];
        foreach ($filesUpload as $key => $file) {
            array_push($nameControls, $key);
            $fileName = $file->getClientOriginalName();
            $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileNameNormal = pathinfo($fileName, PATHINFO_FILENAME);
            $dt = Carbon::now('Asia/Ho_Chi_Minh');
            $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
            $path_image = $path . $fileName;
            Storage::disk('s3')->put($path_image, file_get_contents($file), 'public');
            $imageFileTypeFrame = ['jpeg', 'png', 'jpg', 'gif', 'svg'];

            if (in_array($imageFileType, $imageFileTypeFrame)) {
                $thumbnailImage = Image::make($file);
                $thumbnailImage->fit(150, 150);
                $resource = $thumbnailImage->stream();
                $fileNameThumbnail = $fileNameNormal . '-150x150.' . $imageFileType;
                $path_thumbnail = $path . $fileNameThumbnail;
                Storage::disk('s3')->put($path_thumbnail, $resource->__toString(), 'public');
            }
            array_push($medias, [
                'url_thumbnail' => isset($path_thumbnail) ? $path_thumbnail : "",
                'url_media' => $path_image,
                'url_folder' => $path,
                'filename' => basename($path_image),
                'extension' => $imageFileType,
                'category' => $cateIdName[$key],
                'owner_id' =>  (int)Auth::user()->id,
            ]);
        }
        // dd($medias);
        foreach ($medias as $key => $media) {
            $newMedia = Media::create($media);
            $controlsMedia[$nameControls[$key]] = $newMedia['id'];
        }
        return $controlsMedia;
    }
}
