<?php

namespace App\Http\Controllers;

class WelcomeFortuneDataSource03
{
    function getDataSource() {
        $columns = [ 
            ['dataIndex' => 'hidden_column', 'invisible' => true, 'fixed' => 'left'],
            ['dataIndex' => 'name', 'renderer' => 'text', 'fixed' => 'left'],
            ['dataIndex' => 'name', 'renderer' => 'text', 'mode' => 'edit', 'fixed' => 'left',  ],
            ['dataIndex' => 'bool', 'renderer' => 'toggle'],
            ['dataIndex' => 'bool', 'renderer' => 'toggle', 'mode'=> 'edit', ],
            // ['dataIndex' => 'bool', ],
            ['dataIndex' => 'bool', 'renderer' => 'checkbox', 'mode'=> 'edit',],
            ['dataIndex' => 'number','renderer' => 'number'],
            ['dataIndex' => 'number','renderer' => 'number', 'mode'=> 'edit', ],
            ['dataIndex' => 'status', 'renderer' => 'dropdown'],
            ['dataIndex' => 'status', 'renderer' => 'dropdown', 'mode'=> 'edit', ],
            // ['dataIndex' => 'date','fixed' => 'right', 'width'=> 120],
            // ['dataIndex' => 'date','fixed' => 'right', 'mode'=> 'edit','renderer' => 'dropdown'],
        ];
        
        $tables = [
          [
            'name' => 'John',
            'status' => 'active',
            'text' => 'a string',
            'date' => '2021-01-01',
            'bool' => true,
            'number' => 1.25,
          ],
          [
            'name' => 'Doe',
            'status' => 'new',
            'text' => 'another string',
            'date' => '2021-01-02',
            'bool' => false,
            'number' => 1.25,
          ],
        ];

        return  [
            'columns' => $columns,
            'dataSource' => $tables,
        ];
    }
}
