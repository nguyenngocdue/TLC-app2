<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_induction_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "user_id", "hse_induction_id",
        "owner_id", 'training_hours', "order_no",
    ];
    protected $table = "hse_induction_lines";
    protected static $statusless = true;

    public static $eloquentParams = [
        "getSignatures" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
        "getInduction" => ["belongsTo", Hse_induction::class, "hse_induction_id"],
        'getUsers' => ['belongsTo', User::class, 'user_id'],
    ];

    public static $oracyParams = [];

    public function getSignatures()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category')->orderBy('updated_at');
    }
    public function getInduction()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'hse_induction_id', 'title' => 'Induction ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'user_id',],
            ['dataIndex' => 'training_hours', 'cloneable' => true],
        ];
    }
}
