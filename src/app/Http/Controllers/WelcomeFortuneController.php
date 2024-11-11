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
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
        ];
        $tables = [
            ['name' => 'John', 'description' => '35 Phan Chu Trinh'],
            ['name' => 'Doe', 'description' => '537 Nguyen Thi Dinh'],
        ];

        return view("welcome-fortune", [
            'columns' => $columns,
            'dataSource' => $tables,
        ]);
    }
}
