<?php

namespace App\BigThink;

use App\Models\Attachment;

trait HasCachedAvatar
{
    private static $userAvatarSingleton = [];
    function getAvatarThumbnailUrl()
    {
        $id = $this->id;
        if (!isset(static::$userAvatarSingleton[$id])) {
            $avatar = $this->getAvatar;
            if ($avatar) $avatar = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $avatar->url_thumbnail;
            else $avatar = "/images/avatar.jpg";
            static::$userAvatarSingleton[$id] = $avatar;
        }
        return static::$userAvatarSingleton[$id];
    }
}
