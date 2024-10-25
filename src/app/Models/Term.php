<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Term extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',
        // 'slug',
        'field_id',
        'parent1_id',
        'parent2_id',
        'parent3_id',
        'parent4_id',

        'owner_id',
        'order_no',
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        'getField' => ['belongsTo', Field::class, 'field_id'],
    ];

    public function getField()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'field_id', 'value_as_parent_id' => true, 'invisible' => true],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'parent1_id'],
            ['dataIndex' => 'parent2_id'],
            ['dataIndex' => 'parent3_id'],
            ['dataIndex' => 'parent4_id'],
            // ['dataIndex' => 'description'],
        ];
    }
}
