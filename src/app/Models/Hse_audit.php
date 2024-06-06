<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_audit extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "assignee_1", "workplace_id", "audit_datetime", "owner_id"
    ];

    public static $eloquentParams = [
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "getCorrectiveActions" => ['morphMany', Hse_corrective_action::class, 'correctable', 'correctable_type', 'correctable_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCorrectiveActions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
