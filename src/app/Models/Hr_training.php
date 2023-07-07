<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_training extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "training_location",
        "owner_id", 'training_datetime', 'slug',
    ];
    protected $table = "hr_trainings";

    public static $eloquentParams = [
        "getLines" => ["hasMany", Hr_training_line::class, "hr_training_id"],
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
        "getTrainingCourse" => ["belongsTo", Hr_training_course::class, "course_training_id"],
    ];

    public static $oracyParams = [];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getFacilitator()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTrainingCourse()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
