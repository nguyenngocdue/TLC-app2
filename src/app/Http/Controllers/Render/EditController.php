<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
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
    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
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
        $props = $this->getProps();
        return view('dashboards.render.edit')->with(compact('props', 'values'));
    }


    public function update(Request $request, $id)
    {

        $idMedia = $this->upload->store($request, $id);
        // dd($request->input());

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
}
