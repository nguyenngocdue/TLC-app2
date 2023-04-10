<?php

namespace App\Http\Controllers;

use App\Models\Qaqc_insp_chklst_run;
use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\Tree\BuildTree;
use App\Utils\System\Timer;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    public function index()
    {
        // dump(ini_get("curl.cainfo"));
        // dump(Storage::disk('s3')->put('dinhcanh.txt', 'NgoDinhCanh', 'public'));
        dd(Qaqc_insp_chklst_sht::find(78)->getProject);
        return view(
            'welcome',
            []
        );
    }
}
