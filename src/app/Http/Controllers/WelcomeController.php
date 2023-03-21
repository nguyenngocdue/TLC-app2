<?php

namespace App\Http\Controllers;

use App\Utils\Support\Tree\BuildTree;
use App\Utils\System\Timer;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        // dump(ini_get("curl.cainfo"));
        // dump(Storage::disk('s3')->put('dinhcanh.txt', 'NgoDinhCanh', 'public'));
        dd(Storage::disk('s3')->allFiles());
        return view(
            'welcome',
            []
        );
    }
}
