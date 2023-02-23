<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Support\Facades\Log;

class Zunit_test_05 extends ModelExtended
{
    protected $fillable = ["id", "name", "parent_id", "order_no"];
    protected $primaryKey = "id";
    protected $table = "zunit_test_05s";

    public $eloquentParams = [
        "attachment_1" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_2" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_3" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_4" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_5" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function attachment_1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_4()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_5()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
