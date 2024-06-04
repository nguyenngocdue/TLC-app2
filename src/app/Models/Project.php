<?php

namespace App\Models;

use App\BigThink\HasCachedAvatar;
use App\BigThink\HasShowOnScreens;
use App\BigThink\ModelExtended;

class Project extends ModelExtended
{
    use HasCachedAvatar;
    use HasShowOnScreens;

    protected $fillable = ["id", "name", "description", "slug", "status", "owner_id", "qr_app_source"];

    public static $eloquentParams = [
        "getSubProjects" => ['hasMany', Sub_project::class, "project_id"],
        "getAvatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        "getQrAppSource" => ['belongsTo', Term::class, 'qr_app_source'],

        "getProjectMembers" => ["belongsToMany", User::class, "ym2m_project_user_project_member"],
        "getScreensShowMeOn" => ["belongsToMany", Term::class, "ym2m_project_term_show_me_on"],
    ];

    public function getScreensShowMeOn()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAvatar()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }

    public function getProjectMembers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
