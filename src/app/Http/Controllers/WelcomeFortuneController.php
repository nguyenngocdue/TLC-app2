<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneController extends Controller
{
    function __construct() {}

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $columns = [
            ['dataIndex' => 'name','renderer'=>'text',],
            ['dataIndex' => 'description','renderer'=>'text',],
            ['dataIndex' => 'hidden_column', 'invisible' => true],
        ];
        $tables = [
            ['name' => 'John', 'description' => '35 Phan Chu Trinh',  'hidden_column' => 'hidden'],
            ['name' => 'Doe', 'description' => '537 Nguyen Thi Dinh',  'hidden_column' => 'hidden'],
        ];

        return view("welcome-fortune", [
            'columns' => $columns,
            'dataSource' => $tables,
        ]);
    }
}
