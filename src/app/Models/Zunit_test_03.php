<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_03 extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'parent_id',
        "datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"
    ];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_03s';

    public $eloquentParams = [];

    public function getManyLineParams()
    {
        return [
            // ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'datetime1',],
            ['dataIndex' => 'datetime2',],
            ['dataIndex' => 'datetime3',],
            ['dataIndex' => 'datetime4',],
            ['dataIndex' => 'datetime5',],
            ['dataIndex' => 'datetime6',],
            ['dataIndex' => 'datetime7',],
        ];
    }
}
