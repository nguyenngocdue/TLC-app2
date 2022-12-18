<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;

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
