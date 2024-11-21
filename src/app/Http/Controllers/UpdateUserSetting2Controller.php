<?php

namespace App\Http\Controllers;

use App\Http\Services\UpdateUserSetting2Service;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateUserSetting2Controller extends Controller
{
    private UpdateUserSetting2Service $updateUserSettingService;

    public function updateEntityPerPage($key, $value)
    {
        $this->updateUserSettingService->set("$key", $value);
        return ResponseObject::responseSuccess(
            null,
            [],
            'EntityPerPage is Updated Successfully!'
        );
    }

    public function index(Request $request)
    {
        $action = $request->input('action');
        // $typePlural = $request->input('typePlural');
        $key = $request->input('key');
        $value = $request->input('value');

        if (!method_exists($this, $action)) {
            return ResponseObject::responseFail(
                "Method [$action] Not Found!",

            );
        }

        $this->updateUserSettingService = new UpdateUserSetting2Service();
        return $this->{$action}($key, $value);
    }
}
