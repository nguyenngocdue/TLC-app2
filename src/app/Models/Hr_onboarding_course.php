<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_onboarding_course extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "onboarding_location_id",
        "owner_id", 'onboarding_datetime', 'slug',
    ];

    public static $eloquentParams = [
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
        'getLocationOnboarding' => ['belongsTo', Workplace::class, 'onboarding_location_id'],
        "attachment_onboarding_course" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getFacilitator()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLocationOnboarding()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_onboarding_course()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
