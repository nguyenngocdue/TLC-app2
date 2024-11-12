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
            ['dataIndex' => 'name', 'width' => 400,],
            ['dataIndex' => 'number', 'align' => 'right',],
            ['dataIndex' => 'text',],
            ['dataIndex' => 'object',],
            ['dataIndex' => 'icon',],
            ['dataIndex' => 'hidden_column', 'invisible' => true],
        ];
        $tables = [
            [
                'name' => 'A name and a hidden field (for width calculation)',
                'hidden_column' => 'hidden',
            ],
            [
                'name' => 'Scalar',
                'number' => 3.14159265944,              
                'text' => 'Hello World',              
                'object' => [
                    'value' => 'an obj',
                    'cell_class' => 'text-white',
                    'cell_div_class' => 'bg-blue-800 p-1',
                ],              
                'icon' => [
                    'value' => '<i class="fa-regular fa-circle-plus text-lg"></i>',
                    'cell_href' => 'https://www.google.com',
                    'cell_class' => 'bg-green-300 text-red-500',
                    'cell_title' => 'Create a new item 111',
                    'cell_div_class' => 'p-2 w-11',
                ],              
            ],
            
            [
                'name' => 'Array of numbers',
                'number' => [0,1,2,3,4],
                'text' => ['a', 'b', 'c'],
                'object' => [
                    [
                        'value' => 'text 1', 
                        'cell_class' => 'bg-blue-800 m-1', 
                        'cell_div_class' => 'bg-green-400 p-1',
                    ],
                    [
                        'value' => 'text 2',
                        'cell_class' => 'bg-blue-800 m-1',
                        'cell_div_class' => 'bg-red-400 p-1',
                    ],
                    [
                        'value' => 'text 3',
                        'cell_class' => 'bg-blue-800 m-1',
                        'cell_div_class' => 'bg-pink-400 p-1',
                    ],
                ],
                'icon' => [
                    [
                        'value' => '<i class="fa-regular fa-circle-plus text-lg"></i>',
                        'cell_href' => 'https://www.google.com',
                        'cell_class' => 'bg-green-300 text-red-500',
                        'cell_title' => 'Create a new item 111',
                        'cell_div_class' => 'p-2 w-11',
                    ],
                    [
                        'value' => '<i class="fa-regular fa-circle-minus text-lg"></i>',
                        'cell_href' => 'https://www.google.com',
                        'cell_class' => 'bg-green-300 text-red-500',
                        'cell_title' => 'Create a new item 111',
                        'cell_div_class' => 'p-2 w-11',
                    ],
                    [
                        'value' => '<i class="fa-regular fa-circle-check text-lg"></i>',
                        'cell_href' => 'https://www.google.com',
                        'cell_class' => 'bg-green-300 text-red-500',
                        'cell_title' => 'Create a new item 111',
                        'cell_div_class' => 'p-2 w-11',
                    ],
                ],              
            ],
            
        ];

        return view("welcome-fortune", [
            'columns' => $columns,
            'dataSource' => $tables,
        ]);
    }
}
