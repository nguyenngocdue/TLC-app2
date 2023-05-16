<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function updateBookmark(Request $request)
    {
        $entity = $request->input('entity');
        if ($entity) {
            $user = User::find(Auth::id());
            $dataEntity = LibApps::getAllShowBookmark()[$entity];
            $settings = $user->settings;
            if (!isset($settings['bookmark_search'])) {
                $settings['bookmark_search'] = [$entity];
                $user->settings = $settings;
                $user->update();
                return ResponseObject::responseSuccess(
                    'add',
                    [$dataEntity],
                    'Item is added to bookmark successfully!'
                );
            }
            $value = $settings['bookmark_search'];
            [$result, $key] = $this->valueToAddOrRemove($entity, $value);
            $settings['bookmark_search'] = $result;
            $user->settings = $settings;
            $user->update();
            if ($key === false) {
                return ResponseObject::responseSuccess(
                    'add',
                    [$dataEntity],
                    'Item is added to bookmark successfully!'
                );
            }
            return ResponseObject::responseSuccess(
                'remove',
                [$dataEntity],
                'Item is removed from bookmark successfully!'
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
