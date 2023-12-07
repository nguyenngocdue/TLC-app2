<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Exam_tmpl_group extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
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
}
