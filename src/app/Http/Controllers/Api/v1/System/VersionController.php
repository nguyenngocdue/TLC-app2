<?php

namespace App\Http\Controllers\Api\v1\System;

use App\Http\Controllers\Controller;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Support\Facades\Blade;

class VersionController extends Controller
{
    public function version()
    {
        $version = config('version.app_version');
        return ResponseObject::responseSuccess(
            $version,
            [
                'ws_client_id' => uniqid(),
            ],
            "Get version app successfully!"
        );
    }
}
