<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_course_training extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "training_location",
        "owner_id", 'training_datetime', 'slug',
    ];
    protected $table = "hr_course_trainings";

    public static $eloquentParams = [
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
        'getLocationTraining' => ['belongsTo', Workplace::class, 'training_location'],
    ];

    public static $oracyParams = [];
    public function getFacilitator()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLocationTraining()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
