<?php

namespace App\Http\Controllers;



class WelcomeController extends Controller
{
    public function index()
    {
        // dump(ini_get("curl.cainfo"));
        // dump(Storage::disk('s3')->put('dinhcanh.txt', 'NgoDinhCanh', 'public'));
        return view(
            'welcome',
            []
        );
    }
}
