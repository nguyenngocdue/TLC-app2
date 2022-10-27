<?php

namespace App\Http\Services\Manage;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageService
{

    public function checkUploadFile($data, $type, $fileName)
    {
        $type = Str::plural($type);
        $output = Storage::disk('json')->put("entities/$type/$fileName.json", json_encode($data, JSON_PRETTY_PRINT), 'public');
        if ($output) {
            Toastr::success('Save file json successfully!', 'Save file json');
            return true;
        } else {
            Toastr::warning('Maybe Permission is missing!', 'Save file json failed');
            return false;
        }
    }
    public function path($type, $fileName)
    {
        $type = Str::plural($type);
        $path = storage_path() . "/json/entities/$type/$fileName.json";
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return $dataManage;
        } else {
            return false;
        }
    }
    public function pathTable($type, $fileName, $fileTableName)
    {
        $type = Str::plural($type);
        $path = storage_path() . "/json/entities/$type/$fileName.json";
        $pathTableName = storage_path() . "/json/configs/table/$type/$fileTableName.json";
        $tableNames = json_decode(file_get_contents($pathTableName), true);
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return [$dataManage, $tableNames];
        } else {
            return [false, $tableNames];
        }
    }
    public function destroy($name, $type, $fileName)
    {
        $dataManage = $this->path($type, $fileName);
        unset($dataManage[$name]);
        try {
            $this->checkUploadFile($dataManage, $type, $fileName);
            return true;
        } catch (\Throwable $th) {
            Toastr::warning('$th', 'Save file json');
            return false;
        }
    }
    public function pathConfig($fileName)
    {
        $path = storage_path() . "/json/configs/view/dashboard/$fileName.json";
        if (file_exists($path)) {
            $dataManage = json_decode(file_get_contents($path), true);
            return $dataManage;
        } else {
            return false;
        }
    }
}
