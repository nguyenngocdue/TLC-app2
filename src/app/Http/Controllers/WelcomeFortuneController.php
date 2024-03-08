<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WelcomeFortuneController extends Controller
{
    function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {

        $disk = Storage::disk('project_plans');

        // List contents with '/' as a delimiter to simulate folder structure.
        // dump($disk);
        $contents = $disk->directories('/', false);
        dd($contents);

        // $folders = collect($contents)->where('type', '=', 'dir')->pluck('path')->all();

        // return $folders;
        return view("welcome-fortune", []);
    }
}
