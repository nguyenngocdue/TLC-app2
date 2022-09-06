<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Controller;

class EditUserActionRenderController extends Controller
{
    public function index(){

        $patch = storage_path()."/json/entities/user/props.json";
        $data = json_decode(file_get_contents($patch), true);
        return view('dashboards.render.edit')->with(compact('data'));
    }
}
