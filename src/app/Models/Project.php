<?php

namespace App\Models;

use App\BigThink\HasCachedAvatar;
use App\BigThink\ModelExtended;

class Project extends ModelExtended
{
    use HasCachedAvatar;

    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "owner_id"];

    protected $table = 'projects';

    public $eloquentParams = [
        "getAvatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getSubProjects" => ['hasMany', Sub_project::class, "project_id"],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],

        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
    ];

    public $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
    ];

    public function getAvatar()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }

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

    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public static function getAllProjectByCondition()
    {
        return self::whereIn('status', ['manufacturing', 'construction_site'])->get();
    }
    public function featured_image()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
