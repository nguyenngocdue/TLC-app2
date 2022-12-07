<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\CreateEditControllerMedia;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadFileQaqc extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
        protected ReadingFileService $readingFileService
    ) {
    }
    public function uploadFileQaqc(Request $request)
    {
        $dir = "test/";
        $images = $request->files;
        if (count($images) > 0) {
            return response()->json(['message' => $images], 200);
            $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . "png";
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            foreach ($images as $image) {
                Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
            }
        } else {
            return response()->json(['message' => trans('/storage/test/' . 'def.png')], 200);
        }

        $userDetails = [

            'image' => $imageName,

        ];
        return response()->json(['message' => trans('/storage/test/' . $imageName)], 200);
    }
}
