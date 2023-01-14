<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Project extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status"];
    protected $primaryKey = 'id';
    protected $table = 'projects';

    public $eloquentParams = [
        // "prodOrders" => ['hasMany', Prod_order::class],
        "getSubProjects" => ['hasMany', Sub_project::class, "project_id"],
    ];

    // public function prodOrders()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1]);
    // }
    public function getSubProjects()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
