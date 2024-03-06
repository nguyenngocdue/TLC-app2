<?php

namespace App\Http\Controllers\Conqa;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class ConqaArchiveController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    public function index($name, $uuid)
    {
        return view("conqa.conqa", [
            'item' => (object) ['name' => $name, 'uuid' => $uuid]
        ]);
    }
}
