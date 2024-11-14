<?php

namespace App\Http\Controllers;

class WelcomeFortuneDataSource01
{
    function getDataSource()
    {
        $columns = [
            ['dataIndex' => 'name', 'width' => 400,],
            ['dataIndex' => 'number', 'align' => 'right', 'arraySeparator' => ', ',],
            ['dataIndex' => 'text', 'arraySeparator' => ' | ',],
            ['dataIndex' => 'object', 'width' => 200,],
            ['dataIndex' => 'icon', 'width' => 200,],
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
                    'cell_class' => 'text-sky-800 bg-sky-800',
                    'cell_div_class' => 'bg-white p-1',
                ],
                'icon' => [
                    'value' => '<i class="fa-regular fa-circle-plus text-lg"></i>',
                    'cell_href' => 'https://www.google.com',
                    'cell_class' => 'bg-green-300 text-red-500',
                    'cell_title' => 'Create a new item 111',
                    'cell_div_class' => 'p-2 w-11 bg-sky-300',
                ],
            ],

            [
                'name' => 'Array of numbers',
                'number' => [0, 1, 2, 3, 4],
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
                        'cell_div_class' => 'p-2 w-11 bg-orange-300',
                    ],
                    [
                        'value' => '<i class="fa-regular fa-circle-minus text-lg"></i>',
                        'cell_href' => 'https://www.google.com',
                        'cell_class' => 'bg-green-300 text-red-500',
                        'cell_title' => 'Create a new item 111',
                        'cell_div_class' => 'p-2 w-11 bg-amber-300',
                    ],
                    [
                        'value' => '<i class="fa-regular fa-circle-check text-lg"></i>',
                        'cell_href' => 'https://www.google.com',
                        'cell_class' => 'bg-green-300 text-red-500',
                        'cell_title' => 'Create a new item 111',
                        'cell_div_class' => 'p-2 w-11 bg-violet-300',
                    ],
                ],
            ],

        ];

        return  [
            'columns' => $columns,
            'dataSource' => $tables,
        ];
    }
}
