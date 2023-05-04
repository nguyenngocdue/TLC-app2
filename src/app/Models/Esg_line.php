<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_line extends ModelExtended
{
    protected $fillable = [
        "id", "esg_metric_type_1_id", "esg_metric_type_2_id", "esg_metric_type_id", "unit", "factor",
        "m01", "m02", "m03", "m04", "m05", "m06", "m07", "m08", "m09", "m10", "m11", "m12",
        "year", "ytd", "remark", "owner_id"
    ];
    protected $table = "esg_lines";

    public $eloquentParams = [
        "getEsgSheet" => ['belongTo', Esg_sheet::class, 'esg_sheet_id'],
        "getEsgMetricType" => ['belongTo', Esg_metric_type::class, 'esg_metric_type_id'],
        "getEsgMetricType1" => ['belongTo', Esg_metric_type_1::class, 'esg_metric_type_1_id'],
        "getEsgMetricType2" => ['belongTo', Esg_metric_type_2::class, 'esg_metric_type_2_id'],
        "getUnit" => ['belongTo', Term::class, 'unit'],
    ];
    public function getEsgSheet()
    {
        $p = $this->eloquentParams([__FUNCTION__]);
        return $this->{$p[0]($p[1], $p[2])};
    }
    public function getEsgMetricType()
    {
        $p = $this->eloquentParams([__FUNCTION__]);
        return $this->{$p[0]($p[1], $p[2])};
    }
    public function getEsgMetricType1()
    {
        $p = $this->eloquentParams([__FUNCTION__]);
        return $this->{$p[0]($p[1], $p[2])};
    }
    public function getEsgMetricType2()
    {
        $p = $this->eloquentParams([__FUNCTION__]);
        return $this->{$p[0]($p[1], $p[2])};
    }
    public function getUnit()
    {
        $p = $this->eloquentParams([__FUNCTION__]);
        return $this->{$p[0]($p[1], $p[2])};
    }
}
