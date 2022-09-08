<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class EditController extends Controller
{

    protected $type;
    protected $data;
    private function getProps(){
        $path = storage_path("/json/entities/$this->type/props.json");
        $props = json_decode(file_get_contents($path), true);
        return $props;
    }

    public function show(Request $request, $id){
        $values = $this->data::find($id);
        $props = $this->getProps();
        return view('dashboards.render.edit')->with(compact('props', 'values'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->data::find($id);
        $props = $this->getProps();
        // request()->validate([
        //     'email' => 'require',
        //     'full_name' => 'require'
        // ]);
        foreach ($props as $value) {
            $key = $value['column_name'];
            $data->{$key} = request($key);
            // echo( $key." ". request($key)."</br>");
        }
        $data->save();
        return redirect("dashboard/{$this->type}/{$this->type}_edit/$id");
    }
}
