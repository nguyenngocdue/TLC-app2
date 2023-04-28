<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function updateBookmark(Request $request)
    {
        $entity = $request->input('entity');
        if ($entity) {
            $user = User::find(Auth::id());
            $settings = $user->settings;
            if (!isset($settings['bookmark_search'])) {
                $settings['bookmark_search'] = [$entity];
                $user->settings = $settings;
                $user->update();
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Add bookmark search modal successfully!'
                );
            }
            $value = $settings['bookmark_search'];
            [$result, $key] = $this->valueToAddOrRemove($entity, $value);
            $settings['bookmark_search'] = $result;
            $user->settings = $settings;
            $user->update();
            if ($key === false) {
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Add bookmark search modal successfully!'
                );
            }
            return ResponseObject::responseSuccess(
                null,
                [],
                'Remove bookmark search modal successfully!'
            );
        }
        return ResponseObject::responseFail(
            'Please check entity value submit!'
        );
    }

    private function valueToAddOrRemove($entity, &$value)
    {
        $key = array_search($entity, $value);
        if ($key === false) {
            array_push($value, $entity);
        } else {
            unset($value[$key]);
        }
        return [$value, $key];
    }
}
