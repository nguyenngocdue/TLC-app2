<?php

namespace App\Http\Controllers\ComponentDemo;

trait TraitDemoSpanTable
{
    public function getTableSpanColumns()
    {
        return  [
            [
                'title' => 'Name',
                'dataIndex' => 'name',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'Age',
                'dataIndex' => 'age',
                'width' => 150,
                'headerFilter' => 'input',
            ],
            [
                'title' => 'Favourite Color',
                'dataIndex' => 'color',
                'width' => 150,
                'headerFilter' => 'input',
                // 'colspan' => 2,
            ],
            [
                'title' => 'Lucky Number',
                'dataIndex' => 'lucky_number',
                'width' => 150,
                'headerFilter' => 'input',
            ],
        ];
    }

    public function getTableSpanDataSource()
    {
        return  [
            [
                'name' => (object)[
                    'rowspan' => 2,
                    'value' => 'Bob',
                ],
                'age' => 25,
                'color' => (object)[
                    'colspan' => 2,
                    'value' => 'red'
                ],
                'lucky_number' => 7,

            ],
            [
                'name' => 'Alice',
                'age' => 32,
                'color' => 'blue',
                'lucky_number' => 10,
            ],
            [
                'name' => (object)[
                    'rowspan' => 3,
                    'value' => 'Frank',
                ],
                'age' => 41,
                'color' => (object)[
                    'colspan' => 2,
                    'rowspan' => 3,
                    'value' => 'green'
                ],
                'lucky_number' => 5,
                'rowspan' => 2,
            ],
            [
                'name' => 'Jane',
                'age' => 19,
                'color' => 'yellow',
                'lucky_number' => 3,
            ],
            [
                'name' => 'John',
                'age' => 29,
                'color' => 'purple',
                'lucky_number' => 9,
            ],
        ];
    }
}
