<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_onboarding extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "onboarding_location_id", 'onboarding_course_id',
        "owner_id", 'onboarding_datetime', 'slug',
    ];
    public static $nameless = true;

    public static $eloquentParams = [
        "getLines" => ["hasMany", Hr_onboarding_line::class, "hr_onboarding_id"],
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
        "getOnboardingCourse" => ["belongsTo", Hr_onboarding_course::class, "onboarding_course_id"],
        "getOnboardingLocation" => ["belongsTo", Workplace::class, "onboarding_location_id"],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOnboardingLocation()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getFacilitator()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOnboardingCourse()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
