<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_first_aid extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "injured_person_id", "assignee_1",
        "owner_id", "injury_datetime", "slug", "nature_of_injury", "treatment_provided",
        "employeeid", 'work_area_id',
    ];

    public static $eloquentParams = [
        'getInjuredPerson' => ['belongsTo', User::class, 'injured_person_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
    ];

    public static $nameless = true;
    public function getInjuredPerson()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
