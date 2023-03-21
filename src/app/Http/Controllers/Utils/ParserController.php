<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function index(Request $request)
    {
        return view("utils/parser", []);
    }

    public function store(Request $request)
    {
        $txtIds = explode("\r\n", $_POST['txtIds'] ?? "");
        $txtValues = explode("\r\n", $_POST['txtValues'] ?? "");
        foreach ($txtIds as $index => $id) {
            $values = unserialize($txtValues[$index]);
            if ($values) {
                foreach ($values as $value) {
                    echo "$id";
                    echo "\t";
                    echo "$value";
                    echo "\n";
                }
            } else {
                echo "$id";
                echo "\t";
                echo "";
                echo "\n";
            }
            // echo "\n";
        }
    }
}
