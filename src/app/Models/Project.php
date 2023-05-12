<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Project extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'projects';

    public $eloquentParams = [
        "getSubProjects" => ['hasMany', Sub_project::class, "project_id"],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],

    ];

    public $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
    ];

    public function getProjectMembers()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSubProjects()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public static function getAllProjectByCondition()
    {
        return self::whereIn('status', ['manufacturing', 'construction_site'])->get();
    }
}
