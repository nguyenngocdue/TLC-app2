<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class EditController extends Controller
{

    protected $type;
    protected $data;
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
        $data = $this->data::find($id);
        $props = $this->getProps();
        foreach ($props as $value) {
            $key = $value['column_name'];
            $data->{$key} = request($key);
        }
        $data->save();
        return redirect(route("$this->type._edit.show", $id));
    }
}
