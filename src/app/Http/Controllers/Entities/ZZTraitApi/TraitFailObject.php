<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Utils\System\Api\ResponseObject;
use Illuminate\Support\Facades\Log;

trait TraitFailObject
{
    function getFailObject($th)
    {
        $message = $th->getMessage();
        if (str_contains($message, 'Integrity constraint violation: 1062 Duplicate entry')) {
            Log::info($message);
            $message = "DUPLICATED: This document is exist, please refresh the page to load the latest matrix.";
        }
        return ResponseObject::responseFail($message,);
    }
}
