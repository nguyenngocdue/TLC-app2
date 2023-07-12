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

        "attachment_training_course" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
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

    public function attachment_training_course()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
