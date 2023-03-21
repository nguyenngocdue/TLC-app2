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
        $txt = explode("\r\n", $_POST['txt'] ?? "");
        // $lines = [];
        foreach ($txt as $line) {
            // dump($line);
            $values = unserialize($line);
            if ($values) {
                echo join(",", $values);
            }
            echo "\n";
        }
    }
}
