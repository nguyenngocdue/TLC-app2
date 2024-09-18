<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_14_user_detail extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'owner_id',
        'order_no',
        'zunit_test_14_id',
        'user_id',
        'col_span',
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'id', 'invisible' => !true,],
            ["dataIndex" => 'order_no',  'invisible' => true,],
            ["dataIndex" => 'zunit_test_14_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ["dataIndex" => 'user_id',],
            ["dataIndex" => 'col_span'],
        ];
    }
}
