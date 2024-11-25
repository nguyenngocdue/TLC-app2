<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;

class WelcomeFortuneDataSource05
{
  function getDataSource()
  {
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
        'renderer' => 'action_column',
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
        'dataIndex' => 'attachment_1',
        'renderer' => 'attachment',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [
          'maxFileCount' => 12,
          'maxPerLine' => 1,
          'groupId' => 1,
        ],
      ],
      [
        'dataIndex' => 'attachment_3',
        'renderer' => 'attachment',
        'width' => 240,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [
          'maxFileCount' => 12,
          'maxPerLine' => 5,
          'maxToShow' => 1000,
          'groupId' => 1,
        ],
      ],
      [
        'dataIndex' => 'avatar_user',
        'renderer' => 'avatar_user',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [],
      ],
      [
        'dataIndex' => 'avatar_users',
        'renderer' => 'avatar_user',
        'width' => 200,
        'classList' => 'whitespace-nowrap',
        'rendererAttrs' => [],
      ],
    ];

    $tables = [
      [
        'name' => 'John and lots of his friends',
        'attachment_1' => [
          'src' => asset('images/numbers/01.png'),

        ],
        'attachment_3' => [
          ['src' => asset('images/numbers/01.png'),],
          ['src' => asset('images/numbers/02.png'),],
          ['src' => asset('images/numbers/03.png'),],
          ['src' => asset('images/numbers/04.png'),],
          ['src' => asset('images/numbers/05.png'),],
          ['src' => asset('images/numbers/06.png'),],
          ['src' => asset('images/numbers/07.png'),],
          ['src' => asset('images/numbers/08.png'),],
          ['src' => asset('images/numbers/09.png'),],
          ['src' => asset('images/numbers/10.png'),],
          ['src' => asset('images/numbers/11.png'),],
          ['src' => asset('images/numbers/12.png'),],
        ],
        'avatar_user' => [
          'src' => asset('images/numbers/01.png'),
          'name' => 'AAA',
          'id' => 1,
        ],
        'avatar_users' => [
          [
            'src' => asset('images/numbers/01.png'),
            'name' => 'BBB',
            'id' => 1,
          ],
          [
            'src' => asset('images/numbers/02.png'),
            'name' => 'CCC',
            'id' => 2,
          ],
          [
            'src' => asset('images/numbers/03.png'),
            'name' => 'DDD',
            'id' => 3,
          ],
          [
            'src' => asset('images/numbers/04.png'),
            'name' => 'EEE',
            'id' => 4,
          ],
          [
            'src' => asset('images/numbers/05.png'),
            'name' => 'EEE',
            'id' => 5,
          ],
          [
            'src' => asset('images/numbers/06.png'),
            'name' => 'EEE',
            'id' => 6,
          ],
          [
            'src' => asset('images/numbers/07.png'),
            'name' => 'EEE',
            'id' => 7,
          ],
          [
            'src' => asset('images/numbers/08.png'),
            'name' => 'EEE',
            'id' => 8,
          ],
          [
            'src' => asset('images/numbers/09.png'),
            'name' => 'EEE',
            'id' => 9,
          ],
          [
            'src' => asset('images/numbers/10.png'),
            'name' => 'EEE',
            'id' => 10,
          ],
          [
            'src' => asset('images/numbers/11.png'),
            'name' => 'EEE',
            'id' => 11,
          ],
          [
            'src' => asset('images/numbers/12.png'),
            'name' => 'EEE',
            'id' => 12,
          ],
        ],
      ],

    ];
    $duplicator = [];
    $expectedLines = 10;
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
