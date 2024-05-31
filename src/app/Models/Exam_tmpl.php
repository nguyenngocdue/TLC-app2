<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_tmpl extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getGroups" => ["hasMany", Exam_tmpl_group::class, "exam_tmpl_id"],
        "getQuestions" => ["hasMany", Exam_tmpl_question::class, "exam_tmpl_id"],
    ];

    public function getGroups()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQuestions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
