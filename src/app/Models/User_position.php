<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position extends ModelExtended
{
    protected $fillable = [
        "name", "description", "slug", "owner_id",
        "position_prefix", "position_1", "position_2", "position_3",
    ];

    protected static $statusless = true;
    public static $nameless = true;

    // protected $with = ['getPositionPrefix', 'getPosition1', 'getPosition2', 'getPosition3'];

    public function getNameAttribute($value)
    {
        // $position_pres = ($this->getPositionPrefix) ? $this->getPositionPrefix->name : "";
        // $position_1 = ($this->getPosition1) ? $this->getPosition1->name : "";
        // $position_2 = ($this->getPosition2) ? $this->getPosition2->name : "";
        // $position_3 = ($this->getPosition3) ? $this->getPosition3->name : "";

        $position_pres = User_position_pre::findFromCache($this->position_prefix);
        $position_pres = ($position_pres) ? $position_pres->name : "";
        $position_1 = User_position1::findFromCache($this->position_1);
        $position_1 = ($position_1) ? $position_1->name : "";
        $position_2 = User_position2::findFromCache($this->position_2);
        $position_2 = ($position_2) ? $position_2->name : "";
        $position_3 = User_position3::findFromCache($this->position_3);
        $position_3 = ($position_3) ? $position_3->name : "";

        $position_3 = $position_3 ? "($position_3)" : "";
        $str = "$position_pres $position_1 $position_2 $position_3";
        $str = trim(str_replace("  ", " ", $str));
        return $str;
        // $value = (new User_PositionRendered())($position_pres, $position_1, $position_2, $position_3);
    }

    public static $eloquentParams = [
        // "getUsers" => ['hasMany', User::class, 'position_1', 'id'],
        "getPositionPrefix" => ['belongsTo', User_position_pre::class, 'position_prefix'],
        "getPosition1" => ['belongsTo', User_position1::class, 'position_1'],
        "getPosition2" => ['belongsTo', User_position2::class, 'position_2'],
        "getPosition3" => ['belongsTo', User_position3::class, 'position_3'],
    ];

    // public function getUsers()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    public function getPositionPrefix()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
