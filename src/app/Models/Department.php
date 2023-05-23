<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Department extends ModelExtended
{
    protected $fillable = ["name", "description", "head_of_department", "slug"];

    protected $table = 'departments';
    protected static $statusless = true;

    public $eloquentParams = [
        "getHOD" => ['belongsTo', User::class, 'head_of_department'],
        "getMembers" => ['hasMany', User::class, 'department'],
        "getMembers2" => ['hasMany', User::class, 'department'],
    ];

    public function getHOD()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMembers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMembers2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
