<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;

class WelcomeFortuneDataSource04
{
  function getDataSource()
  {
    $statuses = LibStatuses::getAll();
    $columns = [
      [
        'dataIndex' => 'id',
        'title' => 'CB',
        'renderer' => 'action_checkbox.',
        'fixed' => 'left'
      ],
      [
        'dataIndex' => 'id',
        'title' => 'ID',
        'renderer' => 'id_link',
        'fixed' => 'left',
        'rendererAttrs' => [
          'entityName' => '/dashboard/users',
        ],
      ],
      [
        'dataIndex' => 'id',
        'title' => 'Action',
        'renderer' => 'action.',
        'fixed' => 'left',
        'width' => 100,
      ],
      [
        'dataIndex' => 'id',
        'title' => 'Print',
        'renderer' => 'action_print.',
        'fixed' => 'left',
        // 'width' => 40,
        'rendererAttrs' => [
          'entityName' => '/dashboard/users',
        ],
      ],
      ['dataIndex' => 'hidden_column', 'invisible' => true, 'fixed' => 'left'],
      [
        'dataIndex' => 'name',
        'renderer' => 'text',
        'fixed' => 'left',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [],
      ],
      [
        'dataIndex' => 'hyper_link',
        'renderer' => 'hyper-link',
      ],
      [
        'dataIndex' => 'column',
        'renderer' => 'column',
        'width' => 150,
        'rendererAttrs' => [
          'columnToLoad' => 'name',
        ],
      ],
      [
        'dataIndex' => 'column',
        'renderer' => 'column_link',
        'width' => 150,
        'rendererAttrs' => [
          'columnToLoad' => 'name',
        ],
      ],
      [
        'dataIndex' => 'id_status',
        'renderer' => 'id_status',
      ],
      [
        'dataIndex' => 'id_status_link',
        'renderer' => 'id_status_link',
      ],

      [
        'dataIndex' => 'bool',
        'renderer' => 'toggle',
        'subTitle' => 'Boolean View'
      ],
      [
        'dataIndex' => 'number',
        'renderer' => 'number',
        'rendererAttrs' => [
          'decimalPlaces' => 3,
        ],
      ],
      [
        'dataIndex' => 'status',
        'renderer' => 'dropdown',
        'rendererAttrs' => [
          'dataSource' => $statuses,
          'valueField' => 'name',
        ],
      ],
      [
        'dataIndex' => 'user_id',
        'classList' => 'whitespace-nowrap',
        'renderer' => 'dropdown',
        'width' => 200,
        'rendererAttrs' => [
          'dataSourceKey' => 'users',
        ],
      ],


    ];

    $tables = [
      [
        'name' => 'John and lots of his friends',
        'hyper_link' => 'https://www.google.com',
        'column' => 'John and lots of his friends',
        'column_link' => 'https://www.google.com',
        'id_status' => 1,
        'id_status_link' => 1,
        'bool' => true,
        'number' => 1,
        'status' => 'active',
        'user_id' => 1,
        'column' => [
          [
            'id' => 101,
            'name' => 'Olivia',
          ]
        ],
      ],
      [
        'name' => 'John and lots of his friends',
        'hyper_link' => 'https://www.google.com',
        'column' => 'John and lots of his friends',
        'column_link' => 'https://www.google.com',
        'id_status' => 2,
        'id_status_link' => 2,
        'bool' => false,
        'number' => 2,
        'status' => 'inactive',
        'user_id' => 2,
        'column' => [
          [
            'id' => 201,
            'name' => 'Paul',
          ],
          [
            'id' => 201,
            'name' => 'Sandy',
          ],
        ],
      ],
      [
        'name' => 'John and lots of his friends',
        'hyper_link' => 'https://www.google.com',
        'column' => 'John and lots of his friends',
        'column_link' => 'https://www.google.com',
        'id_status' => 3,
        'id_status_link' => 3,
        'bool' => true,
        'number' => 3,
        'status' => 'active',
        'user_id' => 3,
      ],
    ];
    $duplicator = [];
    $expectedLines = 100;
    // $expectedLines = 35000; //loaded fine but challenging to F12
    for ($i = 0; $i < $expectedLines; $i++) {
      $duplicator[] = $tables[$i % sizeof($tables)];
    }

    foreach ($duplicator as $key => $row) {
      $duplicator[$key]['id'] = $key + 1;
      $duplicator[$key]['name'] .= " " . $key;
    }

    return  [
      'columns' => $columns,
      'dataSource' => $duplicator,
    ];
  }
}
