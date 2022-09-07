<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserEditController extends Controller
{
    protected $data = User::class;

    private function getProps(){
        $path = storage_path("/json/entities/user/props.json");
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
        foreach ($props as $key => $value) {
            $key = $value['column_name'];
            $data->{$key} = request($key);
            // echo( $key." ". request($key)."</br>");
        }
        $data->save();
        return redirect('dashboard/user/user_edit/'.$id);
    }
}
