<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_training_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "user_id", "hr_training_id",
        "owner_id", 'training_hours', "order_no", "training_course_id", "status",
    ];
    protected $table = "hr_training_lines";
    // protected static $statusless = true;

    public static $eloquentParams = [
        "getSignatures" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
        "getTraining" => ["belongsTo", Hr_training::class, "hr_training_id"],
        "getTrainingCourse" => ["belongsTo", Hr_training_course::class, "training_course_id"],
        'getUsers' => ['belongsTo', User::class, 'user_id'],
    ];

    public static $oracyParams = [];

    public function getSignatures()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category')->orderBy('updated_at');
    }
    public function getTraining()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTrainingCourse()
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
            ['dataIndex' => 'hr_training_id', 'title' => 'Training ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'user_id',],
            ['dataIndex' => 'training_course_id', 'invisible' => !true,],
            ['dataIndex' => 'training_hours',  'cloneable' => true],
            ['dataIndex' => 'status', 'title' => "Result", 'cloneable' => true],
        ];
    }
}
