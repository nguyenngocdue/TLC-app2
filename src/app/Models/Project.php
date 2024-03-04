<?php

namespace App\Models;

use App\BigThink\HasCachedAvatar;
use App\BigThink\ModelExtended;

class Project extends ModelExtended
{
    use HasCachedAvatar;

    protected $fillable = ["id", "name", "description", "slug", "status", "owner_id"];

    public static $eloquentParams = [
        "getSubProjects" => ['hasMany', Sub_project::class, "project_id"],
        "getAvatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        "getQrAppSource" => ['belongsTo', Term::class, 'qr_app_source'],
    ];

    public static $oracyParams = [
        "getProjectMembers()" => ['getCheckedByField', User::class],
    ];

    public function getAvatar()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }

    public function getProjectMembers()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSubProjects()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public static function getAllProjectByCondition()
    {
        $status = config("project.active_statuses." . static::getTableName());
        return self::whereIn('status', $status)->get();
    }
    public function featured_image()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
   
    public function getQrAppSource()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public static function getSubProjectTree()
    {
        $tree = [
            ["key" => 0, "name" => "All Projects"],
        ];
        $projects = Project::with('getSubProjects')->orderBy('name')->get();
        foreach ($projects as $project) {
            $key = "project_" . $project->id;
            $name = $project->name . ($project->description ? " - " . $project->description : "");
            $tree[$key] = ['key' => $key, 'name' => $name, "parent" => 0];
            $subProjects = $project->getSubProjects->sortBy('name');
            foreach ($subProjects as $subProject) {
                // dump($subProject->name);
                $key = "subproject_" . $subProject->id;
                $name = $subProject->name . ($subProject->description ? " - " . $subProject->description : "");
                $tree[$key] = ["key" => $key, "name" => $name, "parent" => "project_" . $project->id];
            }
        }
        // dd($tree);
        return $tree;
    }
}
