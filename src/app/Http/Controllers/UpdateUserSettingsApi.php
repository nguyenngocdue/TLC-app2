<?php

namespace App\Http\Controllers;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

class UpdateUserSettingsApi extends Controller
{
    public function updateUserSettingsPerPageApi(Request $request)
    {
        try {
            $entity = $request->input('entity');
            $perPage = $request->input('per_page');
            $key = $request->input('key');
            if ($entity) {
                $user = CurrentUser::get();
                $settings = $user->settings;
                if ($key) {
                    $settings[$entity][Constant::VIEW_ALL]['matrix'][$key]['per_page'] = $perPage;
                } else {
                    $settings[$entity][Constant::VIEW_ALL]['per_page'] = $perPage;
                }
                $user->settings = $settings;
                $user->update();
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Updated User Setting Per Page Successfully (#416)'
                );
            }
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    public function updateUserSettingsApi(Request $request)
    {
        try {
            $entity = $request->input('entity');
            $value = $request->input('show_all_children');
            if ($entity) {
                $user = CurrentUser::get();
                $settings = $user->settings;
                $settings[$entity][Constant::VIEW_ALL]['calendar_options']['show_all_children'] = $value;
                $user->settings = $settings;
                $user->update();
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Updated User Setting Successfully!'
                );
            }
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    public function updateUserSettingsFilterApi(Request $request)
    {
        try {
            $entity = $request->input('entity');
            $value = $request->input('current_filter');
            if ($entity) {
                $user = CurrentUser::get();
                $settings = $user->settings;
                $settings[$entity][Constant::VIEW_ALL]['current_filter'] = $value;
                $user->settings = $settings;
                $user->update();
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Updated User Setting Successfully!'
                );
            }
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
