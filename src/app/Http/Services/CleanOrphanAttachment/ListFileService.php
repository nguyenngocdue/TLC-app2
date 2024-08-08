<?php

namespace App\Http\Services\CleanOrphanAttachment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListFileService
{
    private function listFiles($folder)
    {
        $files = Storage::disk('s3')->files($folder);
        $files = array_map(function ($file) use ($folder) {
            return [
                'name' => $file,
                'folder' => $folder,
                'is_thumbnail' => str_contains($file, '-150x150.'),
                'owner_id' => 1,
            ];
        }, $files);

        DB::table('s3_files')->insert($files);

        return "Inserted " . count($files) . " files into the database";
    }

    public function handle()
    {
        $folders = DB::table('s3_folders')->get();
        foreach ($folders as $folder) {
            DB::table('s3_files')->where('folder', $folder->name)->delete();
            $this->listFiles($folder->name);
        }
    }
}
