<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_question extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getExamContest" => ["belongsTo", Exam::class, "exam_contest_id"],
        "getQuestionType" => ['belongsTo', Term::class, 'question_type_id'],
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
}
