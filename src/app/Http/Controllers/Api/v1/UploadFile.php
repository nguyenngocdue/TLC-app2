<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\CreateEditControllerMedia;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Models\Attachment;
use App\Models\Attachment_category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFile extends Controller
{
    public function __construct(
        protected UploadService $uploadService,
    ) {
    }
    public function upload(Request $request)
    {
        try {
            $this->uploadService->store($request);
            return response()->json('oke');
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
