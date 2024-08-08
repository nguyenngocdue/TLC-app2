<?php

namespace App\Http\Services\CleanOrphanAttachment;

use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListAttachmentService
{
    private function listAttachment($folderName)
    {
        $att = Attachment::query()->where('url_folder', $folderName . "/")->get();
        $fileList = DB::table('s3_files')
            ->where('folder', $folderName)
            ->where('is_thumbnail', 0)
            // ->whereNot('processed', 1)
            ->get();

        $attArr = $att->pluck('url_media', 'id')->toArray();
        $fileListArr = $fileList->pluck('name')->toArray();

        $a = array_diff($attArr, $fileListArr);
        $b = array_diff($fileListArr, $attArr);

        if (count($a) > 0 || count($b) > 0) {
            dump($folderName);
            if (count($a) > 0) {
                dump("Have in attachment but not in MinIO");
                dump($a);
            }
            if (count($b) > 0) {
                dump("Have in MinIO but not in attachment");
                dump($b);
            }
            echo "<br/>";
        }
        // dump($attArr[0]);
        // dump($fileListArr[0]);
    }

    public function handle()
    {
        $folders = DB::table('s3_folders')->get();
        // $folders = [
        //     (object)['name' => 'avatars'],
        // ];
        foreach ($folders as $folder) {
            $this->listAttachment($folder->name);
        }
    }
}
