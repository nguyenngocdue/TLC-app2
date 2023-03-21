<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;

class ParserController extends Controller
{
    public function index()
    {
        $txt = explode("\r\n", $_GET['txt'] ?? "");
        // $lines = [];
        foreach ($txt as $line) {
            // dump($line);
            $values = unserialize($line);
            if ($values) {
                echo join(",", $values);
            }
            echo "\n";
        }
        return view("utils/parser", []);
    }
}
