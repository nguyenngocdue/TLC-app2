<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zunit_test_03 extends ModelExtended
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'name', 'parent_id', 'order_no', "owner_id", "delete_at",
        "datetime1", "datetime2", "datetime3", "datetime4", "datetime5", "datetime6", "datetime7"
    ];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_03s';

    public $eloquentParams = [];

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'datetime1', 'cloneable' => true],
            ['dataIndex' => 'datetime2', 'cloneable' => true],
            ['dataIndex' => 'datetime3', 'cloneable' => true],
            ['dataIndex' => 'datetime4', 'cloneable' => true],
            ['dataIndex' => 'datetime5', 'cloneable' => true],
            ['dataIndex' => 'datetime6', 'cloneable' => true],
            ['dataIndex' => 'datetime7', 'cloneable' => true],
        ];
    }
}
