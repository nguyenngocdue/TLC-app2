<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\Storage\Thumbnail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ThumbnailController extends Controller
{
    public function create(Request $request)
    {
        try {
            $results = Thumbnail::createThumbnailByOptions();
            if($request == true){
                Toastr::success('Create thumbnails all files successfully', 'Create Thumbnail on S3 Minio');
                return redirect()->back();
            }else{
                Toastr::success($results, 'Create Thumbnail on S3 Minio');
            }
        } catch (\Throwable $th) {
            Toastr::success($th->getMessage(), 'Create Thumbnail on S3 Minio');
        }
    }
}
