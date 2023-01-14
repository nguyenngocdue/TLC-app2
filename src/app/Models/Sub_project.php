<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasStatus;

class Sub_project extends ModelExtended
{
    use HasStatus;
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "status", "project_id"];
    protected $primaryKey = 'id';
    protected $table = 'sub_projects';

    public $eloquentParams = [
        "prodOrders" => ['hasMany', Prod_order::class],
        "getProject" => ['belongsTo', Project::class, "project_id"],
    ];

    public function prodOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
