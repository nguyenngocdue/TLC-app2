<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserEdit extends Controller
{

    public function index(){
        $title = "user edit";
        return view ('useredit', [
            "title" => $title,
        ]);
    }
}
