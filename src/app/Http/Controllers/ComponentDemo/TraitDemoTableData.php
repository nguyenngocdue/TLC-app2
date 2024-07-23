<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Blade;

trait TraitDemoTableData
{
    public function getTableColumns()
    {
        return [
            [
                "title" => 'ID',
                "dataIndex" => "id",
                "renderer" => "id",
                "align" => "center",
            ],
            [
                "title" => 'Client',
                "dataIndex" => "client",
                "renderer" => "avatar-item",
                "attributes" => ['title' => 'client', 'description' => 'amount', 'avatar' => 'avatar', 'gray' => 'disabled'],
                "colspan" => 2,
            ],
            [
                "title" => 'Logged In',
                "dataIndex" => "loggedIn",
                "renderer" => "toggle",
                "align" => "center",
            ],
            [
                "title" => 'Amount',
                "dataIndex" => "amount",
                "align" => "right",
            ],
            [
                "title" => 'Status',
                "dataIndex" => "status",
                "renderer" => "status",
                'align' => 'center',
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
                // "hidden" => true, //<< It works
            ],
        ];
    }

    public function getTableEditableColumns()
    {
        return [
            [
                "title" => 'ID',
                "dataIndex" => "id",
                "renderer" => "id",
                'align' => 'center',
            ],
            [
                "title" => 'Client',
                "dataIndex" => "client",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ["", "Hans", "Helen", "sarah", "Sandy", "Eva", "Travis"],
                "colspan" => 2,
            ],
            [
                "title" => 'Logged In',
                "dataIndex" => "loggedIn",
                "renderer" => "checkbox",
                "align" => "center",
                "editable" => true,
            ],
            [
                "title" => 'Amount',
                "dataIndex" => "amount",
                "renderer" => "number4",
                "editable" => true,
            ],
            [
                "title" => 'Status',
                "dataIndex" => "status",
                "renderer" => "dropdown",
                "editable" => true,
                "cbbDataSource" => ["", "approved", "pending", "rejected", "expired"],
            ],
            [
                "title" => 'Date',
                "dataIndex" => "date",
                "renderer" => "text4",
                "editable" => true,
            ],
        ];
    }

    public function getTableDataHeader()
    {
        $onClick = "console.log(\"Hello\")";
        $button = "<x-renderer.button size='xs' value='xxx' onClick='$onClick'>Hello in console</x-renderer.button>";
        return [
            'client' => (object)[
                'value' =>   Blade::render($button),
                'cell_class' => 'bg-green-300',
            ],
            'loggedIn' => "01<br/>01<br/>23"
        ];
    }

    public function getTableDataSource()
    {
        return [
            [
                "id" => 1001, "client" => "Empty Avatar", "amount" => 1213.45, "status" => "approved", "color" => "green", "date" => "26/10/2022",
                "rowDescription" => "This is an example of a rowDescription",
                "loggedIn" => true,
            ],
            [
                "avatar" => "/images/hans.jpeg",
                "id" => 2002, "client" => "Hans", "amount" => 863.45, "status" => "approved", "date" => "06/10/2022",
                "loggedIn" => false,
            ],
            [
                "avatar" => "/images/helen.jpeg",
                "id" => 3003, "client" => "Helen", "amount" => 123.45, "status" => "rejected", "color" => "orange", "date" => "07/10/2022",
                "loggedIn" => true,
                "disabled" => true,
            ],
            [
                "avatar" => "/images/sarah.jpeg",
                "id" => 4004, "client" => "sarah", "amount" => 456.45, "status" => "rejected", "color" => "red", "date" => "08/10/2022",
                "loggedIn" => false,
            ],
            [
                "avatar" => "/images/Sandy.jpeg",
                "id" => 5005, "client" => "Sandy", "amount" => 789.45, "status" => "pending", "color" => "gray", "date" => "09/10/2022",
                "loggedIn" => 1,
            ],
            [
                "avatar" => "/images/avatar.jpg",
                "id" => 6006, "client" => "Eva", "amount" => 1011.45, "status" => "pending", "color" => "green", "date" => "16/10/2022",
                "loggedIn" => 0,
            ],
            [
                "avatar" => "/images/Travis.jpeg",
                "id" => 6006, "client" => "Travis", "amount" => 1011.45, "color" => "green", "date" => "16/10/2022",
                "loggedIn" => 0,
            ],
        ];
    }

    public function getColumnsForRegister()
    {
        return [
            [
                'dataIndex' => 'name',
            ],
            [
                'dataIndex' => 'create',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'value',
                'align' => 'center',
            ],
        ];
    }

    public function getDataSourceForRegister()
    {
        return [
            [
                'name' => 'aaa111',
                'create' => (object)[
                    'value' => '<i class="fa-regular fa-circle-plus text-lg"></i>',
                    'cell_href' => 'https://www.google.com',
                    'cell_class' => 'bg-green-300 text-red-500',
                    'cell_title' => 'Create a new item 111',
                    'cell_div_class' => 'p-2 w-11',
                ],
                'value' => (object)[
                    'value' => 456,
                    'cell_title' => 'Hover on me',
                    'cell_class' => 'bg-yellow-300 text-white font-bold',
                    'cell_href' => 'https://www.google.com',
                ],
            ],
            [
                'name' => 'bbb222',
                'create' => (object)[
                    'value' => '<i class="fa-regular fa-circle-plus text-lg"></i>',
                    'cell_href' => 'https://www.google.com',
                    'cell_class' => 'bg-green-300 text-red-500',
                    'cell_title' => 'Create a new item 111',
                    'cell_div_class' => 'p-2 w-11',
                ],
                'value' => [
                    (object)[
                        'value' => 123,
                        'cell_title' => 'Hover on me',
                        'cell_class' => 'bg-blue-300 text-white font-bold',
                        'cell_href' => 'https://www.google.com',
                    ],
                    (object)[
                        'value' => 789,
                        'cell_title' => 'Hover on me',
                        'cell_class' => 'bg-green-300 text-white font-bold',
                        'cell_href' => 'https://www.google.com',
                    ]
                ],
            ],
        ];
    }
}
