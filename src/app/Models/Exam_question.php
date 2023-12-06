<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_question extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "order_no",
        "exam_contest_id", 'question_type_id', "static_answer", "dynamic_answer",
        /*"status",*/
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getExamContest" => ["belongsTo", Exam_contest::class, "exam_contest_id"],
        "getQuestionType" => ['belongsTo', Term::class, 'question_type_id'],
        "getDynamicAnswer" => ['belongsTo', Term::class, 'dynamic_answer'],
    ];

    public static $oracyParams = [];

    public function getExamContest()
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
            ['dataIndex' => 'exam_contest_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'question_type_id',],
            ['dataIndex' => 'static_answer',],
            ['dataIndex' => 'dynamic_answer',],
        ];
    }
}
