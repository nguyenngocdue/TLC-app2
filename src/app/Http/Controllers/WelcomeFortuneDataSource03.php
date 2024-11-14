<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;

class WelcomeFortuneDataSource03
{
  function getDataSource()
  {
    $statuses = LibStatuses::getAll();
    $columns = [
      ['dataIndex' => 'hidden_column', 'invisible' => true, 'fixed' => 'left'],
      [
        'dataIndex' => 'name',
        'renderer' => 'text',
        'fixed' => 'left',
        'classList' => 'text-center whitespace-nowrap',
        'rendererAttrs' => [],
      ],
      [
        'dataIndex' => 'name',
        'renderer' => 'text',
        'mode' => 'edit',
        'fixed' => 'left',
        'classList' => 'text-right text-red-500 bg-green-100 font-bold',
      ],
      [
        'dataIndex' => 'bool',
        'renderer' => 'toggle',
        'subTitle' => 'Boolean View'
      ],
      [
        'dataIndex' => 'bool',
        'renderer' => 'toggle',
        'mode' => 'edit',
        'subTitle' => 'toggle'
      ],
      // [
      //   'dataIndex' => 'bool',
      //   'renderer' => 'checkbox',
      //   'subTitle' => 'checkbox'
      // ],
      [
        'dataIndex' => 'bool2',
        'renderer' => 'checkbox',
        'mode' => 'edit',
        'subTitle' => 'checkbox'
      ],
      [
        'dataIndex' => 'number',
        'renderer' => 'number',
        'rendererAttrs' => [
          'decimalPlaces' => 3,
        ],
      ],
      [
        'dataIndex' => 'number',
        'renderer' => 'number',
        'mode' => 'edit',
        'rendererAttrs' => [
          'decimalPlaces' => 3,
        ],
      ],
      [
        'dataIndex' => 'status',
        'renderer' => 'dropdown',
        'rendererAttrs' => [
          'dataSource' => $statuses,
        ],
      ],
      [
        'dataIndex' => 'status',
        'renderer' => 'dropdown',
        'mode' => 'edit',
        'rendererAttrs' => [
          'dataSource' => $statuses
        ],
      ],

      [
        'dataIndex' => 'user_id',
        'classList' => 'whitespace-nowrap',
        'renderer' => 'dropdown',
        'rendererAttrs' => [
          'dataSourceKey' => 'users',
        ],
      ],
      [
        'dataIndex' => 'user_id',
        'renderer' => 'dropdown',
        'mode' => 'edit',
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [
          'dataSourceKey' => 'users',
        ],
      ],
      [
        'dataIndex' => 'user1_id',
        'renderer' => 'dropdown',
        // 'mode' => 'edit',
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [
          'dataSourceKey' => 'users',
          'valueField' => 'employeeid',
        ],
      ],
      // ['dataIndex' => 'date','fixed' => 'right', 'width'=> 120],
      // ['dataIndex' => 'date','fixed' => 'right', 'mode'=> 'edit','renderer' => 'dropdown'],
    ];

    $tables = [
      [
        'name' => 'John and lots of his friends',
        'status' => 'active',
        'text' => 'a string',
        'date' => '2021-01-01',
        'bool' => true,
        'bool2' => false,
        'number' => 1,
        'user_id' => 2,
        'user1_id' => 'TLCM01304',
      ],
      [
        'name' => 'Doe',
        'status' => 'new',
        'text' => 'another string',
        'date' => '2021-01-02',
        'bool' => false,
        'bool2' => true,
        'number' => 02.009,
        'user_id' => 37,
        'user1_id' => 'TLCM01069',
      ],
      [
        'name' => 'Doe',
        'status' => 'new',
        'text' => 'another string',
        'date' => '2021-01-02',
        'bool' => false,
        'bool2' => true,
        'number' => -1e-3,
        'user_id' => 222,
        'user1_id' => 'TLCM01034',
      ],
    ];

    return  [
      'columns' => $columns,
      'dataSource' => $tables,
    ];
  }
}
