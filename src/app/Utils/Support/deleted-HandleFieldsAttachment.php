<?php

namespace App\Utils\Support;

class HandleFieldsAttachment
{
    public static function handle($attachment)
    {
        $isOrphan = isset($attachment['isOrphan']) && $attachment['isOrphan'];
        $borderColor = $isOrphan ? "red" : "gray";
        $title = $isOrphan ? "Orphan image found. Will attach after this document is saved." : "";
        $extension = $attachment['extension'] ?? "";
        $attachmentFolder = $attachment['url_folder'] ?? '';

        $isProd = str_starts_with($attachmentFolder, 'app1_prod') || str_starts_with($attachmentFolder, 'app2_prod') || str_starts_with($attachmentFolder, 'avatars');
        $isTesting = str_starts_with($attachmentFolder, 'app2_beta');
        $isDev = !($isProd || $isTesting);

        $sameEnv = false;
        if (app()->isProduction() && $isProd) $sameEnv = true;
        if (app()->isTesting() && $isTesting) $sameEnv = true;
        if (app()->isLocal() && $isDev) $sameEnv = true;
        // dump($sameEnv);
        return [$isOrphan, $sameEnv, $extension, $borderColor, $title];
    }
}
