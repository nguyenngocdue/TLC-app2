<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Support\Facades\Log;

class Zunit_test_05 extends ModelExtended
{
    protected $fillable = ["id", "name", "parent_id", "order_no", 'owner_id',];
    public static $statusless = true;

    public static $eloquentParams = [
        "attachment_1" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_2" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_3" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_4" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_5" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_6" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function attachment_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_4()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_5()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_6()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id',],
            ['dataIndex' => 'parent_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'cloneable' => true],
            ['dataIndex' => 'attachment_1', 'cloneable' => !true],
            ['dataIndex' => 'attachment_2', 'cloneable' => !true],
            ['dataIndex' => 'attachment_3', 'cloneable' => !true],
            ['dataIndex' => 'attachment_4', 'cloneable' => !true],
            ['dataIndex' => 'attachment_5', 'cloneable' => !true],
            ['dataIndex' => 'attachment_6', 'cloneable' => !true],
        ];
    }
}
