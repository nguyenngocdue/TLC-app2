<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\BigThink\SoftDeletesWithDeletedBy;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zunit_test_03 extends ModelExtended
{
    use SoftDeletesWithDeletedBy;

    protected $fillable = [
        'id', 'name', 'parent_id', 'order_no', "owner_id",
        "datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"
    ];

    public static $statusless = true;

    public static $eloquentParams = [];

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'datetime1', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime2', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime3', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime4', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime5', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime6', 'cloneable' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'datetime7', 'cloneable' => true, 'footer' => 'agg_none'],
        ];
    }
}
