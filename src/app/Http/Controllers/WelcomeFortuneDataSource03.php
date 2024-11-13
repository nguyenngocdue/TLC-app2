<?php

namespace App\Http\Controllers;

class WelcomeFortuneDataSource03
{
    function getDataSource() {
        $columns = [ 
            ['dataIndex' => 'hidden_column', 'invisible' => true, 'fixed' => 'left'],
            ['dataIndex' => 'name', 'width' => 200, 'fixed' => 'left'],
            ['dataIndex' => 'name', 'width' => 200, 'mode' => 'edit'],
            ['dataIndex' => 'status', ],
            ['dataIndex' => 'status', 'mode'=> 'edit',],
          
            ['dataIndex' => 'number',],
            ['dataIndex' => 'text','fixed' => 'right'],
            ['dataIndex' => 'date','fixed' => 'right', 'width'=> 120],
        ];
        
        $tables = [
          [
            'name' => 'John',
            'status' => 'active',
            'text' => 'a string',
            'date' => '2021-01-01',
          ],
          [
            'name' => 'Doe',
            'status' => 'new',
            'text' => 'another string',
            'date' => '2021-01-02',
          ],
        ];

        return  [
            'columns' => $columns,
            'dataSource' => $tables,
        ];
    }
}
