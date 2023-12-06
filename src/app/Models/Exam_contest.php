<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_contest extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getQuestions" => ["hasMany", Exam_question::class, "exam_contest_id"],
    ];

    public static $oracyParams = [];

    public function getQuestions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
