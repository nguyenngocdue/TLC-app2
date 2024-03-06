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

    public function index($type, $name, $uuid)
    {
        return view("conqa.conqa", [
            'item' => (object) ['type' => $type, 'name' => $name, 'uuid' => $uuid]
        ]);
    }
}
