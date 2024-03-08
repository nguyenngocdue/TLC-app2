<?php

namespace App\BigThink;

use App\Models\Attachment;
use Illuminate\Support\Facades\DB;

trait HasCachedAvatar
{
    private static $avatarSingleton00 = [];
    private static $avatarSingleton01 = [];

    function getAvatarFromDB($defaultImage)
    {
        $avatar = $this->getAvatar;
        if ($avatar) $avatar = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $avatar->url_thumbnail;
        else $avatar = $defaultImage;
        return $avatar;
    }

    function getUserAvatarFromDB($objectType, $id)
    {
        $result = [];
        if (!isset(static::$avatarSingleton01[$objectType])) {
            $objectTypeEsc = str_replace('\\', '\\\\', $objectType);
            $sql = "SELECT u.id AS u_id, att.id AS att_id, att.url_thumbnail
            FROM users u
                LEFT JOIN attachments att ON (
                    u.id=att.object_id
                    AND att.object_type='$objectTypeEsc'
                ) WHERE att.deleted_at IS NULL";
            $result0 = DB::select($sql);
            foreach ($result0 as $row) {
                $uid = $row->u_id;
                static::$avatarSingleton01[$objectType][$uid]  = $row->url_thumbnail;
            }
        }
        $result = static::$avatarSingleton01[$this::class][$id] ?? null;
        return $result;
    }

    function getAvatarThumbnailUrl($defaultImage = "/images/avatar.jpg")
    {
        $id = $this->id;
        if (!isset(static::$avatarSingleton00[$this::class][$id])) {
            $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
            $thumbnail = $this->getUserAvatarFromDB($this::class, $id);
            // dump($thumbnail);
            static::$avatarSingleton00[$this::class][$id] = $thumbnail ?  $path .  $thumbnail : $defaultImage;
        }
        return static::$avatarSingleton00[$this::class][$id];
    }

    function getAvatarUrl($defaultImage = "/images/avatar.jpg")
    {
        $thumbnail = $this->getAvatarThumbnailUrl($defaultImage);
        return str_replace("-150x150", "", $thumbnail);
    }
}
