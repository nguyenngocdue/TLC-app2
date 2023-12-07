<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_tmpl_question extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "order_no",
        "exam_tmpl_id", "exam_tmpl_group_id",
        'question_type_id', "static_answer", "dynamic_answer",
        /*"status",*/
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getExamTmpl" => ["belongsTo", Exam_tmpl::class, "exam_tmpl_id"],
        "getExamTmplGroup" => ["belongsTo", Exam_tmpl_group::class, "exam_tmpl_group_id"],
        "getQuestionType" => ['belongsTo', Term::class, 'question_type_id'],
        "getDynamicAnswer" => ['belongsTo', Term::class, 'dynamic_answer'],
    ];

    public static $oracyParams = [];

    public function getExamTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getExamTmplGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getQuestionType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDynamicAnswer()
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

            ['dataIndex' => 'exam_tmpl_group',],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'description',],
            ['dataIndex' => 'question_type_id',],
            ['dataIndex' => 'static_answer',],
            ['dataIndex' => 'dynamic_answer',],
        ];
    }
}
