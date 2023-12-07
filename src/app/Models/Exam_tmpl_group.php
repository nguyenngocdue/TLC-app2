<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_tmpl_group extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "order_no",
        "exam_tmpl_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getExamTmpl" => ["belongsTo", Exam_tmpl::class, "exam_tmpl_id"],
    ];

    public static $oracyParams = [];

    public function getExamTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'exam_tmpl_id', 'value_as_parent_id' => true, 'invisible' => true,],

            // ['dataIndex' => 'exam_tmpl_group_id',],
            ['dataIndex' => 'name',],
            // ['dataIndex' => 'description',],
            // ['dataIndex' => 'question_type_id',],
            // ['dataIndex' => 'static_answer',],
            // ['dataIndex' => 'dynamic_answer',],
        ];
    }
}
