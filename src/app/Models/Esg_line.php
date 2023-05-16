<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_line extends ModelExtended
{
    protected $fillable = [
        "id", "esg_metric_type_id", "esg_metric_type_1_id", "esg_metric_type_2_id", "unit", "factor",
        "esg_sheet_id",
        "m01", "m02", "m03", "m04", "m05", "m06", "m07", "m08", "m09", "m10", "m11", "m12",
        "ytd", "remark", "owner_id", "status", "order_no",
    ];
    protected $table = "esg_lines";
    public $nameless = true;

    public $eloquentParams = [
        "getEsgSheet" => ['belongsTo', Esg_sheet::class, 'esg_sheet_id'],
        "getEsgMetricType" => ['belongsTo', Esg_metric_type::class, 'esg_metric_type_id'],
        "getEsgMetricType1" => ['belongsTo', Esg_metric_type_1::class, 'esg_metric_type_1_id'],
        "getEsgMetricType2" => ['belongsTo', Esg_metric_type_2::class, 'esg_metric_type_2_id'],
        "getUnit" => ['belongsTo', Term::class, 'unit'],

        "getOwner" => ['belongsTo', User::class, 'owner_id'],
        "getDeletedBy" => ['belongsTo', User::class, 'deleted_by'],
    ];
    public function getEsgSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEsgMetricType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEsgMetricType1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getEsgMetricType2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUnit()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'esg_sheet_id', 'title' => 'OT ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'esg_metric_type_id'],
            ['dataIndex' => 'esg_metric_type_1_id'],
            ['dataIndex' => 'esg_metric_type_2_id'],

            // ['dataIndex' => 'year', 'cloneable' => true],
            ['dataIndex' => 'unit'],
            ['dataIndex' => 'factor'],

            ['dataIndex' => 'm01'],
            ['dataIndex' => 'm02'],
            ['dataIndex' => 'm03'],
            ['dataIndex' => 'm04'],
            ['dataIndex' => 'm05'],
            ['dataIndex' => 'm06'],
            ['dataIndex' => 'm07'],
            ['dataIndex' => 'm08'],
            ['dataIndex' => 'm09'],
            ['dataIndex' => 'm10'],
            ['dataIndex' => 'm11'],
            ['dataIndex' => 'm12'],

            ['dataIndex' => 'ytd'],

            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
