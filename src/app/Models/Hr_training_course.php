<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_training_course extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "training_location_id",
        "owner_id", 'training_datetime', 'slug',
    ];
    protected $table = "hr_training_courses";

    public static $eloquentParams = [
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
        'getLocationTraining' => ['belongsTo', Workplace::class, 'training_location_id'],
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
