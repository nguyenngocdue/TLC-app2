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
    protected $type = "status_lib";
    protected $title = "Status Library";
    protected $path = "master/status_lib/all_statuses.json";


    public function __construct(ReadingFileService $readingFileService)
    {
        $this->readingFileService = $readingFileService;
    }

    public function index()
    {
        $props = $this->readingFileService->indexProps($this->type, "all_statuses.json");
        $error = is_null($props) ? "null" : "";
        if (is_null($props)) return view('components.render.error')->with(compact('error'));
        //  take a first word of field
        $sign = [];
        foreach ($props as $key => $value) {
            $_key = (string)$key;
            $sign[] = $_key[0];
        }
        $usign =  array_unique($sign);
        $countsign = array_count_values($sign);

        $libStatus = [];
        foreach ($usign as $key => $value) {
            $array = [];
            array_push($array,  array_slice($props, $key, $countsign[$value]));
            $libStatus[$value] = array_values($array)[0];
        }
        // dd($libStatus);
        $type = $this->title;

        return view('statusLibrary')->with(compact('libStatus', 'type'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {

        $data = $request->input();


        $manage = [];
        if (is_null($data["name"][0]) !== true) {
            $name = $data['name'][0];
            $array = [];
            $array["name"] = $name;
            $array["title"] = $data["title"];
            $array["color"] = $data["color"];
            $manage[$name] = $array;
        }
        if (isset($data['oldName'])) {
            foreach ($data['oldName'] as $key => $name) {
                $array = [];
                $array["name"] = $data["oldName"][$key];;
                $array["title"] = $data["oldTitle"][$key];
                $array["color"] = $data["oldColor"][$key];
                $manage[$name] = $array;
                // dd($array, $name);
            }
        }
        ksort($manage);
        $jsonManage = json_encode(array_merge($manage));
        try {
            $output = Storage::disk('json')->put($this->path, $jsonManage, 'public'); // wwhy output has always false value
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

    public function destroy($id)
    {
        //
    }
}
