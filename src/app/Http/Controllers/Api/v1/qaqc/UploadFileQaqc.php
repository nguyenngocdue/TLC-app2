<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\CreateEditControllerMedia;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadFileQaqc extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
        protected ReadingFileService $readingFileService
    ) {
    }
    public function uploadFileQaqc(Request $request)
    {
        if ($request->hasFile('files')) {
        }
        return response()->json(["abc" => Auth::user()->id]);
    }
}
