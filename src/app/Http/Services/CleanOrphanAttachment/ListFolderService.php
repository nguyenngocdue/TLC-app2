<?php

namespace App\Http\Services\CleanOrphanAttachment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListFolderService
{
    private $folders = [];

    private $foldersOnS3 = [
        'app1_prod',
        'app2_prod',
        'avatars',

        // 'app2_beta',
    ];

    private function listSubFolders($folder)
    {
        $folderListArray = Storage::disk('s3')->directories($folder,);
        if (!empty($folderListArray)) {
            // Log::info($folderListArray);
            $this->folders = array_merge($this->folders, $folderListArray);
            foreach ($folderListArray as $folder) {
                $this->listSubFolders($folder);
            }
        }
    }

    public function handle()
    {
        DB::table('s3_folders')->truncate();
        foreach ($this->foldersOnS3 as $folder) {
            DB::table('s3_folders')->insert([
                'name' => $folder,
                'owner_id' => 1,
            ]);
            $this->listSubFolders($folder);
        }

        foreach ($this->folders as $folder) {
            DB::table('s3_folders')->insert([
                'name' => $folder,
                'owner_id' => 1,
            ]);
        }

        return "Inserted " . count($this->folders) . " folders into the database";
    }
}
