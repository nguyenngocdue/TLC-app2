<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_sheet_line extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
        "order_no",
        "exam_tmpl_id", "exam_sheet_id",
        "exam_question_id", "question_type_id",
        "sub_question_1_id", "sub_question_1_value",
        "sub_question_2_id", "sub_question_2_value",
        "response_ids", "response_values",
    ];

    public static $statusless = true;
    public function getNameAttribute($value)
    {
        $question = $this->getExamTmplQuestion;
        return ($question ? $question->name : "");
    }

    public static $eloquentParams = [
        "getExamTmpl" => ["belongsTo", Exam_tmpl::class, "exam_tmpl_id"],
        "getExamSheet" => ["belongsTo", Exam_sheet::class, "exam_sheet_id"],
        "getQuestion" => ["belongsTo", Exam_tmpl_question::class, "exam_question_id"],
        "getQuestionType" => ["belongsTo", Term::class, "question_type_id"],
    ];

    public function getExamTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExamSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQuestion()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQuestionType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'no_print' => true, 'invisible' => true],

            ['dataIndex' => 'exam_sheet_id', 'invisible' => true, 'no_print' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'exam_question_id',],
            // ['dataIndex' => 'question_type_id',],

            ['dataIndex' => 'sub_question_1',],
            ['dataIndex' => 'sub_question_2',],

            ['dataIndex' => 'response_ids',],
            ['dataIndex' => 'response_values',],
        ];
    }
}
