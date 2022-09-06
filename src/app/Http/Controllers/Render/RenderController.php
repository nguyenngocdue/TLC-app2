<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RenderController extends Controller
{
    protected $type = "";
    public function index() {
        $type = $this->type;
        $idUser = Auth::guard()->id();
        $userLogin = User::find($idUser);
        $search = request('search');
        $users = User::search($search)->query(function($q) {
            $q->orderBy('id', 'asc');
        })->paginate(10);
        $patch = storage_path() . "/json/entities/$type/props.json";
        if(!file_exists($patch)){
        }else{
            $data = json_decode(file_get_contents($patch), true);
            return view('dashboards.render.prop')->with(compact('data','users','type','userLogin','search'));
        }
    }
    public function update(Request $request , $id)
    {
        $data = $request->input();
        $data = array_diff_key($data, ['_token' => '' , '_method' => 'PUT']);
        $user = User::find($id);
        $dataDefault = User::find($id)->getAttributes();
        $dataSettings = [];
        foreach($dataDefault as $key => $value){
            if(!array_key_exists('_'.$key, $data)){
                $dataSettings['_'.$key] = $value;
            }
        }
        $user->settings = $dataSettings;
        $user->update();
        Toastr::success('Save settings json Users successfully', 'Save file json');
        return redirect()->back();
    }
}
