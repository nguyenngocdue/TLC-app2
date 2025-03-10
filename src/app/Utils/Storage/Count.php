<?php


namespace App\Utils\Storage;

use Illuminate\Support\Facades\Storage;

class Count
{
    public static function getCount($location = 'app2_prod')
    {
        /** @var Storage $disk */
        $disk = Storage::disk('s3');
        $size = array_sum(array_map(function ($file) {
            return 1;
            // dump($file);
            // return (int)$file['size'];
        }, array_filter($disk->listContents($location, true /*<- recursive*/)->toArray(), function ($file) {
            return $file['type'] == 'file';
        })));
        return $size;
    }
}
