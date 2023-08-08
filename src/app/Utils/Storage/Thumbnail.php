<?php
namespace App\Utils\Storage;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Thumbnail {
    public static function createThumbnailByOptions($height = 150,$width = 150,$position = 'top',$locationInput = 'input',$locationOutput ='output'){
        try {
             /** @var Storage $disk */
            $disk = Storage::disk('s3');
            $files = $disk->files($locationInput);
            foreach ($files as $file) {
                if (static::regexImage($file)) {
                    $fileContent = $disk->get($file);
                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                    $thumbnailImage = Image::make($fileContent);
                    $thumbnailImage->fit($width,$height,function ($constraint) {
                        $constraint->upsize();
                    },$position);
                    $resource = $thumbnailImage->stream();
                    $thumbnailFileName = $fileName . "-{$height}x{$width}." . $fileExtension;
                    $thumbnailPath = $locationOutput.'/' . $thumbnailFileName;
                    Storage::disk('s3')->put($thumbnailPath, $resource->__toString(), 'public');
                }
            }
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
    }
    public static function regexImage($file){
        return preg_match('/\.(jpg|jpeg|png|gif)$/i',$file);
    }
}