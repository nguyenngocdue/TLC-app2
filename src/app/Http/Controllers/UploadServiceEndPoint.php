<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService2;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadServiceEndPoint extends Controller
{
    function __construct() {}

    public function upload(Request $request)
    {
        // $cu = CurrentUser::get();
        // Log::info('UploadServiceEndPoint.upload', ['user' => $cu->id]);

        $object_type = $request->input('object_type');
        $object_id = $request->input('object_id');

        $uploadService2 = new UploadService2($object_type);
        $inserted = $uploadService2->store($request, $object_type, $object_id);

        return response()->json($inserted);
    }
}
