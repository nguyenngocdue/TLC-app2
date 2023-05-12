<?php

namespace App\Utils\System\Api;

use App\Utils\Support\CurrentUser;
use App\Utils\System\Memory;
use App\Utils\System\Timer;

class ResponseObject
{
    public static function responseSuccess($hits = null, $meta = [], $message = '', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'took' => Timer::getTimeElapse(),
            'consumed_in_mb' => Memory::getMemoryElapse(),
            'hits' => $hits,
            'meta' => (object)$meta,

        ], $code);
    }
    public static function responseFail($message = null, $code = 401)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    public static function responseTokenAndUser($token = null, $userDefault = null, $user = null, $message = '', $code = 200)
    {
        $urlAvatar = $userDefault->getAvatar->url_thumbnail ?? '';
        $userDefault = $userDefault->toArray();
        $userDefault['avatar'] = $urlAvatar;
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $userDefault,
            'userRoleSet' =>  CurrentUser::getRoleSet($user),
            'userRoles' => CurrentUser::getRoles($user),
            'userPermissions' =>  CurrentUser::getPermissions($user),
            'message' => $message
        ], 200);
    }
}
