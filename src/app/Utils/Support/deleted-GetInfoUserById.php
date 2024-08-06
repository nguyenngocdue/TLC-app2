<?php

namespace App\Utils\Support;

use App\Models\User;

class GetInfoUserById
{
    public static function get($uid)
    {
        $user = User::findFromCache($uid);
        $src = $user->getAvatarThumbnailUrl();
        $firstName = $user->first_name;
        $displayName = $user->name ;
        return [$src, $firstName, $displayName];
    }
}