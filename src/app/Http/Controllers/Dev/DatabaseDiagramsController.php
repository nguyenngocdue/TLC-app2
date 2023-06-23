<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatabaseDiagramsController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    function index(Request $request)
    {
        return view("dev.database-diagrams", []);
    }
}
