<?php

namespace App\Http\Controllers;

use App\Utils\Support\Tree\BuildTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        $tree = BuildTree::getTreeByOptions(2, '', '', false, true);
        // dump($tree, 13);

        return view("welcome-due", [
            // ''
        ]);
    }
}
