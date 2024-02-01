<?php

namespace App\Utils\Support;

class HandleFieldsAttachment
{
    public static function handle($attachment)
    {
        $hasOrphan = isset($attachment['hasOrphan']) && $attachment['hasOrphan'] ;
        $border = $hasOrphan ? "red" : "gray";
        $title = $hasOrphan ? "Orphan image found. Will attach after this document is saved.":"";
        $extension = $attachment['extension'] ?? "";
        $folder = $attachment['url_folder'] ?? '';
        $isProd = str_starts_with($folder, 'app2_prod') || str_starts_with($folder, 'avatars');
        $isTesting = str_starts_with($folder, 'app2_beta');
        $isDev = !($isProd || $isTesting);

        $sameEnv = false;
        if(app()->isProduction() && $isProd) $sameEnv = true; 
        if(app()->isTesting() && $isTesting) $sameEnv = true; 
        if(app()->isLocal() && $isDev) $sameEnv = true;
        return [$hasOrphan,$sameEnv,$extension,$border,$title];     
    }
}