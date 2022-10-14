<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


abstract class CreateEditController extends Controller
{

    protected $type;
    protected $data;
    protected $upload;
    protected $branchName = 'entities';
    protected $disk = 'json';
    protected $r_fileName = 'props.json';
    protected $readingFileService;
    protected $action;
    public function __construct(UploadService $upload, ReadingFileService $readingFileService)
    {
        $this->upload = $upload;
        $this->readingFileService = $readingFileService;
    }

    private function getProps()
    {
        $type = Str::plural($this->type);
        $path = storage_path("/json/entities/$type/props.json");
        // dd($path);
        $props = json_decode(file_get_contents($path), true);
        return $props;
    }


    public function show($id)
    {
        $currentUser = $this->data::find($id);
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
        $type = Str::plural($this->type);
        $action = $this->action;
        $values = $action === "edit" ? $currentUser : [];



        $tablePath = $this->data;

        // dd($type, $action);
        return view('dashboards.render.edit')->with(compact('props', 'values', 'type', 'action', 'currentUser', 'tablePath'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->data::find($id);
        $dataInput = $request->input();


        // upload multiple pictures
        $idMediaArray = $this->upload->store($request, $id);
        $props = $this->getProps();
        $itemAttachment = [];
        foreach ($props as $key => $value) {
            if ($value['control'] === 'attachment') {
                array_push($itemAttachment, $value['column_name']);
            }
        }


        // foreach ($props as $key => $value) {
        //     $request->validate([$value["column_name"] => $value['validation']]);
        // }
        foreach ($props as $value) {
            $key = $value['column_name'];
            $data->{$key} = request($key);
        }
        // chanage value from toggle
        foreach ($props as $key => $value) {
            if ($value['control'] === 'toggle') {
                $item = $value['column_name'];
                isset($dataInput[$item]) ? $data[$item] = 1 : $data[$item] = 0;
            };
        }


        foreach ($itemAttachment as $key => $value) {
            if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
                $data[$value] = $idMediaArray[$key];
            }
        }
        $data->save();


        $type = Str::plural($this->type);
        return redirect(route("{$type}_edit.show", $id));
    }
    public function index()
    {

        $action = $this->action;
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
        $type = $this->type;
        $values = [];
        $tablePath = $this->data;

        return view('dashboards.render.edit')->with(compact('props', 'type', 'action', 'tablePath'));
    }
    public function store(Request $request)
    {
        $dataInput = $request->input();
        unset($dataInput['_token']);
        unset($dataInput['_method']);
        $db = $this->data;

        // Check existing email
        $emailsDB = $db::all()->pluck('email');
        // $check = in_array($dataInput["email"], $emailsDB);

        $array = [];
        foreach ($dataInput as $key => $value) {
            $array[$key] = $value;
        }
        $newUser = $db::create($array);
        // dd($newUser);
        $idNewUser = $newUser->fresh()->id;


        // Save picture
        $idMediaArray = $this->upload->store($request, $idNewUser);
        // filter fields have "attachment control"
        $itemAttachment = [];
        $props = $this->getProps();
        foreach ($props as $key => $value) {
            if ($value['control'] === 'attachment') {
                array_push($itemAttachment, $value['column_name']);
            }
        }
        // push value of picture into database
        foreach ($itemAttachment as $key => $value) {
            if (count($idMediaArray) > 0 && isset($idMediaArray[$key])) {
                $newUser[$value] = $idMediaArray[$key];
            }
        }

        // chanage value from toggle
        foreach ($props as $key => $value) {
            if ($value['control'] === 'toggle') {
                $item = $value['column_name'];
                isset($dataInput[$item]) ? $newUser[$item] = 1 : $newUser[$item] = 0;
            };
        }
        $type = Str::plural($this->type);



        return redirect(route("{$type}_edit.update", $idNewUser));
    }
}
