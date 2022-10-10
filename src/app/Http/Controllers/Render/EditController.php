<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Http\Services\ReadingFileService;
use App\Http\Services\UploadService;
use App\Models\Media;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


abstract class EditController extends Controller
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
        $path = storage_path("/json/entities/$this->type/props.json");
        $props = json_decode(file_get_contents($path), true);
        return $props;
    }


    public function show($id)
    {
        $values = $this->data::find($id);
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
        $type = $this->type;
        $action = $this->action;
        return view('dashboards.render.edit')->with(compact('props', 'values', 'type', 'action'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->input());


        $idMedia = $this->upload->store($request, $id);

        $props = $this->getProps();
        // foreach ($props as $key => $value) {
        //     $request->validate([$value["column_name"] => $value['validation']]);
        // }
        $data = $this->data::find($id);
        foreach ($props as $value) {
            $key = $value['column_name'];
            $data->{$key} = request($key);
        }
        $data['avatar'] = $idMedia;
        $data->save();
        return redirect(route("{$this->type}_edit.show", $id));
    }
    public function index()
    {
        $action = $this->action;
        $props = $this->readingFileService->type_getPath($this->disk, $this->branchName, $this->type, $this->r_fileName);
        $type = $this->type;
        return view('dashboards.render.edit')->with(compact('props', 'type', 'action'));
    }
    public function store(Request $request)
    {
        $inputData = $request->input();
        unset($inputData['_token']);
        unset($inputData['_method']);
        $db = $this->data;

        // Check existing email
        $emailsDB = $db::all()->pluck('email');
        // $check = in_array($inputData["email"], $emailsDB);

        $array = [];
        foreach ($inputData as $key => $value) {
            $array[$key] = $value;
        }
        $db::create($array);
        return redirect(route("{$this->type}_edit.show", $id));
    }
}
