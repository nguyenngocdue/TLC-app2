<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;

class WelcomeFortuneDataSource03
{
  function getDataSource()
  {
    $statuses = LibStatuses::getAll();
    $columns = [
      [
        // 'dataIndex' => 'checkbox_for_line',
        // 'title' => 'CB',
        'renderer' => 'checkbox_for_line',
        'fixed' => 'left',
        'rendererAttrs' => [
          'hasMasterCheckbox' => true,
        ],
      ],
      // ['dataIndex' => 'id', 'title' => 'ID', 'renderer' => 'id_link', 'fixed' => 'left'],
      [
        'dataIndex' => 'id',
        'title' => 'Action',
        'renderer' => 'action_column',
        'fixed' => 'left',
        'width' => 70,
      ],
      // ['dataIndex' => 'id', 'title' => 'Print', 'renderer' => 'action_print.', 'fixed' => 'left'],
      ['dataIndex' => 'hidden_column', 'invisible' => true, 'fixed' => 'left'],
      [
        'dataIndex' => 'order_no',
        'renderer' => 'text',
        'invisible' => !true,
        'fixed' => 'left',
        'width' => 60,
      ],
      [
        'dataIndex' => 'name',
        'renderer' => 'text',
        'fixed' => 'left',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [],
      ],
      [
        'dataIndex' => 'bool',
        'renderer' => 'toggle',
        'subTitle' => 'Toggle'
      ],
      [
        'dataIndex' => 'bool2',
        'renderer' => 'checkbox',
        'subTitle' => 'Checkbox',
        'rendererAttrs' => [
          'hasMasterCheckbox' => true,
        ],
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
          'labelField' => 'full_name'
        ],
      ],
      [
        'dataIndex' => 'user_employeeid',
        'renderer' => 'dropdown',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [
          'dataSourceKey' => 'users',
          'valueField' => 'employeeid',
          'tooltipField' => 'employeeid',
          'labelField' => 'full_name'
        ],
      ],
      [
        'dataIndex' => 'date01',
        'title' => 'Date Time',
        'width' => 140,
        'renderer' => 'picker_datetime',
        'rendererAttrs' => [],
      ],
      [
        'dataIndex' => 'date02',
        'title' => 'Date',
        // 'width' => 120,
        'renderer' => 'picker_datetime',
        'rendererAttrs' => [
          'pickerType' => 'date',
        ],
      ],
      [
        'dataIndex' => 'date03',
        'title' => 'Time',
        // 'width' => 120,
        'renderer' => 'picker_datetime',
        'rendererAttrs' => [
          'pickerType' => 'time',
        ],
      ],
      [
        'dataIndex' => 'date04',
        'title' => 'Month',
        // 'width' => 120,
        'renderer' => 'picker_datetime',
        'rendererAttrs' => [
          'pickerType' => 'month',
        ],
      ],
      [
        'dataIndex' => 'date05',
        'title' => 'Year',
        // 'width' => 120,
        'renderer' => 'picker_datetime',
        'rendererAttrs' => [
          'pickerType' => 'year',
        ],
      ],


    ];

    $tables = [
      [
        'name' => 'Alice',
        'status' => 'active',
        'text' => 'a string',
        'date01' => '2021-01-01 06:00:00',
        'date02' => '2021-02-01',
        'date03' => '06:00:00',
        'date04' => '2021-04-01',
        'date05' => '2021-05-01',
        'bool' => true,
        'bool2' => false,
        'number' => 1,
        'user_id' => null,
        'user_employeeid' => null,
      ],
      [
        'name' => 'Bob',
        'status' => 'new',
        'text' => 'another string',
        'date01' => '2021-01-01 08:00:00',
        'date02' => '2021-02-02',
        'date03' => '08:00:00',
        'date04' => '2021-04-04',
        'date05' => '2021-05-05',
        'bool' => false,
        'bool2' => true,
        'number' => 02.009,
        'user_id' => 37,
        'user_employeeid' => 'TLCM01069',
      ],
      [
        'name' => 'Ceasar',
        'status' => 'new',
        'text' => 'another string',
        'date01' => '2021-01-01 23:00:00',
        'date02' => '2022-02-02',
        'date03' => '23:00:00',
        'date04' => '2024-04-04',
        'date05' => '2025-05-05',
        'bool' => false,
        'bool2' => true,
        'number' => -1e-3,
        'user_id' => 222,
        'user_employeeid' => 'TLCM01034',
      ],
    ];
    $duplicator = [];
    $expectedLines = 25;
    // $expectedLines = 35000; //loaded fine but challenging to F12
    for ($i = 0; $i < $expectedLines; $i++) {
      $duplicator[] = $tables[$i % 3];
    }

    foreach ($duplicator as $key => $row) {
      $duplicator[$key]['name'] .= " " . ($key + 1);
    }

    return  [
      'columns' => $columns,
      'dataSource' => $duplicator,
    ];
  }
}
