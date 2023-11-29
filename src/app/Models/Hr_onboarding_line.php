<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_onboarding_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "user_id", "hr_onboarding_id",
        "owner_id", 'onboarding_hours', "order_no", "onboarding_course_id", "status",
        "employeeid", "position_rendered", "remark"
    ];
    // public static $statusless = true;

    public static $eloquentParams = [
        "getSignatures" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
        "getOnboarding" => ["belongsTo", Hr_onboarding::class, "hr_onboarding_id"],
        "getOnboardingCourse" => ["belongsTo", Hr_onboarding_course::class, "onboarding_course_id"],
        'getUsers' => ['belongsTo', User::class, 'user_id'],
    ];

    public static $oracyParams = [];

    public function getSignatures()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category')->orderBy('updated_at');
    }
    public function getOnboarding()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOnboardingCourse()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'hr_onboarding_id', 'title' => 'Onboarding ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'user_id',],
            ['dataIndex' => 'employeeid',],
            ['dataIndex' => 'position_rendered',],
            ['dataIndex' => 'onboarding_course_id', 'invisible' => true,],
            ['dataIndex' => 'onboarding_hours',  'cloneable' => true],
            ['dataIndex' => 'status', 'title' => "Result", 'cloneable' => true],
            ['dataIndex' => 'remark',],
        ];
    }
}
