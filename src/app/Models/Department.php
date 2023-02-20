<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Department extends ModelExtended
{
    protected $fillable = ["name", "description", "head_of_department", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'departments';

    public $eloquentParams = [
        "user" => ['belongsTo', User::class, 'head_of_department'],
        "Users_Count" => ['hasMany', User::class, 'department'],
        "Users_Count2" => ['hasMany', User::class, 'department'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function Users_Count()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function Users_Count2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
