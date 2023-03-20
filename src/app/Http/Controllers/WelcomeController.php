<?php

namespace App\Http\Controllers;

use App\Utils\Support\Tree\BuildTree;
use App\Utils\System\Timer;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Support\Facades\Hash;

class WelcomeController extends Controller
{
    public function index()
    {
        Timer::startTimeCounter();
        dump(BuildTree::getTreeByOptions(null, null, null, false, false));
        dd(Timer::getTimeElapse());
        return view(
            'welcome',
            []
        );
    }
}
