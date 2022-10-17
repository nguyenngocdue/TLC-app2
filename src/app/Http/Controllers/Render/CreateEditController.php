<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


abstract class CreateEditController extends Controller
{

    protected $type;
    protected $data;
    protected $action;

    protected $upload;
    protected $branchName = 'entities';
    protected $disk = 'json';
    protected $r_fileName = 'props.json';
    protected $readingFileService;
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
        $values = $action === "create" ? "" : $currentUser;



        // dd($currentUser->id, $id);
        $tablePath = $this->data;

        // dd($type, $action);
        return view('dashboards.render.createEdit')->with(compact('props', 'values', 'type', 'action', 'currentUser', 'tablePath'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->input());
        $data = $this->data::find($id);
        $dataInput = $request->input();

        // Validation
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
        $itemsValidation = [];
        foreach ($props as $key => $value) {
            if ($value['validation'] != "") {
                $itemsValidation[$value['column_name']] = "required";
            }
        }
        $request->validate($itemsValidation);


        // upload multiple pictures
        $idMediaArray = $this->upload->store($request, $id);
        $props = $this->getProps();
        $itemAttachment = [];
        foreach ($props as $key => $value) {
            if ($value['control'] === 'attachment') {
                array_push($itemAttachment, $value['column_name']);
            }
        }

        // Update dataInput to database
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

        if ($props  === false) {
            $error =  "Please check `$this->r_fileName` file  of `$this->type` at $this->branchName folder";
            return view('components.render.error')->with(compact('error'));
        }

        $type = $this->type;
        $tablePath = $this->data;
        $values = "";

        return view('dashboards.render.createEdit')->with(compact('props', 'type', 'action', 'tablePath', 'values'));
    }
    public function store(Request $request)
    {
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);

        // dd($request->input());
        // Validation
        $itemsValidation = [];
        foreach ($props as $key => $value) {
            if ($value['validation'] != "") {
                $itemsValidation[$value['column_name']] = "required";
            }
        }
        // Validation.
        Validator::make($request->all(), $itemsValidation);
        $request->validate($itemsValidation);


        $dataInput = $request->input();
        unset($dataInput['_token']);
        unset($dataInput['_method']);
        $db = $this->data;

        $array = [];
        foreach ($dataInput as $key => $value) {
            $array[$key] = $value;
        }
        // dd($array);
        $newUser = $db::create($array);
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
