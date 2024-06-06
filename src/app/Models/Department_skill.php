<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Department_skill extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "name", "description",
        "department_skill_group_id", "order_no",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDepartmentSkillGroup" => ['belongsTo', Department_skill_group::class, 'department_skill_group_id'],
    ];

    public function getDepartmentSkillGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'department_skill_group_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
        ];
    }
}
