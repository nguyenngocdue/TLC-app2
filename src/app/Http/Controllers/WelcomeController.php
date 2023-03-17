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
        dump(BuildTree::getTreeByOptions(38, 16, 38, false, true));
        dd(Timer::getTimeElapse());
        return view(
            'welcome',
            []
        );
    }
}
