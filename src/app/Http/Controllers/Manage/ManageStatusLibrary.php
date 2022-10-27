<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageStatusLibrary extends Controller
{


    protected $readingFileService;
    protected $title = "Status Library";
    protected $w_file_path = "master/status_lib/all_statuses.json";
    protected $r_file_path = "master/status_lib/all_statuses.json";
    protected $disk = "json";

    public function __construct(ReadingFileService $readingFileService)
    {
        $this->readingFileService = $readingFileService;
    }

    public function index()
    {
        $props = $this->readingFileService->getPath("$this->disk/$this->r_file_path");
        $error = is_null($props) ? "all_statuses.json file was null" : "";
        if (is_null($props)) return view('components.render.alert')->with(compact('error'));
        //  take a first word of field
        $sign = array_map(fn ($key) => ((string)$key)[0], array_keys($props));
        $usign =  array_unique($sign);
        $countsign = array_count_values($sign);

        $libStatus = [];
        foreach ($usign as $key => $value) {
            $array = [];
            array_push($array,  array_slice($props, $key, $countsign[$value]));
            $libStatus[$value] = array_values($array)[0];
        }
        $type = $this->title;
        return view('statusLibrary')->with(compact('libStatus', 'type'));
    }

    public function update(Request $request, $id)
    {

        $data = $request->input();

        //  Check the same name when you create a new status
        $newName = $data['newName'];
        if (isset($data['oldName']) && in_array($newName[0], $data['oldName'])) {
            Toastr::warning('This name already exists', 'Save file json was failed');
            return back();
        }

        // Create a status
        $array1 = [];
        if (is_null($newName[0]) !== true) {
            $name = $newName[0];
            $array = [];
            $array["name"] = $name;
            $array["title"] = "";
            $array["color"] = "";
            $array1[$name] = $array;
        }
        $array2 = [];

        if (isset($data['oldName'])) {
            foreach ($data['oldName'] as $key => $name) {
                $array = [];
                $array["name"] = $data["oldName"][$key];;
                $array["title"] = $data["oldTitle"][$key];
                $array["color"] = $data["oldColor"][$key];
                $array2[$name] = $array;
            }
        }
        $allStatuses  = array_merge($array2, $array1);
        ksort($allStatuses);
        $jsonManage = json_encode($allStatuses, JSON_PRETTY_PRINT);
        // dd($data, $array2, $array1, $allStatuses, $jsonManage);
        try {
            $output = Storage::disk('json')->put($this->w_file_path, $jsonManage, 'public');
            if ($output) {
                Toastr::success('Save file json successfully!', 'Save file json');
            } else {
                Toastr::warning('Maybe Permission is missing!', 'Save file json failed');
            }
            return back();
        } catch (\Throwable $th) {
            Toastr::warning($th, 'Save file json');
        }
    }
}
